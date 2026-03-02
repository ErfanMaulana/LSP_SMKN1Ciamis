@extends('admin.layout')

@section('title', 'Akun Asesi')
@section('page-title', 'Kelola Akun Asesi (NIK)')

@section('content')
<div class="page-header">
    <h2>Daftar Akun Asesi</h2>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.akun-asesi.import.form') }}" class="btn btn-secondary">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
        </a>
        <a href="{{ route('admin.akun-asesi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Akun
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px;">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

@if(session('import_errors') && count(session('import_errors')))
    <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:8px;padding:14px 18px;margin-bottom:16px;font-size:12px;">
        <strong><i class="bi bi-exclamation-triangle"></i> Catatan Import:</strong>
        <ul style="margin:6px 0 0;padding-left:18px;line-height:1.8;color:#713f12;">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($accounts->isEmpty())
            <div style="padding:40px;text-align:center;color:#64748b;">
                <i class="bi bi-person-vcard" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                <p>Belum ada akun asesi yang dibuat.</p>
                <a href="{{ route('admin.akun-asesi.create') }}" class="btn btn-primary" style="margin-top:12px;">
                    <i class="bi bi-plus-lg"></i> Buat Akun Pertama
                </a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>NIK</th>
                            <th>Status Pendaftaran</th>
                            <th>Nama Asesi</th>
                            <th>Dibuat</th>
                            <th style="width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $index => $account)
                            @php
                                $asesi = \App\Models\Asesi::where('NIK', $account->NIK)->first();
                            @endphp
                            <tr>
                                <td style="text-align:center;">{{ $index + 1 }}</td>
                                <td style="font-family:monospace;font-weight:600;">{{ $account->NIK }}</td>
                                <td>
                                    @if(!$asesi)
                                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#f1f5f9;color:#64748b;">
                                            Belum Daftar
                                        </span>
                                    @elseif($asesi->status === 'pending')
                                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#fef3c7;color:#92400e;">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($asesi->status === 'approved')
                                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#d1fae5;color:#065f46;">
                                            Disetujui
                                        </span>
                                    @elseif($asesi->status === 'rejected')
                                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#fee2e2;color:#991b1b;">
                                            Ditolak
                                        </span>
                                    @else
                                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#f1f5f9;color:#64748b;">
                                            {{ $asesi->status ?? '-' }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $asesi->nama ?? '-' }}</td>
                                <td>{{ $account->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td style="text-align:center;">
                                    @if(!$asesi)
                                        <form action="{{ route('admin.akun-asesi.destroy', $account->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus akun NIK {{ $account->NIK }}?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span style="color:#94a3b8;font-size:11px;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
