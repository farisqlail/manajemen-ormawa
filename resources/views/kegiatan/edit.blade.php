@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Edit Kegiatan</h2>
    <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if(Auth::user()->role === 'superadmin')
        <div class="form-group mb-3">
            <label for="id_club">Ormawa</label>
            <select name="id_club" id="id_club" class="form-control" required>
                <option value="">-- Pilih Ormawa --</option>
                @foreach($clubs as $club)
                <option value="{{ $club->id }}" {{ $activity->id_club == $club->id ? 'selected' : '' }}>
                    {{ $club->name }}
                </option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
        @endif

        <div class="form-group mb-3">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ $activity->name }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" required>{{ $activity->description }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="foto">Foto Kegiatan (maks 5) <small class="text-danger">MAX: 2MB</small></label>
            <input type="file" id="foto" name="foto[]" class="form-control" multiple>
        </div>

        <div class="mb-3">
            <strong>Foto Saat Ini:</strong><br>
            @foreach (json_decode($activity->photos) as $photo)
            <img src="{{ Storage::url($photo) }}" width="50" height="50" alt="Photo">
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Kegiatan</button>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection