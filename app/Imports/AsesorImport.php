<?php

namespace App\Imports;

use App\Models\Asesor;
use App\Models\Account;
use App\Models\Skema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AsesorImport implements ToCollection, WithChunkReading
{
    public int $imported = 0;
    public int $skipped  = 0;
    public int $invalid  = 0;

    /** @var array<string> */
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 1;

            // Skip completely empty rows
            if ($row->filter()->isEmpty()) {
                continue;
            }

            $nama   = trim((string) ($row[0] ?? ''));
            $noMet  = trim((string) ($row[1] ?? ''));
            $skema  = trim((string) ($row[2] ?? ''));

            // Skip header row
            if (strtolower($nama) === 'nama' || strtolower($nama) === 'nama asesor') {
                continue;
            }

            if ($nama === '' || $noMet === '') {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: Nama dan No. Met tidak boleh kosong.";
                continue;
            }

            // Skip if no_met already exists in asesor table
            if (Asesor::where('no_met', $noMet)->exists()) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNum}: No. Met {$noMet} sudah terdaftar (dilewati).";
                continue;
            }

            // Skip if no_met already exists in accounts table
            if (Account::where('id', $noMet)->exists()) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNum}: Akun dengan ID/No. Met {$noMet} sudah digunakan (dilewati).";
                continue;
            }

            // Create Asesor
            $asesor = Asesor::create([
                'nama'   => $nama,
                'no_met' => $noMet,
            ]);

            // Create Account — password default = no_met itself
            Account::create([
                'id'       => $noMet,
                'nama'     => $nama,
                'password' => Hash::make($noMet),
                'role'     => 'asesor',
            ]);

            // Associate Skemas if provided
            if ($skema !== '') {
                // Split by comma
                $skemaNames = array_map('trim', explode(',', $skema));
                $skemaIds = [];
                foreach ($skemaNames as $skemaName) {
                    if ($skemaName === '') continue;
                    // Find skema by name or number case-insensitively
                    $foundSkema = Skema::where('nama_skema', 'LIKE', $skemaName)
                        ->orWhere('nomor_skema', 'LIKE', $skemaName)
                        ->first();
                    if ($foundSkema) {
                        $skemaIds[] = $foundSkema->id;
                    } else {
                        $this->errors[] = "Baris {$rowNum}: Skema \"{$skemaName}\" untuk Asesor \"{$nama}\" tidak ditemukan di sistem.";
                    }
                }
                if (!empty($skemaIds)) {
                    $asesor->skemas()->sync($skemaIds);
                }
            }

            $this->imported++;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
