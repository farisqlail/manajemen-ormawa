@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Welcome, {{ Auth::user()->name }}</h2>

    <div class="row mt-4">
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

        <div class="col-md-6">
            <h4>Proker Mendatang</h4>
            <div class="list-group">
                @if($nonPendingProkers->isEmpty())
                <p class="text-muted">No non-pending prokers available.</p>
                @else
                @foreach($nonPendingProkers as $proker)
                <a href="{{ route('prokers.show', $proker->id) }}" class="list-group-item list-group-item-action">
                    {{ $proker->name }} - Tanggal Target: {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }} <!-- Menampilkan tanggal target -->
                    @if($proker->status == 'approved') <!-- Menampilkan label status jika proker disetujui -->
                    <span class="badge badge-success float-right">Approved</span>
                    @elseif($proker->status == 'rejected') <!-- Menampilkan label status jika proker ditolak -->
                    <span class="badge badge-danger float-right">Rejected</span>
                    @else
                    <span class="badge badge-secondary float-right">Other</span> <!-- Label untuk status lain -->
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

</div>
@endsection