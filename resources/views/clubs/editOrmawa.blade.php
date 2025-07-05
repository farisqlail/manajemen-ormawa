@extends('app')

@section('content')
<div class="container">
    <h1>Edit Ormawa</h1>
    <form action="{{ route('clubs.updateOrmawa', $ormawa->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ $ormawa->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ $ormawa->description }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="logo">Logo <small class="text-danger">MAX: 2MB</small></label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="logo" name="logo" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="logo">Pilih file</label>
                </div>
            </div>
            @if(isset($ormawa->logo))
            <div class="mt-2">
                <h6>Logo Saat Ini:</h6>
                <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo Saat Ini" class="img-fluid" style="max-width: 200px;">
            </div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="photo_structure">Foto Struktur <small class="text-danger">MAX: 2MB</small></label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="photo_structure" name="photo_structure" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="photo_structure">Pilih file</label>
                </div>
            </div>
            @if(isset($ormawa->photo_structure))
            <div class="mt-2">
                <h6>Foto Struktur Saat Ini:</h6>
                <img src="{{ asset('storage/' . $ormawa->photo_structure) }}" alt="Foto Struktur Saat Ini" class="img-fluid" style="max-width: 300px;">
            </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Ubah</button>
        <a href="{{ route('profile') }}" class="btn btn-secondary">Kembali</a>
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
