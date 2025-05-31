@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Edit Pengguna</h2>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required oninput="setPassword()" placeholder="cnth: agus ...">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required placeholder="cnth: agus@gmail.com">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Leave blank to keep current password">
                </div>
                <div class="form-group mb-3">
                    <label for="id_club">Ormawa</label>
                    <select id="id_club" name="id_club" class="form-control" required>
                        @foreach ($clubs as $club)
                        <option value="{{ $club->id }}" {{ $user->id_club == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="id_division">Division</label>
                    <select id="id_division" name="id_division" class="form-control" required>
                        @foreach ($divisions as $division)
                        <option value="{{ $division->id }}" {{ $user->id_division == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="ormawa" {{ $user->role == 'ormawa' ? 'selected' : '' }}>Ormawa</option>
                    </select>
                </div>
                <input type="hidden" id="status" name="status" value="active">
                <div class="form-group mb-3">
                    <label>Status</label>
                    <input type="text" class="form-control" value="Active" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Edit Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    function setPassword() {
        const nameInput = document.getElementById('name');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const nameValue = nameInput.value.trim();

        if (nameValue) {
            passwordInput.value = nameValue + '123';
            passwordConfirmationInput.value = nameValue + '123';
        } else {
            passwordInput.value = '';
            passwordConfirmationInput.value = '';
        }
    }
</script>
@endsection