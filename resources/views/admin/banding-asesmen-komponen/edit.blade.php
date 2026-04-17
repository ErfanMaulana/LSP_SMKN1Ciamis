@extends('admin.layout')

@section('title', 'Edit Ceklis Banding')
@section('page-title', 'Edit Ceklis Banding')

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
    .error-text { color:#dc2626; font-size:12px; margin-top:6px; }
    .actions { display:flex; gap:10px; margin-top:20px; }
</style>
@endsection

@section('content')
<div class="head">
    <h2>Edit Komponen Ceklis Banding</h2>
    <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="body">
        <form method="POST" action="{{ route('admin.banding-asesmen-komponen.update', $komponen->id) }}">
            @csrf
            @method('PUT')

            <div class="group">
                <label>Pernyataan Ceklis</label>
                <input class="input" type="text" name="pernyataan" value="{{ old('pernyataan', $komponen->pernyataan) }}">
                @error('pernyataan')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="group">
                <label>Urutan</label>
                <input class="input" type="number" min="1" name="urutan" value="{{ old('urutan', $komponen->urutan) }}">
                @error('urutan')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="group">
                <label style="display:flex;align-items:center;gap:8px;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $komponen->is_active) ? 'checked' : '' }}>
                    Komponen aktif
                </label>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-light"><i class="bi bi-x-circle"></i> Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
