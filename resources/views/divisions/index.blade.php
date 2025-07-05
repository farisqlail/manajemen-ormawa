@extends('app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1>Divisi untuk {{ $dataOrmawa->name }}</h1>
            @if(Auth::user()->role == 'ormawa')
            <a href="{{ route('divisions.create', $dataOrmawa->id) }}" class="btn btn-primary mb-3">Tambah Divisi</a>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Divisi</th>
                        @if(Auth::user()->role == 'ormawa')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($daftarDivisi as $divisi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $divisi->name }}</td>
                        @if(Auth::user()->role == 'ormawa')
                        <td>
                            <a href="{{ route('divisions.edit', $divisi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('divisions.destroy', $divisi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">Delete</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection