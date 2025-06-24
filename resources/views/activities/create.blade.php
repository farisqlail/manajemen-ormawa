@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Create Activity</h2>
    <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="number" name="id_club" value="{{Auth::user()->id_club}}" hidden>
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="photos">Photos (max 5) <small class="text-danger">MAX: 2MB</small></label>
            <input type="file" id="photos" name="photos[]" class="form-control" multiple required>
        </div>
        <button type="submit" class="btn btn-primary">Create Activity</button>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
