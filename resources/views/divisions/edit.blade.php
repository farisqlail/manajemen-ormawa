@extends('app')

@section('content')
<div class="container">
    <h1>Edit Divisi</h1>
    <form action="{{ route('divisions.update', $division->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Nama Divisi</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $division->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Ubah</button>
    </form>
</div>
@endsection