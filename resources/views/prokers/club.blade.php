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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Proker</th>
                        <th>Berlangsung pada</th>
                        <th>Proposal</th>
                        <th>Laporan (LPJ)</th>
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
                            <a href="{{ route('prokers.export', $proker->id) }}" class="btn btn-primary mr-2 btn-sm">
                                Proposal
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('prokers.exportLaporan', $proker->id) }}" class="btn btn-info mr-2 btn-sm">
                                Laporan
                            </a>
                        </td>
                        <td>
                            @if(Auth::user()->role == 'pembina' && ($proker->status == 'pending' || $proker->status_laporan == ''))
                            <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            @elseif(Auth::user()->role == 'pembina' && ($proker->status == 'approve' || $proker->status_laporan == 'pending'))
                            <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            @endif

                            @if(Auth::user()->role == 'admin' && ($proker->status == 'pembina' || $proker->status_laporan == ''))
                            <form action="{{ route('prokers.approve', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm">Approve</button>
                            </form>
                            @elseif(Auth::user()->role == 'admin' && ($proker->status == 'approve' || $proker->status_laporan == 'pembina'))
                            <form action="{{ route('prokers.approve.laporan', $proker->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            @endif

                            <!-- Tombol Open Reject -->
                            <button class="btn btn-danger btn-sm" onclick="openRejectModal({{ $proker->id }})">
                                Reject
                            </button>
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