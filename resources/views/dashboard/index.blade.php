@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Welcome, {{ Auth::user()->name }}</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
        @if(Auth::user()->status == "nonactive")
            <div class="alert alert-warning">Ubah Password anda untuk mengaktifkan akun!</div>
        @endif
    @endif

    @if(Auth::user()->role == 'admin')
    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Prokers Pending <span class="badge badge-primary rounded-circle">{{ $pendingProkersPembinaAdmin->count() }}</span></h4>
            <div class="card">
                <div class="card-body">
                    @if($pendingProkersPembinaAdmin->isEmpty())
                    <p class="text-muted">No pending prokers available.</p>
                    @else
                    @foreach($pendingProkersPembinaAdmin as $clubId => $prokers)
                    <h5>Club: {{ $prokers->first()->club->name }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Target Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prokers as $proker)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $proker->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('prokers.club', $clubId) }}" class="btn btn-info btn-sm">View Proker</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4>List Ormawa</h4>
            <div class="card">
                <div class="card-body">
                    @if($approvedProkers->isEmpty())
                    <p class="text-muted">No approved prokers available.</p>
                    @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clubs as $club)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $club->name }}</td>

                                <td>
                                    <a href="{{ route('prokers.club', $club->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @elseif(Auth::user()->role == 'ormawa')
    <div class="row mt-4">
        @if(Auth::user()->role == 'ormawa' && Auth::user()->status == 'active')
        <div class="col-md-6">
            <h4>Prokers Pending <span class="badge badge-primary rounded-circle">{{ $pendingProkers->count() }}</span></h4>
            <div class="list-group">
                @if($pendingProkers->isEmpty())
                <p class="text-muted">No pending prokers available.</p>
                @else
                @foreach($pendingProkers as $proker)
                <a href="{{ route('prokers.show', $proker->id) }}" class="list-group-item list-group-item-action">
                    {{ $proker->name }} - Tanggal Target: {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}
                    <span class="badge badge-warning float-right">Pending</span> <!-- Label status -->
                </a>
                @endforeach
                @endif
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <h4>Proker Mendatang</h4>
            <div class="list-group">
                @if($nonPendingProkers->isEmpty())
                <p class="text-muted">No non-pending prokers available.</p>
                @else
                @foreach($nonPendingProkers as $proker)
                <a href="{{ route('prokers.show', $proker->id) }}" class="list-group-item list-group-item-action">
                    {{ $proker->name }} - Tanggal Target: {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}
                    @if($proker->status == 'approved' && Auth::user()->role == 'ormawa' && Auth::user()->status == 'active')
                    <span class="badge badge-success float-right">Approved</span>
                    @elseif($proker->status == 'rejected')
                    <span class="badge badge-danger float-right">Rejected</span>
                    @else
                    <span class="badge badge-secondary float-right">Sedang Berjalan</span>
                    @endif
                </a>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Data Anggota</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($members->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">No members available.</td>
                            </tr>
                            @else
                            @foreach($members as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $member->name }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection