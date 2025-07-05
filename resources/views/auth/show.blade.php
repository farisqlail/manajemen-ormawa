@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Profil Pengguna</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $pengguna->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $pengguna->email }}</p>
            <p class="card-text"><strong>Role:</strong> {{ $pengguna->role }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $pengguna->status }}</p>
            <a href="{{ route('profile.user.edit', $pengguna->id) }}" class="btn btn-warning">Edit Profil</a>
        </div>
    </div>
</div>
@endsection
