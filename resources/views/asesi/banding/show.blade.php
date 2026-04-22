@extends('asesi.layout')

@section('page-title', 'Detail Banding Asesmen')

@section('content')
<div class="container py-5">
    <div class="alert alert-info" role="alert">
        <h5 class="alert-heading">Fitur Banding Asesmen</h5>
        <p>Fitur ini masih dalam tahap pengembangan dan akan segera tersedia.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>{{ $skema->nama_skema ?? 'Skema' }}</h5>
        </div>
        <div class="card-body">
            <p>Detail banding akan ditampilkan di sini setelah implementasi fitur selesai.</p>
        </div>
    </div>
</div>
@endsection
