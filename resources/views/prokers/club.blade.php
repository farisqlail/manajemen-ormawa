@extends('app')

@section('content')
<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($pendingProkers->isNotEmpty())
            <h2>Proker menunggu persetujuan {{ $pendingProkers->first()->club->name }}</h2>
            @else
            <h2>Tidak ada proker diormawa ini.</h2>
            @endif


            @if($pendingProkers->isEmpty())
            <p class="text-muted">Tidak ada proker yang sedang pending diormawa ini.</p>
            @else

            @php
            $showLaporanColumn = $pendingProkers->contains(function($proker) {
            return !empty($proker->status_laporan) && $proker->status_laporan !== '';
            });
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Proker</th>
                        <th>Berlangsung pada</th>
                        <th>Proposal</th>
                        @if($showLaporanColumn)
                        <th>Laporan (LPJ)</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingProkers as $proker)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $proker->name }}</td>
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
                        @if($showLaporanColumn)
                        <td>
                            @if(!empty($proker->status_laporan) && $proker->status_laporan !== '')
                            <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info mr-2 btn-sm">
                                Laporan
                            </a>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if(!empty($proker->proposal))
                            @if(Auth::user()->role == 'admin' && $proker->status == 'pending')
                            <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm">Approve</button>
                            </form>
                            @elseif(Auth::user()->role == 'admin' && $proker->status_laporan == 'pending')
                            <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            @endif

                            <button class="btn btn-danger btn-sm" onclick="openRejectModal({{ $proker->id }})">
                                Reject
                            </button>
                            @else
                            <span>-</span>
                            @endif
                        </td>
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
                    <button type="submit" class="btn btn-danger">Submit Reject</button>
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