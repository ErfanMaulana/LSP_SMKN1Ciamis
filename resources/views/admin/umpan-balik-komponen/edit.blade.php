@extends('admin.layout')

@section('title', 'Edit Komponen Umpan Balik')
@section('page-title', 'Edit Komponen Umpan Balik')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px; }
    .page-header h2 { margin: 0; font-size: 22px; color: #0F172A; }
    .btn { display: inline-flex; align-items: center; gap: 6px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 16px; }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }
    .btn-primary:hover { background: #005f9a; }
    .btn-secondary:hover { background: #475569; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .card-body { padding: 24px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #334155; }
    .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-control:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0, 115, 189, .1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 5px; }
    .form-hint { font-size: 12px; color: #64748b; margin-top: 5px; }
    .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Edit Komponen Umpan Balik</h2>
    <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.umpan-balik-komponen.update', $komponen->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="skema_id">Skema <span class="required">*</span></label>
                <select id="skema_id" name="skema_id" class="form-control @error('skema_id') is-invalid @enderror" required>
                    <option value="">Pilih skema</option>
                    @foreach($skemaList as $skema)
                        <option value="{{ $skema->id }}" {{ old('skema_id', $komponen->skema_id) == $skema->id ? 'selected' : '' }}>
                            {{ $skema->nama_skema }} ({{ $skema->nomor_skema }})
                        </option>
                    @endforeach
                </select>
                @error('skema_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="urutan">Urutan <span class="required">*</span></label>
                <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $komponen->urutan) }}" min="1"
                       class="form-control @error('urutan') is-invalid @enderror">
                @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="pernyataan">Pernyataan Komponen <span class="required">*</span></label>
                <textarea id="pernyataan" name="pernyataan" rows="4"
                          class="form-control @error('pernyataan') is-invalid @enderror"
                          placeholder="Tulis pernyataan komponen umpan balik...">{{ old('pernyataan', $komponen->pernyataan) }}</textarea>
                @error('pernyataan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-hint">Komponen ini akan tampil pada formulir umpan balik asesi ke asesor.</div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $komponen->is_active) ? 'checked' : '' }}>
                    Aktifkan komponen ini
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
