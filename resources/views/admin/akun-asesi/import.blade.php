@extends('admin.layout')

@section('title', 'Import Akun Asesi')
@section('page-title', 'Import Akun Asesi dari Excel')

@section('content')
<div class="page-header">
    <h2>Import Akun Asesi (Excel/CSV)</h2>
    <a href="{{ route('admin.akun-asesi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    <!-- Upload Form -->
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom:20px;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>Kesalahan:</strong>
                        <ul style="margin:4px 0 0;padding-left:16px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.akun-asesi.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf

                <div class="form-group" style="margin-bottom:24px;">
                    <label for="file" style="font-weight:600;font-size:14px;color:#374151;display:block;margin-bottom:8px;">
                        File Excel / CSV <span class="required">*</span>
                    </label>

                    <div id="drop-zone" onclick="document.getElementById('file').click()"
                         style="border:2px dashed #d1d5db;border-radius:10px;padding:32px;text-align:center;
                                cursor:pointer;transition:all .2s;background:#fafafa;">
                        <i class="bi bi-cloud-upload" style="font-size:40px;color:#9ca3af;display:block;margin-bottom:10px;"></i>
                        <p style="font-size:14px;font-weight:600;color:#374151;margin-bottom:4px;">
                            Klik atau seret file ke sini
                        </p>
                        <p style="font-size:12px;color:#9ca3af;">Format: .xlsx, .xls, .csv &bull; Maks: 5 MB</p>
                        <p id="file-name" style="margin-top:10px;font-size:12px;font-weight:600;color:#14532d;display:none;"></p>
                    </div>

                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv"
                           style="display:none;" onchange="onFileChange(this)" required>
                </div>

                <div style="background:#f0fdf4;border-left:4px solid #14532d;padding:12px 16px;border-radius:6px;
                            margin-bottom:20px;font-size:12px;color:#14532d;line-height:1.7;">
                    <i class="bi bi-info-circle"></i>
                    <strong>Password default</strong> setiap akun akan diset sama dengan NIK-nya.<br>
                    Siswa dapat mengubah password setelah login.
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;" id="submitBtn">
                    <i class="bi bi-upload"></i> Upload &amp; Import
                </button>
            </form>
        </div>
    </div>

    <!-- Instructions + Template -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="card">
            <div class="card-body">
                <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:12px;">
                    <i class="bi bi-question-circle" style="color:#14532d;"></i> Petunjuk Format
                </h3>

                <div style="overflow-x:auto;margin-bottom:16px;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f1f5f9;">
                                <th style="padding:8px 12px;text-align:left;border:1px solid #e2e8f0;font-weight:600;">Kolom A</th>
                                <th style="padding:8px 12px;text-align:left;border:1px solid #e2e8f0;font-weight:600;">Kolom B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background:#fefce8;">
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;font-weight:700;color:#92400e;">NIK</td>
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;font-weight:700;color:#92400e;">Nama</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;font-family:monospace;">3204010101010001</td>
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;">Budi Santoso</td>
                            </tr>
                            <tr style="background:#f8fafc;">
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;font-family:monospace;">3204010101010002</td>
                                <td style="padding:8px 12px;border:1px solid #e2e8f0;">Siti Rahayu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <ul style="font-size:12px;color:#64748b;line-height:2;padding-left:16px;margin:0;">
                    <li>Baris pertama boleh berisi header (<strong>NIK, Nama</strong>) atau langsung data</li>
                    <li>NIK harus tepat <strong>16 digit angka</strong></li>
                    <li>NIK yang sudah terdaftar akan <strong>dilewati</strong></li>
                    <li>Kolom B (Nama) bersifat opsional (hanya referensi)</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align:center;padding:20px;">
                <i class="bi bi-file-earmark-spreadsheet" style="font-size:36px;color:#16a34a;display:block;margin-bottom:10px;"></i>
                <p style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:4px;">Download Template</p>
                <p style="font-size:11px;color:#94a3b8;margin-bottom:14px;">File CSV siap pakai dengan contoh data</p>
                <a href="{{ route('admin.akun-asesi.template') }}" class="btn btn-secondary" style="font-size:13px;">
                    <i class="bi bi-download"></i> Download Template
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function onFileChange(input) {
    if (input.files && input.files[0]) {
        const name = document.getElementById('file-name');
        name.textContent = '✓ ' + input.files[0].name;
        name.style.display = 'block';
        document.getElementById('drop-zone').style.borderColor = '#14532d';
        document.getElementById('drop-zone').style.background = '#f0fdf4';
    }
}

document.getElementById('importForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
});

// Drag & drop support
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor = '#14532d'; });
dz.addEventListener('dragleave', () => { dz.style.borderColor = '#d1d5db'; });
dz.addEventListener('drop', e => {
    e.preventDefault();
    const fi = document.getElementById('file');
    fi.files = e.dataTransfer.files;
    onFileChange(fi);
});
</script>
@endsection
