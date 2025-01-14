@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Anggota List</h2>
            @if(Auth::user()->role == 'ketua' && Auth::user()->status == 'active')
            <a href="{{ route('anggotas.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ormawa</th>
                        <th>Divisi</th>
                        <th>Nama</th>
                        @if(Auth::user()->role == 'ketua')
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($anggotas->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada anggota yang ditemukan.</td>
                    </tr>
                    @else
                    @foreach ($anggotas as $anggota)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $anggota->club->name ?? '-' }}</td>
                        <td>{{ $anggota->division->name ?? '-' }}</td>
                        <td>{{ $anggota->name }}</td>
                        @if(Auth::user()->role == 'ketua')
                        <td>
                            <a href="{{ route('anggotas.edit', $anggota->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('anggotas.destroy', $anggota->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    @endif
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