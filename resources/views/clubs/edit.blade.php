@extends('app')

@section('content')
<div class="container">
    <h1>Edit Club</h1>
    <form action="{{ route('clubs.update', $club->id) }}" method="POST" enctype="multipart/form-data">
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
        <div class="form-group mb-3">
            <label for="logo">Logo</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="logo" name="logo" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="logo">Choose file</label>
                </div>
            </div>
            @if(isset($club->logo))
            <div class="mt-2">
                <h6>Current Logo:</h6>
                <img src="{{ asset('storage/' . $club->logo) }}" alt="Current Logo" class="img-fluid" style="max-width: 200px;">
            </div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="photo_structure">Photo Struktur</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="photo_structure" name="photo_structure" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="photo_structure">Choose file</label>
                </div>
            </div>
            @if(isset($club->photo_structure))
            <div class="mt-2">
                <h6>Current Photo Struktur:</h6>
                <img src="{{ asset('storage/' . $club->photo_structure) }}" alt="Current Photo Struktur" class="img-fluid" style="max-width: 300px;">
            </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Ubah</button>
        <a href="{{ route('clubs.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endsection