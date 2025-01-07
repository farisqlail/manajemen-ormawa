@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Detail Activity: {{ $activity->name }}</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Deskripsi</h5>
            <p>{!! $activity->description !!}</p>

            <h5 class="card-title">Photos</h5>
            <div class="row">
                @if($activity->photos)
                @foreach(json_decode($activity->photos) as $photo)
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('storage/' . $photo) }}" class="img-fluid" alt="Activity Photo">
                </div>
                @endforeach
                @else
                <p class="text-muted">No photos available.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection