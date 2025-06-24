@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Edit Proker</h2>
    <form action="{{ route('prokers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" id="status" name="status" value="pending">

        <div class="row mb-3">
            <div class="col">
                <div class="form-group">
                    <label for="club_id">Ormawa</label>
                    <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
                    <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="name">Nama Proker:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-group">
                    <label for="budget">Budget:</label>
                    <input type="number" class="form-control" id="budget" name="budget" required>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="target_event">Target Event:</label>
                    <input type="date" class="form-control" id="target_event" name="target_event" required>
                </div>
            </div>
        </div>

        {{-- Proposal File Upload --}}
        <!-- <div class="form-group mb-4">
            <label for="proposal_file" class="form-label">Proposal (File PDF atau DOC)</label>
            <input type="file" class="d-none" id="proposal_file" name="proposal_file" accept=".pdf,.doc,.docx">
            <label for="proposal_file" class="btn btn-outline-primary w-100 text-center" style="cursor: pointer; padding-top: 3rem; padding-bottom: 3rem;">
                Klik di sini untuk memilih file proposal
            </label>
            <small class="form-text text-muted mt-1">Upload file baru jika ingin mengganti file proposal.</small>
            <div id="proposal-file-chosen" class="mt-2 text-secondary"></div>
        </div> -->

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
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