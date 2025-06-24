@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>List Proker</h2>

            <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
                @if(Auth::user()->role == 'ormawa')
                <a href="{{ route('prokers.create') }}" class="btn btn-primary mb-2">Ajukan Proker</a>
                @endif

                <form method="GET" action="{{ route('prokers.index') }}" class="mb-2" style="min-width: 250px;">
                    <div class="input-group">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama proker...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            @if($prokers->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada proker yang ditemukan. Silakan ajukan proker baru.
            </div>
            @else

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Proker</th>
                        <th>Budget</th>
                        <th>Target Event</th>
                        <th>Status Proposal</th>
                        <th>Status Laporan</th>
                        <th>Alasan Penolakan</th>
                        @if(Auth::user()->role == 'ormawa')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prokers as $proker)
                    <tr>
                        <td>{{ $proker->name }}</td>
                        <td>Rp {{ number_format($proker->budget, 0, ',', '.') }}</td>
                        <td>{{ $proker->target_event }}</td>
                        <td>
                            @if($proker->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                            @elseif($proker->status == 'approved')
                            <span class="badge badge-success">Approved</span>
                            @elseif($proker->status == 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($proker->laporan !== "")
                            @if($proker->status_laporan == 'pending')
                            <span class="badge badge-warning">Pending</span>
                            @elseif($proker->status_laporan == 'approved')
                            <span class="badge badge-success">Approved</span>
                            @elseif($proker->status_laporan == 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                            @endif
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($proker->reason !== "")
                            {{ $proker->reason }}
                            @else
                            -
                            @endif
                        </td>

                        @if(Auth::user()->role == 'ormawa')
                        <td>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-warning btn-sm mr-2 mb-2">
                                    <i class="fas fa-fw fa-pen"></i>
                                </a>
                                <form action="{{ route('prokers.destroy', $proker->id) }}" method="POST" class="mr-2 mb-2" onsubmit="return confirm('Yakin hapus proker ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-trash"></i></button>
                                </form>
                                <!-- <a href="{{ route('prokers.export', $proker->id) }}" class="btn btn-primary btn-sm mr-2 mb-2">
                                    Proposal
                                </a> -->
                                <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-primary btn-sm mr-2 mb-2">
                                    Proposal
                                </a>
                                @if($proker->status == 'approved' && $proker->laporan == "")
                                <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-info btn-sm mr-2 mb-2">
                                    Upload LPJ
                                </a>
                                @elseif($proker->laporan !== "")
                                <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info btn-sm mr-2 mb-2">
                                    Laporan
                                </a>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                {{ $prokers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection