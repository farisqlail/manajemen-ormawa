@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Kegiatan</h2>

            {{-- Menampilkan jumlah notifikasi jika ada --}}
            @if($jumlahNotifikasi > 0)
            <div class="alert alert-warning">
                Terdapat {{ $jumlahNotifikasi }} proker yang menunggu persetujuan atau laporan.
            </div>
            @endif

            @if(Auth::user()->role == 'ormawa' || Auth::user()->role == 'superadmin')
            <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">Tambah Kegiatan</a>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ormawa</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Foto Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($kegiatan->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada kegiatan ditemukan. Silakan tambahkan kegiatan baru.</td>
                    </tr>
                    @else
                    @foreach ($kegiatan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if(Auth::user()->role === 'superadmin')
                        <td>{{ $item->club->name ?? '-' }}</td>
                        @endif
                        <td>{{ $item->name }}</td>
                        <td>{!! Str::limit($item->description, 50) !!}</td>
                        <td>
                            @foreach (json_decode($item->photos) as $photo)
                            <img src="{{ Storage::url($photo) }}" width="50" height="50" alt="Foto Kegiatan">
                            @endforeach
                        </td>
                        <td>
                            @if(Auth::user()->role == 'ormawa' || Auth::user()->role == 'superadmin')
                            <a href="{{ route('activities.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('activities.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                            @else
                            <a href="{{ route('activities.show', $item->id) }}" class="btn btn-info">Lihat</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection