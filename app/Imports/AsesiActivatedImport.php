<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Asesi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AsesiActivatedImport implements ToCollection, WithChunkReading
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $invalid = 0;

    /** @var array<string> */
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 1;

            if ($row->filter()->isEmpty()) {
                continue;
            }

            $nama = trim((string) ($row[1] ?? ''));
            $nik = trim((string) ($row[2] ?? ''));
            $tempatLahir = trim((string) ($row[3] ?? ''));
            $tanggalLahirRaw = trim((string) ($row[4] ?? ''));
            $jenisKelaminRaw = trim((string) ($row[5] ?? ''));
            $alamat = trim((string) ($row[6] ?? ''));
            $telp = trim((string) ($row[7] ?? ''));
            $email = trim((string) ($row[8] ?? ''));

            // Skip header row
            if (strtolower($nik) === 'nik') {
                continue;
            }

            // Normalize NIK from scientific/decimal format
            if (preg_match('/^(\d{16})\.0*$/', $nik, $m)) {
                $nik = $m[1];
            }
            if (preg_match('/[eE]/', $nik) && preg_match('/^([+-]?)(\d+)\.?(\d*)[eE]\+?(\d+)$/', $nik, $m)) {
                $digits = $m[2] . $m[3];
                $shift = (int) $m[4] - strlen($m[3]);
                $nik = $m[1] . $digits . ($shift >= 0 ? str_repeat('0', $shift) : '');
            }

            if (!preg_match('/^\d{16}$/', $nik)) {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: NIK \"{$nik}\" tidak valid (harus 16 digit).";
                continue;
            }

            if ($nama === '') {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: Nama asesi kosong untuk NIK {$nik}.";
                continue;
            }

            // Hanya akun yang sudah aktivasi (akun asesi sudah ada)
            $account = Account::where('NIK', $nik)->where('role', 'asesi')->first();
            if (!$account) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNum}: NIK {$nik} belum punya akun asesi aktif (dilewati).";
                continue;
            }

            // Hanya update data asesi yang sudah ada
            $asesi = Asesi::where('NIK', $nik)->first();
            if (!$asesi) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNum}: Data asesi NIK {$nik} belum terdaftar (dilewati).";
                continue;
            }

            $jenisKelamin = $this->normalizeJenisKelamin($jenisKelaminRaw);
            if ($jenisKelaminRaw !== '' && $jenisKelamin === null) {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: Jenis kelamin harus L/P atau Laki-laki/Perempuan.";
                continue;
            }

            $tanggalLahir = $this->parseDate($tanggalLahirRaw);
            if ($tanggalLahirRaw !== '' && $tanggalLahir === null) {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: Tanggal lahir \"{$tanggalLahirRaw}\" tidak valid.";
                continue;
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->invalid++;
                $this->errors[] = "Baris {$rowNum}: Email \"{$email}\" tidak valid.";
                continue;
            }

            $asesi->update([
                'nama' => $nama,
                'tempat_lahir' => $tempatLahir !== '' ? $tempatLahir : null,
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelamin,
                'alamat' => $alamat !== '' ? $alamat : null,
                'telepon_hp' => $telp !== '' ? $telp : null,
                'email' => $email !== '' ? $email : null,
            ]);

            if ($account->nama !== $nama) {
                $account->update(['nama' => $nama]);
            }

            $this->imported++;
        }
    }

    private function normalizeJenisKelamin(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        $clean = strtolower(trim($value));

        if (in_array($clean, ['l', 'lk', 'laki-laki', 'laki laki'], true)) {
            return 'L';
        }

        if (in_array($clean, ['p', 'pr', 'perempuan'], true)) {
            return 'P';
        }

        return null;
    }

    private function parseDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
