@extends('app')

@section('content')
<div class="container">
    <h1>Tambah Divisi</h1>
    <form action="{{ route('divisions.store', ['id_club' => $club->id]) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Division Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>

</div>
@endsection