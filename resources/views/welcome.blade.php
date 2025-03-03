@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">TahfidzTrack</h1>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk</a>
    </div>
</div>
@endsection
