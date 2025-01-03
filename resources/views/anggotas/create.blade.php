@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Add New Anggota</h2>
    <form action="{{ route('anggotas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="club_id">Oramawa</label>
            <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
            <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
        </div>
        <div class="form-group">
            <label for="id_division">Divisi</label>
            <select class="form-control" id="id_division" name="id_division" required>
                <option value="">Pilih Divisi</option>
                @foreach($divisions as $division)
                <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection