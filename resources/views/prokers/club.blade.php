@extends('app')

@section('content')
<div class="container mt-5">
    @if($pendingProkers->isNotEmpty())
    <h2>Proker menunggu persetujuan {{ $pendingProkers->first()->club->name }}</h2>
    @else
    <h2>Tidak ada proker diormawa ini.</h2>
    @endif

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pendingProkers->isEmpty())
    <p class="text-muted">Tidak ada proker yang sedang pending diormawa ini.</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Proker</th>
                <th>Berlangsung pada</th>
                <th>LPJ</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingProkers as $proker)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $proker->name }}</td>
                <td>{{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}</td>
                <td>
                    @if($proker->document_lpj)
                    <a href="{{ route('prokers.download', $proker->id) }}" class="btn btn-primary btn-sm">Download LPJ</a>
                    @else
                    <span class="text-muted">No document available</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('prokers.reject', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection