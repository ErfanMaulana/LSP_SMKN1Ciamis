@extends('asesor.layout')

@section('page-title', 'Form Banding Asesmen')

@section('content')
<div class="container py-5">
    <div class="alert alert-info" role="alert">
        <h5 class="alert-heading">Fitur Banding Asesmen</h5>
        <p>Fitur ini masih dalam tahap pengembangan.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Peserta: {{ $asesi->nama ?? $asesi->NIK }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Skema:</strong> {{ $skema->nama_skema ?? 'N/A' }}</p>
            <p>Form banding akan ditampilkan di sini setelah implementasi fitur selesai.</p>
        </div>
    </div>
</div>
@endsection
