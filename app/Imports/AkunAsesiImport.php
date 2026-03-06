<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AkunAsesiImport implements ToCollection, WithChunkReading
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

            $nik  = trim((string) ($row[0] ?? ''));
            $nama = trim((string) ($row[1] ?? ''));

            // Skip header row
            if (strtolower($nik) === 'nik') {
                continue;
            }

            // Remove trailing ".0" if number stored as decimal
            if (preg_match('/^(\d{16})\.0*$/', $nik, $m)) {
                $nik = $m[1];
            }

            // Convert from scientific notation if needed
            if (preg_match('/[eE]/', $nik) && preg_match('/^([+-]?)(\d+)\.?(\d*)[eE]\+?(\d+)$/', trim($nik), $m)) {
                $digits = $m[2] . $m[3];
                $shift  = (int) $m[4] - strlen($m[3]);
                $nik = $m[1] . $digits . ($shift >= 0 ? str_repeat('0', $shift) : '');
            }

            // Validate: NIK must be 16 digits
            if (!preg_match('/^\d{16}$/', $nik)) {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: NIK \"{$nik}\" tidak valid (harus 16 digit angka).";
                continue;
            }

            // Skip if NIK already exists
            if (Account::where('NIK', $nik)->exists()) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNum}: NIK {$nik} sudah terdaftar (dilewati).";
                continue;
            }

            // Create account — password default = NIK itself
            Account::create([
                'id'       => $nik,
                'NIK'      => $nik,
                'nama'     => $nama,
                'password' => $nik,
                'role'     => 'asesi',
            ]);

            $this->imported++;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
