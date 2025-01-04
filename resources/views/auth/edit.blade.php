@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Edit Profile</h2>
    <form action="{{ route('profile.user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="{{ route('profile.user.show') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection