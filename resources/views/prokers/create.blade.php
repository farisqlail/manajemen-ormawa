@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Ajukan Proker Baru</h2>
            <form action="{{ route('prokers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="status" name="status" value="pending">

                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="id_club">Pilih Ormawa</label>
                            <select name="id_club" id="id_club" class="form-control" required>
                                <option value="">-- Pilih Ormawa --</option>
                                @foreach($daftarOrmawa as $ormawa)
                                <option value="{{ $ormawa->id }}">{{ $ormawa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="nama_proker">Nama Proker:</label>
                            <input type="text" class="form-control" id="nama_proker" name="name" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="anggaran">Anggaran (Rp):</label>
                            <input type="number" class="form-control" id="anggaran" name="budget" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="tanggal_target">Target Kegiatan:</label>
                            <input type="date" class="form-control" id="tanggal_target" name="target_event" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="proposal_file">Upload Proposal (opsional):</label>
                    <input type="file" class="form-control" id="proposal_file" name="proposal_file" accept=".pdf,.doc,.docx">
                    <small class="form-text text-muted">Maksimal 5 MB. Format: PDF, DOC, DOCX.</small>
                    <div id="proposal-file-chosen" class="mt-2 text-muted"></div>
                </div>

                <button type="submit" class="btn btn-primary">Ajukan Proker</button>
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
</script>
@endsection