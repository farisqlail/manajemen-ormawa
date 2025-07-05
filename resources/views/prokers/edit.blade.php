@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Edit Proker</h2>
            <form action="{{ route('prokers.update', $proker->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($proker->status_laporan == '' && $proker->status !== 'approved')
                <input type="hidden" id="status" name="status" value="pending">
                <input type="hidden" id="status_laporan" name="status_laporan" value="">
                @else
                <input type="hidden" id="status" name="status" value="approved">
                <input type="hidden" id="status_laporan" name="status_laporan" value="pending">
                @endif

                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="id_club">Ormawa</label>
                            <input type="text" class="form-control" id="id_club" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
                            <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Nama Proker:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $proker->name }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="budget">Anggaran:</label>
                            <input type="number" class="form-control" id="budget" name="budget" value="{{ $proker->budget }}" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="target_event">Target Kegiatan:</label>
                            <input type="date" class="form-control" id="target_event" name="target_event" value="{{ $proker->target_event }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    {{-- Proposal --}}
                    <div class="col-md-6">
                        <label for="proposal_file" class="form-label">Proposal (PDF atau DOC) MAX: 5MB</label>
                        <input type="file" class="d-none" id="proposal_file" name="proposal_file" accept=".pdf,.doc,.docx">
                        <label for="proposal_file" class="btn btn-outline-primary w-100 text-center" style="cursor: pointer; padding: 2rem 0;">
                            Pilih File Proposal
                        </label>
                        @if($proker->proposal)
                        <small class="form-text text-muted mt-1">
                            File saat ini: <a href="{{ Storage::url($proker->proposal) }}" target="_blank">Lihat file</a>
                        </small>
                        @endif
                        <small class="form-text text-muted mt-1">Kosongkan jika tidak ingin mengganti.</small>
                        <div id="proposal-file-chosen" class="mt-2 text-secondary"></div>
                    </div>

                    {{-- Laporan --}}
                    @if($proker->status == 'approved')
                    <div class="col-md-6">
                        <label for="laporan_file" class="form-label">Laporan (PDF atau DOC) MAX: 5MB</label>
                        <input type="file" class="d-none" id="laporan_file" name="laporan_file" accept=".pdf,.doc,.docx">
                        <label for="laporan_file" class="btn btn-outline-primary w-100 text-center" style="cursor: pointer; padding: 2rem 0;">
                            Pilih File Laporan
                        </label>
                        @if($proker->laporan)
                        <small class="form-text text-muted mt-1">
                            File saat ini: <a href="{{ Storage::url($proker->laporan) }}" target="_blank">Lihat file</a>
                        </small>
                        @endif
                        <small class="form-text text-muted mt-1">Kosongkan jika tidak ingin mengganti.</small>
                        <div id="laporan-file-chosen" class="mt-2 text-secondary"></div>
                    </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    const proposalInput = document.getElementById('proposal_file');
    const proposalChosen = document.getElementById('proposal-file-chosen');
    proposalInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            proposalChosen.textContent = 'File terpilih: ' + this.files[0].name;
        } else {
            proposalChosen.textContent = '';
        }
    });

    const laporanInput = document.getElementById('laporan_file');
    const laporanChosen = document.getElementById('laporan-file-chosen');
    if (laporanInput) {
        laporanInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                laporanChosen.textContent = 'File terpilih: ' + this.files[0].name;
            } else {
                laporanChosen.textContent = '';
            }
        });
    }
</script>
@endsection