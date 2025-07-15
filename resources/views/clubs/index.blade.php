@extends('app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1>Ormawa</h1>
            <a href="{{ route('clubs.create') }}" class="btn btn-primary mb-3">Tambah Ormawa</a>
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Ormawa</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($daftarOrmawa as $ormawa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ormawa->name }}</td>
                        <td>{!! Str::limit($ormawa->description, 100, '...') !!}</td>
                        <td>
                            <a href="{{ route('divisions.index', ['id_club' => $ormawa->id]) }}" class="btn btn-info btn-sm">Divisi</a>
                            <a href="{{ route('clubs.edit', $ormawa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('clubs.destroy', $ormawa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection