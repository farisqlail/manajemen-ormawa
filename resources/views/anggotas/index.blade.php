@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Anggota List</h2>
            <a href="{{ route('anggotas.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ormawa</th>
                        <th>Divisi</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggotas as $anggota)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $anggota->club->name ?? '-' }}</td>
                        <td>{{ $anggota->division->name ?? '-' }}</td>
                        <td>{{ $anggota->name }}</td>
                        <td>
                            <a href="{{ route('anggotas.edit', $anggota->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('anggotas.destroy', $anggota->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus anggota ini?");
    }
</script>
@endsection