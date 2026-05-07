@extends('asesor.layout')

@section('title', 'Persetujuan Asesmen')
@section('page-title', 'Persetujuan Asesmen')

@section('content')
@php $items = collect($items ?? []); @endphp
<div class="page-header">
    <div>
        <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Persetujuan Asesmen</h2>
        <p style="margin:6px 0 0;color:#64748b;">Daftar asesi dan skema yang dapat diproses persetujuan asesmennya.</p>
    </div>
</div>

<div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;overflow:auto;">
    @if($items->isEmpty())
        <div style="padding:18px;color:#64748b;">Belum ada asesi/skema yang terhubung ke akun asesor ini.</div>
    @else
        <table style="width:100%;border-collapse:collapse;min-width:720px;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:12px 10px;">Asesi</th>
                    <th style="padding:12px 10px;">Skema</th>
                    <th style="padding:12px 10px;">Nomor Skema</th>
                    <th style="padding:12px 10px;">Status</th>
                    <th style="padding:12px 10px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px 10px;">{{ $item['asesi_nama'] }}</td>
                        <td style="padding:12px 10px;">{{ $item['skema_nama'] }}</td>
                        <td style="padding:12px 10px;">{{ $item['skema_nomor'] }}</td>
                        <td style="padding:12px 10px;">{{ $item['status'] }}</td>
                        <td style="padding:12px 10px;">
                            <a class="btn btn-primary" href="{{ route('asesor.persetujuan.front.asesor.show', [$item['asesi_nik'], $item['skema_id']]) }}">Buka</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
