@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Detail Kegiatan: {{ $kegiatan->name }}</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Deskripsi</h5>
            <p>{!! $kegiatan->description !!}</p>

            <h5 class="card-title">Foto-foto</h5>
            <div class="row">
                @if($kegiatan->photos)
                @foreach(json_decode($kegiatan->photos) as $foto)
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('storage/' . $foto) }}" class="img-fluid" alt="Foto Kegiatan">
                </div>
                @endforeach
                @else
                <p class="text-muted">Tidak ada foto yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection