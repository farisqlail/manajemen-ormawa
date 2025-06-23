@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Tambah Pengguna</h2>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" required oninput="setPassword()" placeholder="cnth: agus ...">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required onchange="toggleDivision()">
                        <option value="admin">Admin</option>
                        <option value="ormawa">Ormawa</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="cnth: agus@gmail.com ...">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required readonly placeholder="Auto-generated password">
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required readonly placeholder="Auto-generated password">
                </div>
                <div class="form-group mb-3">
                    <label for="id_club">Club</label>
                    <select id="id_club" name="id_club" class="form-control" required>
                        @foreach ($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3" id="">
                    <label for="">PIC</label>
                    <input type="text" value="Ketua" class="form-control" readonly>
                </div>
                <input type="hidden" id="status" name="status" value="nonactive">
                <div class="form-group mb-3">
                    <label>Status</label>
                    <input type="text" class="form-control" value="Non Active" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Buat Pengguna</button>
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

    function toggleDivision() {
        const divisionGroup = document.getElementById('division-group');
        const division = document.getElementById('id_division');

        divisionGroup.style.display = 'block';
        division.setAttribute('required', 'required');
    }

    document.addEventListener("DOMContentLoaded", toggleDivision);
</script>
@endsection