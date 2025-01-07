@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Detail Proker: {{ $proker->name }}</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informasi Proker</h5>
            <p><strong>Nama Klub:</strong> {{ $proker->club->name }}</p>
            @if(Auth::user()->role === 'ormawa' && Auth::user()->status === 'active' || Auth::user()->role === 'admin')
            <p><strong>Dokumen LPJ:</strong>
                @if($proker->document_lpj)
                <a href="{{ asset('storage/' . $proker->document_lpj) }}" class="btn btn-primary btn-sm" download>Download LPJ</a>
                @else
                <span class="text-muted">No document available</span>
                @endif
            </p>
            @endif
            <p><strong>Anggaran:</strong> {{ number_format($proker->budget, 2, ',', '.') }} IDR</p>
            <p><strong>Tanggal Target:</strong> {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($proker->status) }}</p>
        </div>
    </div>

    @if(Auth::user()->role === 'ormawa' && Auth::user()->status === 'active')
    <div class="mt-4">
        <a href="{{ route('prokers.index') }}" class="btn btn-secondary">Kembali ke Daftar Proker</a>
    </div>
    @endif
</div>
@endsection