@extends('app')

@section('content')
<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($prokerPending->isNotEmpty())
            <h2>Proker menunggu persetujuan {{ $prokerPending->first()->club->name }}</h2>
            @else
            <h2>Tidak ada proker di ormawa ini.</h2>
            @endif

            @if($prokerPending->isEmpty())
            <p class="text-muted">Tidak ada proker yang sedang pending di ormawa ini.</p>
            @else

            @php
            $tampilkanKolomLaporan = $prokerPending->contains(function($proker) {
            return !empty($proker->status_laporan) && $proker->status_laporan !== '';
            });
            @endphp

            <form method="GET" action="{{ route('prokers.club', ['clubId' => $prokerPending->first()->id_club ?? '' ]) }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama proker..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Proker</th>
                        <th>Dana</th>
                        <th>Berlangsung pada</th>
                        <th>Proposal</th>
                        @if($tampilkanKolomLaporan)
                        <th>Laporan (LPJ)</th>
                        @endif
                        @if(Auth::user()->role == 'admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($prokerPending as $proker)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $proker->name }}</td>
                        <td>Rp {{ number_format($proker->budget, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}</td>
                        <td>
                            @if(!empty($proker->proposal))
                            <a href="{{ route('prokers.export', $proker->id) }}" class="btn btn-primary mr-2 btn-sm">
                                Proposal
                            </a>
                            @else
                            <span>-</span>
                            @endif
                        </td>
                        @if($tampilkanKolomLaporan)
                        <td>
                            @if(!empty($proker->status_laporan) && $proker->status_laporan !== '')
                            <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info mr-2 btn-sm">
                                Laporan
                            </a>
                            @endif
                        </td>
                        @endif
                        @if(Auth::user()->role == 'admin')
                        <td>
                            @if(!empty($proker->proposal))
                            @php
                            $showButtons = false;
                            @endphp

                            {{-- Tombol Admin --}}
                            @if(Auth::user()->role == 'admin')

                            {{-- Jika status proposal masih pending --}}
                            @if($proker->status == 'pending' && $proker->reason == null)
                            @php $showButtons = true; @endphp
                            <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('prokers.rejectNoReason.proposal', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                            <button class="btn btn-info btn-sm" onclick="openRejectModal({{ $proker->id }})">Komentar</button>

                            {{-- Jika status laporan masih pending --}}
                            @elseif($proker->status_laporan == 'pending' && $proker->reason == null)
                            @php $showButtons = true; @endphp
                            <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('prokers.rejectNoReason.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                            <button class="btn btn-info btn-sm" onclick="openRejectModal({{ $proker->id }})">Komentar</button>
                            @endif
                            @endif

                            {{-- Status tampilan hanya jika tombol tidak ditampilkan --}}
                            @unless($showButtons)
                            @if($proker->status == 'approved' && $proker->status_laporan == null)
                            <div><span class="badge bg-success text-white">Proposal sudah disetujui</span></div>
                            @elseif($proker->status == 'rejected')
                            <div><span class="badge bg-danger text-white">Proposal sudah ditolak</span></div>
                            @elseif($proker->status == 'approved' && $proker->status_laporan == 'approved')
                            <div><span class="badge bg-success text-white">Laporan sudah disetujui</span></div>
                            @elseif($proker->status_laporan == 'rejected')
                            <div><span class="badge bg-danger text-white">Laporan sudah ditolak</span></div>
                            @elseif(!empty($proker->reason) && $proker->status == 'pending')
                            <div><span class="badge bg-warning text-dark">Proposal sudah dikomentari</span></div>
                            @elseif(!empty($proker->reason) && $proker->status_laporan == 'pending')
                            <div><span class="badge bg-warning text-dark">Laporan sudah dikomentari</span></div>
                            @endif
                            @endunless

                            @else
                            <span>-</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="rejectProkerId" name="proker_id">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Masukkan alasan:</label>
                        <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit Penolakan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menangani modal -->
<script>
    function openRejectModal(prokerId) {
        document.getElementById('rejectProkerId').value = prokerId;
        document.getElementById('rejectForm').action = `/prokers/${prokerId}/reject`;
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    }
</script>
@endsection