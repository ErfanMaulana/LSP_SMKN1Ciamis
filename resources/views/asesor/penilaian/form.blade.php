@extends('asesor.layout')

@section('title', 'Input Nilai Asesi')
@section('page-title', 'Entry Penilaian')

@section('styles')
<style>
    .top-info {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        color: white;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }
    .top-info h3 { margin: 0 0 6px; font-size: 18px; }
    .top-info .meta { font-size: 13px; opacity: .92; }
    .top-info .meta span { margin-right: 14px; }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        background: white;
        color: #1e3a5f;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .panel-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .panel-head {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .panel-head h4 {
        margin: 0;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
    }
    .search-box {
        border: 1px solid #d1d5db;
        border-radius: 7px;
        padding: 7px 10px;
        font-size: 13px;
        min-width: 240px;
    }

    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 860px; }
    thead th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: .4px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 12px;
    }
    tbody td {
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        padding: 9px 12px;
        vertical-align: middle;
    }
    .nilai-input {
        width: 86px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        padding: 6px 8px;
        font-size: 13px;
        text-align: center;
    }
    .nilai-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
    .status-badge {
        display: inline-flex;
        min-width: 44px;
        justify-content: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-k { background: #d1fae5; color: #065f46; }
    .status-bk { background: #fee2e2; color: #991b1b; }

    .footer-bar {
        padding: 14px 16px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        border-top: 1px solid #e2e8f0;
    }
    .summary-chip {
        display: inline-flex;
        padding: 6px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        margin-right: 8px;
    }
    .btn-save {
        background: #0073bd;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save:hover { background: #005e9b; }

    @media (max-width: 768px) {
        .top-info {
            padding: 14px;
            margin-bottom: 14px;
        }

        .top-info h3 {
            font-size: 16px;
        }

        .top-info .meta {
            display: grid;
            gap: 3px;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .panel-head {
            padding: 12px 14px;
        }

        .search-box {
            min-width: 0;
            width: 100%;
        }

        .table-wrap {
            -webkit-overflow-scrolling: touch;
        }

        .footer-bar {
            padding: 12px 14px;
        }

        .summary-chip {
            display: inline-flex;
            margin-right: 0;
            margin-bottom: 6px;
        }

        .btn-save {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="top-info">
    <div>
        <h3><i class="bi bi-pencil-square"></i> Input Nilai Asesi</h3>
        <div class="meta">
            <span><i class="bi bi-person"></i> {{ $asesi->nama }}</span>
            <span><i class="bi bi-credit-card"></i> {{ $asesi->NIK }}</span>
            <span><i class="bi bi-award"></i> {{ $skema->nama_skema }}</span>
        </div>
    </div>
    <a href="{{ route('asesor.entry-penilaian') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Nilai
    </a>
</div>

<form method="POST" action="{{ route('asesor.entry-penilaian.store', $asesi->NIK) }}">
    @csrf
    <div class="panel-card">
        <div class="panel-head">
            <h4>Nilai Per Elemen (0 - 100)</h4>
            <input type="text" id="search-elemen" class="search-box" placeholder="Cari unit/elemen...">
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:170px;">ID Unit</th>
                        <th style="width:120px;">ID Elemen</th>
                        <th>Judul Elemen</th>
                        <th style="width:110px;">Nilai</th>
                        <th style="width:90px;">K/BK</th>
                    </tr>
                </thead>
                <tbody id="nilai-elemen-tbody">
                    @php
                        $rowNo = 1;
                        $sumNilai = 0;
                        $countNilai = 0;
                    @endphp
                    @foreach($skema->units as $unit)
                        @foreach($unit->elemens as $index => $elemen)
                            @php
                                $nilaiDefault = old('nilai.' . $elemen->id, $existingNilai[$elemen->id]->nilai ?? '');
                                $nilaiAngka = is_numeric($nilaiDefault) ? (float) $nilaiDefault : 0;
                                $status = $nilaiAngka >= 75 ? 'K' : 'BK';
                                if ($nilaiDefault !== '') { $sumNilai += $nilaiAngka; $countNilai++; }
                            @endphp
                            <tr class="elemen-row" data-search="{{ strtolower($unit->kode_unit . ' ' . $elemen->nama_elemen) }}">
                                <td>{{ $rowNo++ }}</td>
                                <td>{{ $unit->kode_unit }}</td>
                                <td>EL.{{ str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $elemen->nama_elemen }}</td>
                                <td>
                                    <input
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="1"
                                        class="nilai-input"
                                        name="nilai[{{ $elemen->id }}]"
                                        value="{{ $nilaiDefault }}"
                                        required>
                                </td>
                                <td>
                                    <span class="status-badge {{ $status === 'K' ? 'status-k' : 'status-bk' }} status-live">{{ $status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $avgNilai = $countNilai > 0 ? round($sumNilai / $countNilai, 2) : 0;
        @endphp
        <div class="footer-bar">
            <div>
                <span class="summary-chip">Total Elemen: {{ $rowNo - 1 }}</span>
                <span class="summary-chip">Rata-rata saat ini: <span id="avg-nilai">{{ $avgNilai }}</span></span>
            </div>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Nilai
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
(function () {
    function refreshStatusAndAverage() {
        var inputs = document.querySelectorAll('.nilai-input');
        var sum = 0;
        var count = 0;

        inputs.forEach(function (input) {
            var row = input.closest('tr');
            var badge = row ? row.querySelector('.status-live') : null;
            var val = parseFloat(input.value);
            if (!isNaN(val)) {
                count += 1;
                sum += val;
            }

            var isK = !isNaN(val) && val >= 75;
            if (badge) {
                badge.textContent = isK ? 'K' : 'BK';
                badge.classList.toggle('status-k', isK);
                badge.classList.toggle('status-bk', !isK);
            }
        });

        var avg = count ? (sum / count).toFixed(2) : '0.00';
        var avgEl = document.getElementById('avg-nilai');
        if (avgEl) avgEl.textContent = avg;
    }

    document.querySelectorAll('.nilai-input').forEach(function (input) {
        input.addEventListener('input', refreshStatusAndAverage);
    });

    var searchInput = document.getElementById('search-elemen');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var key = (this.value || '').toLowerCase().trim();
            document.querySelectorAll('.elemen-row').forEach(function (row) {
                var hay = row.getAttribute('data-search') || '';
                row.style.display = (!key || hay.indexOf(key) !== -1) ? '' : 'none';
            });
        });
    }

    refreshStatusAndAverage();
}());
</script>
@endsection
