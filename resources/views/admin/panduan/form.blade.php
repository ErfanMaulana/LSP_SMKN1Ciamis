@php
    $isEdit = !is_null($item);
@endphp

<div class="form-group">
    <label for="title">Judul Poin <span class="required">*</span></label>
    <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $item?->title) }}"
        placeholder="Contoh: Login dan Identifikasi Peran"
        required
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="description">Deskripsi <span class="required">*</span></label>
    <textarea
        id="description"
        name="description"
        rows="5"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Jelaskan langkah panduan ini"
        required
    >{{ old('description', $item?->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-grid">
    <div class="form-group">
        <label for="image">Foto (Opsional)</label>
        <div class="upload-preview-wrap" id="uploadPreviewWrap">
            <img
                id="uploadPreviewImage"
                class="upload-preview-image {{ $isEdit && $item->image ? 'show' : '' }}"
                src="{{ $isEdit && $item->image ? asset('storage/' . $item->image) : '' }}"
                alt="Preview foto panduan"
            >
            <div id="uploadPreviewPlaceholder" class="upload-preview-placeholder {{ $isEdit && $item->image ? 'hide' : '' }}">
                <i class="bi bi-image"></i>
                <span>Preview foto akan tampil di sini</span>
            </div>
        </div>
        <input
            type="file"
            id="image"
            name="image"
            accept="image/jpeg,image/jpg,image/png,image/webp"
            class="form-control file-input @error('image') is-invalid @enderror"
        >
        <small class="help-text">Boleh dikosongkan. Format JPG/PNG/WebP, maksimal 5MB.</small>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if($isEdit && $item->image)
            <div class="current-image-wrap">
                <label class="remove-image-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1" {{ old('remove_image') ? 'checked' : '' }}>
                    Hapus foto saat ini
                </label>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="sort_order">Urutan</label>
        <input
            type="number"
            id="sort_order"
            name="sort_order"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $item?->sort_order ?? 0) }}"
        >
        <small class="help-text">Urutan kecil akan tampil lebih awal di front.</small>
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="toggle-wrapper">
            <label class="toggle">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
            <span class="toggle-label">Aktifkan poin ini</span>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
    </button>
    <a href="{{ route('admin.panduan.index', $section) }}" class="btn btn-secondary">
        <i class="bi bi-x-circle"></i> Batal
    </a>
</div>
