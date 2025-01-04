@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Profile User</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Role:</strong> {{ $user->role }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $user->status }}</p>
            <a href="{{ route('profile.user.edit', $user->id) }}" class="btn btn-warning">Edit Profile</a>
        </div>
    </div>
</div>
@endsection