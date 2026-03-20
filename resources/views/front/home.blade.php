@extends('front.layout.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')

@include('front.sections.hero')
@include('front.sections.map')
@include('front.sections.stats')
@include('front.sections.profile-content')

@endsection
