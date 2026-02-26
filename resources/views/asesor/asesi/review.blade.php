@extends('asesor.layout')

@section('title', 'Review Asesmen - ' . $asesi->nama)
@section('page-title', 'Review Asesmen Mandiri')

@section('styles')
<style>
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: #2563eb; text-decoration: none; font-size: 14px;
        font-weight: 500; margin-bottom: 18px;
    }
    .back-btn:hover { color: #1d4ed8; }

    .header-card {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 14px; padding: 24px 28px; color: white; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;
        flex-wrap: wrap;
    }
    .header-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .header-card .meta { font-size: 13px; opacity: 0.85; }
    .header-card .meta span { margin-right: 16px; }

    .summary-row {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(150px,1fr));
        gap: 14px; margin-bottom: 24px;
    }
    .summary-card {
        background: white; border-radius: 10px; padding: 16px;
        text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .summary-card .num { font-size: 28px; font-weight: 700; }
    .summary-card .lbl { font-size: 12px; color: #64748b; font-weight: 500; }
    .k-color  { color: #059669; }
    .bk-color { color: #dc2626; }

    .print-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: white; color: #1e3a5f;
        border: 1.5px solid #d1d5db; padding: 8px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none;
        transition: all 0.2s;
    }
    .print-btn:hover { background: #f8fafc; color: #1e3a5f; }

    .unit-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; margin-bottom: 20px; overflow: hidden;
    }
    .unit-header {
        background: #f8fafc; padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 12px;
    }
    .unit-number {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; flex-shrink: 0;
    }
    .unit-header h3 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
    .unit-header small { font-size: 12px; color: #64748b; font-family: monospace; }

    .elemen-row {
        padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
    }
    .elemen-row:last-child { border-bottom: none; }

    .elemen-top {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: 12px; margin-bottom: 10px;
    }
    .elemen-name {
        font-size: 14px; font-weight: 600; color: #1e293b;
    }
    .elemen-label { font-size: 11px; color: #64748b; font-weight: 500; margin-bottom: 2px; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;
        flex-shrink: 0;
    }
    .status-K  { background: #d1fae5; color: #059669; }
    .status-BK { background: #fee2e2; color: #dc2626; }
    .status-na { background: #f1f5f9; color: #94a3b8; }

    .kriteria-list {
        list-style: none; padding: 0; margin: 0 0 10px;
    }
    .kriteria-list li {
        display: flex; gap: 8px; font-size: 12px; color: #64748b;
        padding: 3px 0;
    }
    .kriteria-list li::before {
        content: counter(krit);
        counter-increment: krit;
        background: #dbeafe; color: #2563eb;
        width: 18px; height: 18px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
    }
    .kriteria-list { counter-reset: krit; }

    .bukti-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 10px 14px; font-size: 13px; color: #475569;
        display: flex; gap: 8px; align-items: flex-start;
    }
    .bukti-box i { color: #3b82f6; margin-top: 2px; flex-shrink: 0; }
    .bukti-empty { color: #94a3b8; font-style: italic; }

    @media print {
        .back-btn, .print-btn, aside, .topbar { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .unit-card { break-inside: avoid; }
    }
</style>
@endsection

@section('content')

<a href="{{ route('asesor.asesi.index') }}" class="back-btn">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Asesi
</a>

{{-- Header Info --}}
<div class="header-card">
    <div>
        <h2><i class="bi bi-person-lines-fill"></i> {{ $asesi->nama }}</h2>
        <div class="meta">
            <span><i class="bi bi-credit-card"></i> NIK: {{ $asesi->NIK }}</span>
            <span><i class="bi bi-hash"></i> No. Reg: {{ $asesi->no_reg ?? '—' }}</span>
            <span><i class="bi bi-mortarboard"></i> {{ $asesi->jurusan?->nama_jurusan ?? '—' }}</span>
        </div>
        <div class="meta" style="margin-top:6px;">
            <span><i class="bi bi-award"></i> {{ $skema->nama_skema }}</span>
        </div>
        <div class="meta" style="margin-top:4px; opacity:0.75; font-size:12px;">
            <span>Mulai: {{ $pivot->tanggal_mulai ? \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d/m/Y H:i') : '—' }}</span>
            <span>Selesai: {{ $pivot->tanggal_selesai ? \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
    <a href="javascript:window.print()" class="print-btn" style="color:#1e3a5f;background:white;">
        <i class="bi bi-printer"></i> Cetak
    </a>
</div>

{{-- Summary --}}
<div class="summary-row">
    <div class="summary-card">
        <div class="num" style="color:#1e3a5f;">{{ $skema->units->count() }}</div>
        <div class="lbl">Unit Kompetensi</div>
    </div>
    <div class="summary-card">
        <div class="num" style="color:#2563eb;">{{ $answers->count() }}</div>
        <div class="lbl">Elemen Dijawab</div>
    </div>
    <div class="summary-card">
        <div class="num k-color">{{ $kCount }}</div>
        <div class="lbl">Kompeten (K)</div>
    </div>
    <div class="summary-card">
        <div class="num bk-color">{{ $bkCount }}</div>
        <div class="lbl">Belum Kompeten (BK)</div>
    </div>
    <div class="summary-card">
        @php $pct = $answers->count() > 0 ? round($kCount / $answers->count() * 100) : 0; @endphp
        <div class="num" style="color:{{ $pct >= 70 ? '#059669' : '#d97706' }};">{{ $pct }}%</div>
        <div class="lbl">Tingkat Kompeten</div>
    </div>
</div>

{{-- Per-Unit Detail --}}
@foreach($skema->units as $unitIdx => $unit)
<div class="unit-card">
    <div class="unit-header">
        <div class="unit-number">{{ $unitIdx + 1 }}</div>
        <div>
            <h3>{{ $unit->judul_unit }}</h3>
            <small>{{ $unit->kode_unit }}</small>
        </div>
    </div>

    @foreach($unit->elemens as $elIdx => $elemen)
    @php $jawaban = $answers->get($elemen->id); @endphp
    <div class="elemen-row">
        <div class="elemen-top">
            <div>
                <div class="elemen-label">Elemen {{ $elIdx + 1 }}</div>
                <div class="elemen-name">{{ $elemen->nama_elemen }}</div>
            </div>
            @if($jawaban)
                <span class="status-badge status-{{ $jawaban->status }}">
                    {{ $jawaban->status === 'K' ? '✓ Kompeten' : '✗ Belum Kompeten' }}
                </span>
            @else
                <span class="status-badge status-na">— Belum Dijawab</span>
            @endif
        </div>

        {{-- Kriteria --}}
        @if($elemen->kriteria->count())
        <ul class="kriteria-list">
            @foreach($elemen->kriteria as $k)
            <li>{{ $k->deskripsi_kriteria }}</li>
            @endforeach
        </ul>
        @endif

        {{-- Bukti --}}
        <div class="bukti-box">
            <i class="bi bi-file-earmark-text"></i>
            @if($jawaban?->bukti)
                <span>{{ $jawaban->bukti }}</span>
            @else
                <span class="bukti-empty">Tidak ada bukti yang diisikan.</span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endforeach

{{-- ═══════════════════════════════════════════════════════
     FR.APL.02 — Rekomendasi Asesor
════════════════════════════════════════════════════════ --}}
<div class="unit-card" style="margin-top:28px;" id="rekomendasi-section">
    <div class="unit-header" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);color:white;">
        <div class="unit-number" style="background:rgba(255,255,255,0.2);">
            <i class="bi bi-patch-check-fill" style="font-size:16px;"></i>
        </div>
        <div>
            <h3 style="color:white;margin:0;">Rekomendasi Asesor <span style="font-size:12px;font-weight:400;opacity:0.8;">(FR.APL.02)</span></h3>
            <small style="color:rgba(255,255,255,0.7);">Tentukan apakah asesi dapat melanjutkan ke tahap asesmen berikutnya</small>
        </div>
    </div>

    @if($pivot->rekomendasi)
    <div style="padding:16px 22px;background:{{ $pivot->rekomendasi === 'lanjut' ? '#d1fae5' : '#fee2e2' }};border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:12px;">
        <i class="bi bi-{{ $pivot->rekomendasi === 'lanjut' ? 'check-circle-fill' : 'x-circle-fill' }}"
           style="font-size:22px;color:{{ $pivot->rekomendasi === 'lanjut' ? '#059669' : '#dc2626' }};"></i>
        <div>
            <div style="font-weight:700;font-size:15px;color:{{ $pivot->rekomendasi === 'lanjut' ? '#065f46' : '#991b1b' }};">
                Asesmen {{ $pivot->rekomendasi === 'lanjut' ? 'dapat' : 'tidak dapat' }} dilanjutkan
            </div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">
                Ditinjau pada {{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                &bull; oleh <strong>{{ $pivot->reviewed_by ?? '-' }}</strong>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('asesor.asesi.recommend', $asesi->NIK) }}" style="padding:22px 24px;">
        @csrf

        {{-- Tabel Rekomendasi — format FR.APL.02 --}}
        <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
            <tr>
                <td style="width:42%;border:1px solid #d1d5db;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                    <strong>Rekomendasi Untuk Asesi:</strong>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">Asesmen dapat / tidak dapat dilanjutkan</div>
                </td>
                <td style="border:1px solid #d1d5db;padding:14px 20px;">
                    <label id="label-lanjut" onclick="selectRekom('lanjut')"
                           style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border-radius:8px;border:2px solid #e2e8f0;margin-bottom:10px;transition:all 0.2s;">
                        <input type="radio" name="rekomendasi" value="lanjut"
                               {{ old('rekomendasi', $pivot->rekomendasi) === 'lanjut' ? 'checked' : '' }}
                               style="accent-color:#059669;width:16px;height:16px;">
                        <span style="font-weight:600;color:#065f46;">✓ &nbsp;Asesmen <u>dapat</u> dilanjutkan</span>
                    </label>
                    <label id="label-tidak" onclick="selectRekom('tidak_lanjut')"
                           style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border-radius:8px;border:2px solid #e2e8f0;transition:all 0.2s;">
                        <input type="radio" name="rekomendasi" value="tidak_lanjut"
                               {{ old('rekomendasi', $pivot->rekomendasi) === 'tidak_lanjut' ? 'checked' : '' }}
                               style="accent-color:#dc2626;width:16px;height:16px;">
                        <span style="font-weight:600;color:#991b1b;">✗ &nbsp;Asesmen <u>tidak dapat</u> dilanjutkan</span>
                    </label>
                    @error('rekomendasi')
                        <div style="color:#dc2626;font-size:12px;margin-top:6px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #d1d5db;border-top:none;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                    <strong>Catatan Asesor:</strong>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">Opsional</div>
                </td>
                <td style="border:1px solid #d1d5db;border-top:none;padding:12px 14px;">
                    <textarea name="catatan_asesor" rows="3"
                              placeholder="Tuliskan catatan atau alasan rekomendasi (opsional)..."
                              style="width:100%;border:1px solid #e2e8f0;border-radius:6px;padding:8px 12px;font-size:13px;resize:vertical;font-family:inherit;color:#374151;">{{ old('catatan_asesor', $pivot->catatan_asesor) }}</textarea>
                </td>
            </tr>
        </table>

        {{-- Tabel Tanda Tangan — format FR.APL.02 --}}
        <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:22px;">
            <tr>
                <td style="width:50%;border:1px solid #d1d5db;padding:0;vertical-align:top;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td colspan="2" style="padding:9px 14px;background:#f8fafc;font-weight:700;border-bottom:1px solid #d1d5db;">Asesi :</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;width:42%;border-bottom:1px solid #eff0f1;color:#64748b;">Nama</td>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;">{{ $asesi->nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;color:#64748b;vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                            <td style="padding:9px 14px;height:50px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;border:1px solid #d1d5db;border-left:none;padding:0;vertical-align:top;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td colspan="2" style="padding:9px 14px;background:#f8fafc;font-weight:700;border-bottom:1px solid #d1d5db;">Ditinjau Oleh Asesor :</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;width:38%;border-bottom:1px solid #eff0f1;color:#64748b;">Nama :</td>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;">{{ $asesor->nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;color:#64748b;">No. Reg:</td>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;font-family:monospace;">{{ $account->no_reg }}</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;color:#64748b;vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                            <td style="padding:9px 14px;height:50px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('asesor.asesi.index') }}"
               style="padding:10px 20px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;color:#475569;font-size:14px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit"
                    style="padding:10px 26px;border-radius:8px;border:none;background:linear-gradient(135deg,#1e3a5f,#2563eb);color:white;font-size:14px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                <i class="bi bi-send-check-fill"></i>
                {{ $pivot->rekomendasi ? 'Perbarui Rekomendasi' : 'Simpan Rekomendasi' }}
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
function selectRekom(val) {
    const lanjut = document.getElementById('label-lanjut');
    const tidak  = document.getElementById('label-tidak');
    lanjut.style.borderColor = val === 'lanjut'       ? '#059669' : '#e2e8f0';
    lanjut.style.background  = val === 'lanjut'       ? '#f0fdf4' : 'white';
    tidak.style.borderColor  = val === 'tidak_lanjut' ? '#dc2626' : '#e2e8f0';
    tidak.style.background   = val === 'tidak_lanjut' ? '#fff1f2' : 'white';
}
document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="rekomendasi"]:checked');
    if (checked) selectRekom(checked.value);
});
</script>
@endsection
