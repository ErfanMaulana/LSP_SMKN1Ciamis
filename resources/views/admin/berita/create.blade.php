@extends('admin.layout')

@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label.required::after {
        content: " *";
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0,115,189,.1);
    }

    .form-input.error {
        border-color: #dc2626;
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-hint {
        color: #6b7280;
        font-size: 12px;
        margin-top: 4px;
    }

    .image-preview {
        margin-top: 12px;
        display: none;
    }

    .image-preview img {
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-primary {
        background: #0073bd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #003961;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Tambah Berita</h2>
        <p>Buat berita atau artikel baru</p>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label required">Judul Berita</label>
            <input type="text" name="judul" class="form-input @error('judul') error @enderror" 
                   value="{{ old('judul') }}" placeholder="Masukkan judul berita">
            @error('judul')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Konten Berita</label>
            <textarea name="konten" id="konten" class="form-input @error('konten') error @enderror">{{ old('konten') }}</textarea>
            @error('konten')
                <div class="form-error">{{ $message }}</div>
            @enderror
            <div class="form-hint">Gunakan editor untuk memformat konten berita</div>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Berita</label>
            <input type="file" name="gambar" id="gambar" class="form-input @error('gambar') error @enderror" 
                   accept="image/jpeg,image/png,image/jpg,image/gif">
            @error('gambar')
                <div class="form-error">{{ $message }}</div>
            @enderror
            <div class="form-hint">Format: JPG, PNG, GIF. Maksimal 5MB</div>
            <div class="image-preview" id="imagePreview">
                <img src="" alt="Preview">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label required">Penulis</label>
            <input type="text" name="penulis" class="form-input @error('penulis') error @enderror" 
                   value="{{ old('penulis', auth('admin')->user()->name ?? '') }}" placeholder="Nama penulis">
            @error('penulis')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Tanggal Publikasi</label>
            <input type="date" name="tanggal_publikasi" class="form-input @error('tanggal_publikasi') error @enderror" 
                   value="{{ old('tanggal_publikasi', date('Y-m-d')) }}">
            @error('tanggal_publikasi')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Status</label>
            <select name="status" class="form-input @error('status') error @enderror">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Berita
            </button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    let editor;
    ClassicEditor
        .create(document.querySelector('#konten'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'uploadImage', 'blockQuote', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'insertTable', '|',
                    'undo', 'redo', '|',
                    'sourceEditing'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(newEditor => {
            editor = newEditor;
            // Set minimum height for editing area
            const editingView = editor.editing.view;
            editingView.change(writer => {
                writer.setStyle('min-height', '500px', editingView.document.getRoot());
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Image preview
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                preview.querySelector('img').src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
