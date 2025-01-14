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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clubs as $club)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $club->name }}</td>
                        <td>{!! Str::limit($club->description, 100, '...') !!}</td>
                        <td>
                            <a href="{{ route('clubs.edit', $club->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('clubs.destroy', $club->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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