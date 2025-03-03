@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Program Hafalan</h2>
    
    <form action="{{ route('program.update', $program->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="program_name" class="form-label">Nama Program</label>
            <input type="text" class="form-control @error('program_name') is-invalid @enderror" id="program_name" name="program_name" value="{{ old('program_name', $program->program_name) }}" required>
            @error('program_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="surah" class="form-label">Surat Al-Qur'an</label>
            <input type="text" class="form-control @error('surah') is-invalid @enderror" id="surah" name="surah" 
                value="{{ old('surah', is_array($program->surah) ? implode(', ', $program->surah) : $program->surah) }}" required>
            <small class="form-text text-muted">Masukkan nama surat atau beberapa surat dipisahkan koma (misalnya: Al-Fatihah, Al-Baqarah)</small>
            @error('surah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Tenggat Waktu (Bulan)</label>
            <input type="number" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline', $program->deadline) }}" min="1" required>
            <small class="form-text text-muted">Jumlah bulan dari sekarang untuk menyelesaikan program</small>
            @error('deadline')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Program</button>
        <a href="{{ route('program.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection