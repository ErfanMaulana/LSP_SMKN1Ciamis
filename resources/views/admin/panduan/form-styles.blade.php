<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        font-size: 22px;
        color: #0f172a;
        font-weight: 700;
    }

    .subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 24px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary {
        background: #0073bd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #003961;
    }

    .btn-secondary {
        background: #64748b;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        padding: 10px 12px;
        min-height: 44px;
        font-size: 14px;
        font-family: inherit;
        color: #0f172a;
        background: #f8fafc;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .ck-editor__editable_inline {
        min-height: 240px;
    }

    .form-control:focus {
        outline: none;
        border-color: #0073bd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.12);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .file-input {
        padding: 9px 12px;
        background: #fff;
    }

    .upload-preview-wrap {
        width: 100%;
        aspect-ratio: 16 / 9;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        margin-bottom: 10px;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .upload-preview-image.show {
        display: block;
    }

    .upload-preview-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 6px;
        text-align: center;
        font-size: 12px;
    }

    .upload-preview-placeholder i {
        font-size: 28px;
    }

    .upload-preview-placeholder.hide {
        display: none;
    }

    .help-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }

    .invalid-feedback {
        margin-top: 5px;
        color: #ef4444;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
    }

    .toggle-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
    }

    .toggle {
        position: relative;
        width: 46px;
        height: 24px;
        display: inline-block;
    }

    .toggle input {
        display: none;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        border-radius: 999px;
        background: #cbd5e1;
        transition: all 0.2s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        left: 3px;
        top: 3px;
        transition: all 0.2s;
    }

    .toggle input:checked + .toggle-slider {
        background: #0073bd;
    }

    .toggle input:checked + .toggle-slider::before {
        transform: translateX(22px);
    }

    .toggle-label {
        font-size: 13px;
        color: #475569;
    }

    .current-image-wrap {
        margin-top: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .remove-image-check {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        color: #475569;
        font-size: 13px;
        font-weight: 500;
    }

    .form-actions {
        border-top: 1px solid #e2e8f0;
        margin-top: 20px;
        padding-top: 18px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }

        .ck-editor__editable_inline {
            min-height: 200px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .page-header h2 {
            font-size: 18px;
        }
    }
</style>
