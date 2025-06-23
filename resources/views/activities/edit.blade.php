@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Edit Activity</h2>
    <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="number" name="id_club" value="{{Auth::user()->id_club}}" hidden>
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $activity->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required>{{ $activity->description }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="photos">Photos (max 5)</label>
            <input type="file" id="photos" name="photos[]" class="form-control" multiple>
        </div>
        <div class="mb-3">
            <strong>Current Photos:</strong>
            @foreach (json_decode($activity->photos) as $photo)
                <img src="{{ Storage::url($photo) }}" width="50" height="50" alt="Photo">
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Update Activity</button>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
