@extends('app')

@section('content')
<div class="container mt-5">
    <h2>List Proker</h2>
    <a href="{{ route('prokers.create') }}" class="btn btn-primary mb-3">Ajukan Proker</a>
    <div class="row">
        @foreach ($prokers as $proker)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><b>{{ $proker->name }}</b></h5>
                    <p class="card-text"><strong>Budget:</strong> Rp {{ number_format($proker->budget, 0, ',', '.') }}</p>
                    <p class="card-text"><strong>Target Event:</strong> {{ $proker->target_event }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $proker->status }}</p>

                    <div class="d-flex">
                        <a href="{{ Storage::url($proker->document_lpj) }}" class="btn btn-info mr-2" download>Download</a>
                        <a href="{{ route('prokers.edit', $proker->id) }}" class="btn btn-warning mr-2">
                            <i class="fas fa-fw fa-pen"></i>
                        </a>
                        <form action="{{ route('prokers.destroy', $proker->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection