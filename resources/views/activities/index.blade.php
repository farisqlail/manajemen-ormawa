@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Kegiatan</h2>
            @if(Auth::user()->role == 'ketua')
            <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">Tambah Kegiatan</a>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Foto Kegiatan</th>
                        @if(Auth::user()->role == 'ketua')
                        <th>Actions</th>
                        @else
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($activities->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No activities found. Please create a new activity.</td>
                    </tr>
                    @else
                    @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $activity->name }}</td>
                        <td>{!! Str::limit($activity->description, 50) !!}</td>
                        <td>
                            @foreach (json_decode($activity->photos) as $photo)
                            <img src="{{ Storage::url($photo) }}" width="50" height="50" alt="Photo">
                            @endforeach
                        </td>
                        @if(Auth::user()->role == 'ketua')
                        <td>
                            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @else
                        <td>
                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-info">Lihat</a>
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
@endsection