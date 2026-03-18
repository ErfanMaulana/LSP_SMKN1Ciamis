@extends('admin.layout')

@section('title', 'Edit Poin Panduan')
@section('page-title', 'Panduan')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Poin - {{ $sectionMeta['title'] }}</h2>
        <p class="subtitle">Perbarui konten panduan sesuai kebutuhan.</p>
    </div>
    <a href="{{ route('admin.panduan.index', $section) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.panduan.update', [$section, $item->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.panduan.form', ['item' => $item])
        </form>
    </div>
</div>
@endsection

@section('styles')
@include('admin.panduan.form-styles')
@endsection

@section('scripts')
@include('admin.panduan.form-scripts')
@endsection
