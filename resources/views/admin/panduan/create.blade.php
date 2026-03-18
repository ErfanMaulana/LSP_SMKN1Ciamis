@extends('admin.layout')

@section('title', 'Tambah Poin Panduan')
@section('page-title', 'Panduan')

@section('content')
<div class="page-header">
    <div>
        <h2>Tambah Poin - {{ $sectionMeta['title'] }}</h2>
        <p class="subtitle">Tambahkan langkah baru untuk ditampilkan di halaman front.</p>
    </div>
    <a href="{{ route('admin.panduan.index', $section) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.panduan.store', $section) }}" enctype="multipart/form-data">
            @csrf
            @include('admin.panduan.form', ['item' => null])
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
