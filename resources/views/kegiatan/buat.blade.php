@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Tambah Kegiatan</h2>
    <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(Auth::user()->role === 'superadmin')
        <div class="form-group mb-3">
            <label for="id_club">Ormawa</label>
            <select name="id_club" id="id_club" class="form-control" required>
                <option value="">-- Pilih Ormawa --</option>
                @foreach($clubs as $club)
                <option value="{{ $club->id }}">{{ $club->name }}</option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
        @endif

        <div class="form-group mb-3">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="foto">Foto Kegiatan (maksimal 5) <small class="text-danger">MAX: 2MB per file</small></label>
            <input type="file" id="foto" name="foto[]" class="form-control" multiple required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection