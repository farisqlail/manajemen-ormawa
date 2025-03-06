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
                <th>Proposal</th>
                <th>Laporan (LPJ)</th>
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
                    <a href="{{ route('prokers.export', $proker->id) }}" class="btn btn-primary mr-2 btn-sm">
                        Proposal
                    </a>
                </td>
                <td>
                    <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info mr-2 btn-sm">
                        Laporan
                    </a>
                </td>
                <td>
                    @if(Auth::user()->role == 'pembina' && ($proker->status == 'pending' || $proker->status_laporan == ''))
                    <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve Pembina</button>
                    </form>
                    @elseif(Auth::user()->role == 'pembina' && ($proker->status == 'approve' || $proker->status_laporan == 'pending'))
                    <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve Pembina Laporan</button>
                    </form>
                    @endif

                    @if(Auth::user()->role == 'admin' && ($proker->status == 'pembina' || $proker->status_laporan == ''))
                    <form action="{{ route('prokers.approve.admin', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">Approve Admin</button>
                    </form>
                    @elseif(Auth::user()->role == 'admin' && ($proker->status == 'approve' || $proker->status_laporan == 'pembina'))
                    <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve Admin Laporan</button>
                    </form>
                    @endif

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