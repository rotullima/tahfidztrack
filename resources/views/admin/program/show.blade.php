@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Program Hafalan</h2>
    
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $program->program_name }}</h4>
            <p><strong>Surah:</strong> 
                @if(is_array($program->surah))
                    {{ implode(', ', $program->surah) }}
                @else
                    {{ $program->surah }}
                @endif
            </p>
            <p><strong>Deadline:</strong> {{ $program->deadline }} bulan</p>
            
            @if($program->deadline_date)
            <p><strong>Tanggal Deadline:</strong> {{ \Carbon\Carbon::parse($program->deadline_date)->format('d F Y') }}</p>
            @endif
            
            <a href="{{ route('program.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection