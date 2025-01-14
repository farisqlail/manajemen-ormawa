@extends('app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1>Divisions for {{ $club->name }}</h1>
            @if(Auth::user()->role == 'ketua')
            <a href="{{ route('divisions.create', $club->id) }}" class="btn btn-primary mb-3">Tambah Divisi</a>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Divisi</th>
                        @if(Auth::user()->role == 'ketua')
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisions as $division)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $division->name }}</td>
                        @if(Auth::user()->role == 'ketua')
                        <td>
                            <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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