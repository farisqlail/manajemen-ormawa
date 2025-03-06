@extends('app')

@section('content')
<div class="container mt-5">
    <h2>List Proker</h2>
    @if(Auth::user()->role == 'ormawa')
    <a href="{{ route('prokers.create') }}" class="btn btn-primary mb-3">Ajukan Proker</a>
    @endif
    <div class="row">
        @if($prokers->isEmpty())
        <div class="col-12">
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada proker yang ditemukan. Silakan ajukan proker baru.
            </div>
        </div>
        @else

        <div class="row">
            @foreach ($prokers as $proker)
            <div class="col-md-12 mb-4 w-auto">
                <div class="card h-100" style="width: 100%;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5 class="card-title"><b>{{ $proker->name }}</b></h5>
                            <p class="card-text"><strong>Budget:</strong> Rp {{ number_format($proker->budget, 0, ',', '.') }}</p>
                            <p class="card-text"><strong>Target Event:</strong> {{ $proker->target_event }}</p>
                            <p class="card-text"><strong>Status Proposal:</strong>
                                @if($proker->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif($proker->status == 'pembina')
                                <span class="badge badge-warning">Pending Kemahasiswaan</span>
                                @elseif($proker->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                                @elseif($proker->status =='rejected')
                                <span class="badge badge-danger">Rejected</span>
                                @endif
                            </p>
                            @if($proker->laporan !== "")
                            <p class="card-text"><strong>Status Laporan:</strong>
                                @if($proker->status_laporan == 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif($proker->status_laporan == 'pembina')
                                <span class="badge badge-warning">Pending Kemahasiswaan</span>
                                @elseif($proker->status_laporan == 'approved')
                                <span class="badge badge-success">Approved</span>
                                @elseif($proker->status_laporan =='rejected')
                                <span class="badge badge-danger">Rejected</span>
                                @endif
                            </p>
                            @endif
                        </div>

                        <div>
                            @if(Auth::user()->role == 'ormawa')
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-warning mr-2 mb-2">
                                    <i class="fas fa-fw fa-pen"></i>
                                </a>
                                <form action="{{ route('prokers.destroy', $proker->id) }}" method="POST" class="mr-2 mb-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>
                                </form>
                                <a href="{{ route('prokers.export', $proker->id) }}" class="btn btn-primary mr-2 mb-2">
                                    Proposal
                                </a>
                                @if($proker->status == 'approved' && $proker->laporan == "")
                                <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-info mr-2 mb-2">
                                    Upload LPJ
                                </a>
                                @elseif($proker->laporan !== "")
                                <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info mr-2 mb-2">
                                    Laporan
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @endif
    </div>
</div>
@endsection