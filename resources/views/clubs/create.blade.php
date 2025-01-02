@extends('app')

@section('content')
<div class="container">
    <h1>Add Club</h1>
    <form action="{{ route('clubs.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('clubs.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection