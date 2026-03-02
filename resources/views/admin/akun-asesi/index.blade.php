@extends('admin.layout')

@section('title', 'Akun Asesi')
@section('page-title', 'Kelola Akun Asesi')

@section('content')
<div class="asesi-management">
    <!-- Header -->
    <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:16px;padding:32px;margin-bottom:24px;box-shadow:0 4px 6px rgba(0,0,0,.1);">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div style="color:#fff;">
                <h2 style="font-size:28px;font-weight:700;margin:0 0 8px 0;color:#fff;">Kelola Akun Asesi</h2>
                <p style="font-size:14px;margin:0;color:rgba(255,255,255,0.9);font-weight:400;">Buat dan kelola akun asesi berdasarkan NIK. Import massal via CSV.</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="btn btn-outline" onclick="openImportModal()" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:8px;cursor:pointer;transition:all 0.2s;backdrop-filter:blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import CSV
                </button>
                <button class="btn btn-primary" onclick="openCreateModal()" style="background:#fff;border:none;color:#667eea;padding:10px 20px;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:8px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#f8f9ff';this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.background='#fff';this.style.transform='translateY(0)';this.style.boxShadow='none'">
                    <i class="bi bi-plus-circle"></i> Tambah Akun
                </button>
            </div>
        </div>
    </div>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <div id="toast-success" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;display:flex;align-items:center;gap:12px;min-width:320px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="width:40px;height:40px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:20px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:2px;">Berhasil!</div>
                <div style="color:#64748b;font-size:13px;">{{ session('success') }}</div>
            </div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div id="toast-error" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;display:flex;align-items:center;gap:12px;min-width:320px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="width:40px;height:40px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-exclamation-circle-fill" style="color:#ef4444;font-size:20px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:2px;">Error!</div>
                <div style="color:#64748b;font-size:13px;">{{ session('error') }}</div>
            </div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif
    @if(session('import_errors') && count(session('import_errors')))
        <div id="toast-warning" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;min-width:360px;max-width:450px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="display:flex;align-items:start;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:20px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:6px;">Catatan Import</div>
                    <ul style="margin:0;padding-left:16px;line-height:1.7;color:#64748b;font-size:12px;">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:20px;">
        <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.1);display:flex;align-items:center;gap:16px;">
            <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-person-vcard" style="font-size:24px;color:#2563eb;"></i>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;color:#64748b;letter-spacing:.5px;margin-bottom:4px;">TOTAL AKUN</div>
                <div style="font-size:28px;font-weight:700;color:#1e293b;">{{ number_format($totalAkun) }}</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.1);display:flex;align-items:center;gap:16px;">
            <div style="width:52px;height:52px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-patch-check-fill" style="font-size:24px;color:#16a34a;"></i>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;color:#64748b;letter-spacing:.5px;margin-bottom:4px;">SUDAH APL-01</div>
                <div style="font-size:28px;font-weight:700;color:#1e293b;">{{ number_format($verified) }}</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.1);display:flex;align-items:center;gap:16px;">
            <div style="width:52px;height:52px;border-radius:12px;background:#fff7ed;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-hourglass-split" style="font-size:24px;color:#ea580c;"></i>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;color:#64748b;letter-spacing:.5px;margin-bottom:4px;">BELUM APL-01</div>
                <div style="font-size:28px;font-weight:700;color:#1e293b;">{{ number_format($unverified) }}</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body" style="padding:14px 18px;">
            <form method="GET" action="{{ route('admin.akun-asesi.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <div style="flex:1;min-width:200px;">
                    <div style="position:relative;">
                        <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari NIK atau nama..."
                               style="width:100%;padding:9px 12px 9px 36px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;">
                    </div>
                </div>
                <select name="status" style="padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;min-width:160px;outline:none;">
                    <option value="">Semua Status</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Sudah APL-01</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Belum APL-01</option>
                </select>
                <button type="submit" class="btn btn-primary" style="padding:9px 18px;">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.akun-asesi.index') }}" style="font-size:12px;color:#64748b;text-decoration:underline;">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body" style="padding:0;">
            @if($accounts->isEmpty())
                <div style="padding:60px 20px;text-align:center;color:#64748b;">
                    <div style="width:80px;height:80px;margin:0 auto 20px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-vcard" style="font-size:36px;color:#94a3b8;"></i>
                    </div>
                    <p style="font-size:16px;font-weight:600;margin-bottom:6px;color:#475569;">Belum ada akun asesi</p>
                    <p style="font-size:13px;color:#94a3b8;margin-bottom:20px;">Buat satu per satu atau import massal dari file CSV.</p>
                    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                        <button onclick="openImportModal()" style="padding:10px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;color:#475569;transition:all .2s;"
                                onmouseover="this.style.borderColor='#cbd5e1';this.style.background='#f8fafc'" onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                            <i class="bi bi-file-earmark-arrow-up"></i> Import CSV
                        </button>
                        <button onclick="openCreateModal()" style="padding:10px 20px;background:#2563eb;border:none;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;color:#fff;transition:all .2s;"
                                onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                            <i class="bi bi-plus-circle"></i> Tambah Akun
                        </button>
                    </div>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <th style="width:50px;padding:12px 16px;text-align:center;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">No</th>
                                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">NIK</th>
                                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Nama</th>
                                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Status APL-01</th>
                                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Dibuat</th>
                                <th style="width:140px;padding:12px 16px;text-align:center;font-weight:600;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $index => $account)
                                @php
                                    $asesi = \App\Models\Asesi::where('NIK', $account->NIK)->first();
                                    $statusLabel = 'Belum Mendaftar';
                                    $statusColor = '#f1f5f9';
                                    $statusText  = '#64748b';
                                    $statusIcon  = 'bi-dash-circle';

                                    if ($asesi) {
                                        if ($asesi->status === 'approved') {
                                            $statusLabel = 'Terverifikasi';
                                            $statusColor = '#d1fae5';
                                            $statusText  = '#065f46';
                                            $statusIcon  = 'bi-check-circle-fill';
                                        } elseif ($asesi->status === 'pending') {
                                            $statusLabel = 'Menunggu Verifikasi';
                                            $statusColor = '#fef3c7';
                                            $statusText  = '#92400e';
                                            $statusIcon  = 'bi-clock';
                                        } elseif ($asesi->status === 'rejected') {
                                            $statusLabel = 'Ditolak';
                                            $statusColor = '#fee2e2';
                                            $statusText  = '#991b1b';
                                            $statusIcon  = 'bi-x-circle-fill';
                                        } else {
                                            $statusLabel = 'Sudah Daftar';
                                            $statusColor = '#e0e7ff';
                                            $statusText  = '#3730a3';
                                            $statusIcon  = 'bi-person-check';
                                        }
                                    }
                                @endphp
                                <tr style="border-bottom:1px solid #f1f5f9;transition:background-color .15s;" 
                                    onmouseover="this.style.backgroundColor='#f8fafc'" 
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding:14px 16px;text-align:center;color:#94a3b8;font-size:12px;font-weight:500;">
                                        {{ ($accounts->currentPage() - 1) * $accounts->perPage() + $index + 1 }}
                                    </td>
                                    <td style="padding:14px 16px;">
                                        <span style="font-family:'Courier New',monospace;font-weight:600;font-size:13px;letter-spacing:0.3px;color:#334155;background:#f8fafc;padding:4px 8px;border-radius:4px;display:inline-block;">
                                            {{ $account->NIK }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 16px;font-weight:600;color:#1e293b;">
                                        {{ $account->nama ?? ($asesi->nama ?? '-') }}
                                    </td>
                                    <td style="padding:14px 16px;">
                                        <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $statusColor }};color:{{ $statusText }};">
                                            <i class="bi {{ $statusIcon }}" style="font-size:12px;"></i>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 16px;font-size:12px;color:#64748b;">
                                        {{ $account->created_at?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td style="padding:14px 16px;text-align:center;">
                                        <div style="display:flex;gap:6px;justify-content:center;">
                                            {{-- Reset Password --}}
                                            <form action="{{ route('admin.akun-asesi.reset-password', $account->id) }}" method="POST"
                                                  onsubmit="return confirm('Reset password akun NIK {{ $account->NIK }} ke NIK sebagai password?')" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        style="padding:6px 11px;font-size:12px;background:#dbeafe;color:#1e40af;border:none;border-radius:6px;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:4px;"
                                                        onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'"
                                                        title="Reset Password ke NIK">
                                                    <i class="bi bi-key" style="font-size:13px;"></i>
                                                </button>
                                            </form>

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.akun-asesi.destroy', $account->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus akun NIK {{ $account->NIK }}? Akun akan dihapus permanen.')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        style="padding:6px 11px;font-size:12px;background:#fee2e2;color:#991b1b;border:none;border-radius:6px;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:4px;"
                                                        onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'"
                                                        title="Hapus Akun">
                                                    <i class="bi bi-trash" style="font-size:13px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($accounts->hasPages())
                    <div style="padding:14px 18px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:12px;color:#64748b;">
                            Menampilkan {{ $accounts->firstItem() }}–{{ $accounts->lastItem() }} dari {{ $accounts->total() }} akun
                        </span>
                        {{ $accounts->withQueryString()->links('pagination::tailwind') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- IMPORT MODAL -->
<div id="import-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:520px;
                margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-file-earmark-arrow-up" style="color:#16a34a;"></i> Import Akun Asesi (CSV)
            </h3>
            <button onclick="closeImportModal()" style="background:none;border:none;font-size:20px;
                    color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="import-form" action="{{ route('admin.akun-asesi.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="import-drop" onclick="document.getElementById('import-file').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;
                        cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px;">
                <i class="bi bi-cloud-upload" style="font-size:36px;color:#9ca3af;display:block;margin-bottom:8px;"></i>
                <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Klik atau seret file ke sini</p>
                <p style="font-size:11px;color:#9ca3af;">.csv &bull; Maks 5 MB</p>
                <p id="import-file-label" style="margin-top:8px;font-size:12px;font-weight:600;color:#16a34a;display:none;"></p>
            </div>
            <input type="file" id="import-file" name="file" accept=".csv"
                   style="display:none;" onchange="onImportFileChange(this)" required>

            <div style="background:#f0fdf4;border-left:3px solid #14532d;padding:10px 14px;
                        border-radius:6px;margin-bottom:16px;font-size:12px;color:#14532d;line-height:1.7;">
                <strong>Format kolom:</strong> Kolom A = NIK (16 digit) &bull; Kolom B = Nama<br>
                Password default akun = NIK.
                <a href="{{ route('admin.akun-asesi.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">
                    <i class="bi bi-download"></i> Download template CSV
                </a>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeImportModal()"
                        style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;
                               background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">
                    Batal
                </button>
                <button id="import-submit-btn" type="submit"
                        style="flex:2;padding:10px;border-radius:8px;border:none;
                               background:#14532d;color:white;font-size:14px;font-weight:600;cursor:pointer;
                               display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-upload"></i> Upload &amp; Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CREATE MODAL -->
<div id="create-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:480px;
                margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-person-plus" style="color:#2563eb;"></i> Tambah Akun Asesi
            </h3>
            <button onclick="closeCreateModal()" style="background:none;border:none;font-size:20px;
                    color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form action="{{ route('admin.akun-asesi.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">NIK</label>
                <input type="text" name="NIK" required maxlength="16" minlength="16" pattern="\d{16}"
                       placeholder="Masukkan 16 digit NIK"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;
                              font-family:'Courier New',monospace;letter-spacing:1px;outline:none;">
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Nama Lengkap</label>
                <input type="text" name="nama" required maxlength="255"
                       placeholder="Masukkan nama lengkap"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;">
            </div>

            <div style="background:#eff6ff;border-left:3px solid #2563eb;padding:10px 14px;
                        border-radius:6px;margin-bottom:16px;font-size:12px;color:#1e40af;line-height:1.6;">
                <i class="bi bi-info-circle"></i>
                Password default = NIK. Asesi bisa login menggunakan NIK sebagai username dan password.
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeCreateModal()"
                        style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;
                               background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">
                    Batal
                </button>
                <button type="submit"
                        style="flex:2;padding:10px;border-radius:8px;border:none;
                               background:#2563eb;color:white;font-size:14px;font-weight:600;cursor:pointer;
                               display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-check-lg"></i> Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('import-modal').style.display = 'flex';
}
function closeImportModal() {
    document.getElementById('import-modal').style.display = 'none';
    document.getElementById('import-file').value = '';
    document.getElementById('import-file-label').style.display = 'none';
}
function openCreateModal() {
    document.getElementById('create-modal').style.display = 'flex';
}
function closeCreateModal() {
    document.getElementById('create-modal').style.display = 'none';
}
function onImportFileChange(input) {
    var label = document.getElementById('import-file-label');
    if (input.files.length > 0) {
        label.textContent = input.files[0].name;
        label.style.display = 'block';
    } else {
        label.style.display = 'none';
    }
}

// Close modals on backdrop click
['import-modal', 'create-modal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Import form loading state
document.getElementById('import-form').addEventListener('submit', function() {
    var btn = document.getElementById('import-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
});

// Auto-hide toast notifications after 5 seconds
setTimeout(function() {
    var toasts = document.querySelectorAll('.toast-notification');
    toasts.forEach(function(toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 300);
    });
}, 5000);
</script>

<style>
@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}
</style>
@endsection
