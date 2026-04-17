@extends('admin.layout')

@section('title', 'Tambah Ceklis Banding')
@section('page-title', 'Tambah Ceklis Banding')

@section('styles')
<style>
    .head { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; gap:10px; }
    .head h2 { margin:0; font-size:22px; color:#0f172a; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-light { background:#e2e8f0; color:#334155; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; }
    .body { padding:20px; }
    .group { margin-bottom:16px; }
    .group label { display:block; margin-bottom:7px; font-size:13px; font-weight:600; color:#334155; }
    .input { width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; font-size:14px; }

    .row-item { display:grid; grid-template-columns:36px 1fr auto; gap:8px; margin-bottom:8px; align-items:center; }
    .idx { width:30px; height:30px; border-radius:6px; background:#f1f5f9; color:#475569; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; }
    .btn-remove { width:30px; height:30px; border:none; border-radius:6px; background:#fee2e2; color:#991b1b; cursor:pointer; }
    .btn-add { border:1px dashed #94a3b8; background:#fff; color:#334155; border-radius:8px; padding:8px 12px; font-size:13px; cursor:pointer; }

    .error-text { color:#dc2626; font-size:12px; margin-top:6px; }
    .actions { display:flex; gap:10px; margin-top:20px; }
</style>
@endsection

@section('content')
<div class="head">
    <h2>Tambah Komponen Ceklis Banding</h2>
    <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="body">
        <form method="POST" action="{{ route('admin.banding-asesmen-komponen.store') }}">
            @csrf

            <div class="group">
                <label>Pernyataan Ceklis</label>
                <div id="list-wrap">
                    @php $oldList = old('pernyataan', ['']); @endphp
                    @foreach($oldList as $idx => $val)
                        <div class="row-item" data-row>
                            <span class="idx" data-idx>{{ $idx + 1 }}</span>
                            <input class="input" type="text" name="pernyataan[]" value="{{ $val }}" placeholder="Masukkan pernyataan ceklis...">
                            <button type="button" class="btn-remove" data-remove {{ count($oldList) <= 1 ? 'style=display:none;' : '' }}><i class="bi bi-trash"></i></button>
                        </div>
                    @endforeach
                </div>
                @error('pernyataan')<div class="error-text">{{ $message }}</div>@enderror
                @error('pernyataan.*')<div class="error-text">{{ $message }}</div>@enderror
                <button type="button" id="add-row" class="btn-add" style="margin-top:6px;"><i class="bi bi-plus-circle"></i> Tambah Baris</button>
            </div>

            <div class="group">
                <label style="display:flex;align-items:center;gap:8px;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    Aktifkan komponen yang ditambahkan
                </label>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-light"><i class="bi bi-x-circle"></i> Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const wrap = document.getElementById('list-wrap');
    const addBtn = document.getElementById('add-row');

    function refresh() {
        const rows = wrap.querySelectorAll('[data-row]');
        rows.forEach((row, i) => {
            const idx = row.querySelector('[data-idx]');
            const removeBtn = row.querySelector('[data-remove]');
            if (idx) idx.textContent = String(i + 1);
            if (removeBtn) removeBtn.style.display = rows.length <= 1 ? 'none' : '';
        });
    }

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'row-item';
        row.setAttribute('data-row', '1');
        row.innerHTML = '<span class="idx" data-idx>1</span><input class="input" type="text" name="pernyataan[]" placeholder="Masukkan pernyataan ceklis..."><button type="button" class="btn-remove" data-remove><i class="bi bi-trash"></i></button>';
        wrap.appendChild(row);
        refresh();
    });

    wrap.addEventListener('click', (event) => {
        const btn = event.target.closest('[data-remove]');
        if (!btn) return;
        const row = btn.closest('[data-row]');
        if (row) row.remove();
        refresh();
    });

    refresh();
})();
</script>
@endsection
