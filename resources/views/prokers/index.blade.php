@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Proker List</h2>
            <a href="{{ route('prokers.create') }}" class="btn btn-primary mb-3">Add New Proker</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Club ID</th>
                        <th>Name</th>
                        <th>Document LPJ</th>
                        <th>Budget</th>
                        <th>Target Event</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prokers as $proker)
                    <tr>
                        <td>{{ $proker->id }}</td>
                        <td>{{ $proker->id_club }}</td>
                        <td>{{ $proker->name }}</td>
                        <td>{{ $proker->document_lpj }}</td>
                        <td>{{ $proker->budget }}</td>
                        <td>{{ $proker->target_event }}</td>
                        <td>{{ $proker->status }}</td>
                        <td>
                            <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('prokers.destroy', $proker->id) }}" method="POST" style="display:inline-block;">
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
@endsection