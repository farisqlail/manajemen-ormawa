@extends('app')

@section('content')
<div class="container">
    <h1>Edit Club</h1>
    <form action="{{ route('clubs.update', $club->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $club->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ $club->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ubah</button>
        <a href="{{ route('clubs.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
