@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dashboard Admin</h2>
    <div>
        <a href="{{ route('program.create') }}" class="btn btn-primary">Tambah Program</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>

@if(session()->has('username'))
    <p>Halo, <strong>{{ session('username') }}</strong>! Selamat datang di Dashboard.</p>
@else
    <script>window.location.href = "{{ route('login') }}";</script>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Program</th>
            <th>Surat</th>
            <th>Tenggat Waktu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($programs as $key => $program)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $program->program_name }}</td>
            <td>
                @if(is_array($program->surah))
                    {{ implode(', ', $program->surah) }}
                @else
                    {{ $program->surah }}
                @endif
            </td>
            <td>{{ $program->deadline }} bulan</td>
            <td>
                <a href="{{ route('program.show', $program->id) }}" class="btn btn-info btn-sm">Detail</a>
                <a href="{{ route('program.edit', $program->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('program.destroy', $program->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Belum ada program yang ditambahkan</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
