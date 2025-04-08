@extends('app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary mb-3">🧾 Profil Ormawa</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('clubs.editOrmawa', $club->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit Profil
            </a>
            <a href="{{ route('clubs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <div class="text-center mb-4">
            @if($club->logo)
            <img src="{{ asset('storage/' . $club->logo) }}" alt="Logo Ormawa" class="rounded-circle shadow-sm mb-3"
                style="width: 120px; height: 120px; object-fit: cover;">
            @endif
            <h3 class="fw-bold">{{ $club->name }}</h3>
        </div>

        <div class="text-center">
            <p class="lead text-muted">{!! $club->description !!}</p>
        </div>

        @if($club->photo_structure)
        <div class="mt-5 text-center">
            <h5 class="fw-semibold text-secondary mb-3">Struktur Organisasi</h5>
            <img src="{{ asset('storage/' . $club->photo_structure) }}" alt="Struktur Organisasi"
                class="img-fluid rounded border shadow-sm" style="max-width: 500px;">
        </div>
        @endif
    </div>
</div>

@endsection