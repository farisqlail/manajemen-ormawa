@extends('app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center mb-4">Profile Ormawa</h1>
        @if(Auth::user()->role == 'ketua' && Auth::user()->status == 'active')
        <div class="d-flex justify-content-center">
            <a href="{{ route('clubs.editOrmawa', $club->id) }}" class="btn btn-primary btn-lg mr-2">Edit Profile</a>
            <a href="{{ route('clubs.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
        </div>
        @endif
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body text-center">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="display-4"><b>{{ $club->name }}</b></h5>
                @if($club->logo)
                <img src="{{ asset('storage/' . $club->logo) }}" alt="Logo" class="img-fluid rounded-circle" style="max-width: 100px; height: auto;">
                @endif
            </div>

            <p class="card-text lead">{!! $club->description !!}</p>

            @if($club->photo_structure)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $club->photo_structure) }}" alt="Photo Struktur" class="img-fluid rounded" style="max-width: 300px;">
            </div>
            @endif

        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
        /* Warna latar belakang yang lembut */
    }

    .card {
        border-radius: 15px;
        /* Sudut yang lebih halus untuk kartu */
    }

    .card-title {
        color: #007bff;
        /* Warna judul */
    }

    .lead {
        color: #6c757d;
        /* Warna teks deskripsi */
    }
</style>
@endsection