@extends('app')

@section('content')
<div class="container mt-5">
    <h2>List Proker</h2>
    <a href="{{ route('prokers.create') }}" class="btn btn-primary mb-3">Ajukan Proker</a>
    <div class="row">
        @if($prokers->isEmpty())
        <div class="col-12">
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada proker yang ditemukan. Silakan ajukan proker baru.
            </div>
        </div>
        @else
        @foreach ($prokers as $proker)
        <div class="col-md-3 mb-4">
            <div class="card" style="width: auto; display: inline-block;">
                <div class="card-body">
                    <h5 class="card-title"><b>{{ $proker->name }}</b></h5>
                    <p class="card-text"><strong>Budget:</strong> Rp {{ number_format($proker->budget, 0, ',', '.') }}</p>
                    <p class="card-text"><strong>Target Event:</strong> {{ $proker->target_event }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $proker->status }}</p>

                    <div class="d-flex">
                        <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-warning mr-2">
                            <i class="fas fa-fw fa-pen"></i>
                        </a>
                        <form action="{{ route('prokers.destroy', $proker->id) }}" method="POST" style="display:inline-block;" class="mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>
                        </form>
                        <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-primary mr-2">
                            Proposal
                        </a>
                        <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-info mr-2">
                            LPJ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection