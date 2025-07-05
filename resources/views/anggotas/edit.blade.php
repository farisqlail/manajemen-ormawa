@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Edit Anggota</h2>
            <form action="{{ route('anggotas.update', $dataAnggota->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="club_id">Ormawa</label>
                    <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
                    <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
                </div>
                <div class="form-group">
                    <label for="id_division">Divisi</label>
                    <select class="form-control" id="id_division" name="id_division" required>
                        <option value="">Pilih Divisi</option>
                        @foreach($daftarDivisi as $divisi)
                        <option value="{{ $divisi->id }}" {{ $dataAnggota->id_division == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $dataAnggota->name }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Ubah</button>
            </form>
        </div>
    </div>
</div>
@endsection