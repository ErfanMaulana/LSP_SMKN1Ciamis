@extends('asesi.layout')

@section('page-title', 'Banding Asesmen')

@section('content')
<div class="container py-5">
    <div class="alert alert-info" role="alert">
        <h5 class="alert-heading">Fitur Banding Asesmen</h5>
        <p>Fitur ini masih dalam tahap pengembangan. Silakan menunggu update berikutnya untuk dapat mengajukan banding asesmen.</p>
    </div>

    @if(!$bandings || $bandings->isEmpty())
        <div class="alert alert-warning" role="alert">
            Belum ada data banding asesmen saat ini.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Skema</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Table content will be populated after feature implementation --}}
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
