@extends('app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Edit Anggota</h2>
            <form action="{{ route('anggotas.update', $dataAnggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Ormawa -->
                <div class="form-group">
                    <label for="club_id">Ormawa</label>
                    @if(Auth::user()->id_club)
                    <!-- Tetap pakai select, tapi di-disable -->
                    <select class="form-control" id="club_id" disabled>
                        <option value="{{ Auth::user()->id_club }}">{{ Auth::user()->club->name ?? 'N/A' }}</option>
                    </select>
                    <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
                    @else
                    <select class="form-control" name="id_club" id="club_id" required>
                        <option value="">-- Pilih Ormawa --</option>
                        @foreach($daftarOrmawaNonUser as $ormawa)
                        <option value="{{ $ormawa->id }}" {{ $dataAnggota->id_club == $ormawa->id ? 'selected' : '' }}>
                            {{ $ormawa->name }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>

                <!-- Divisi -->
                <div class="form-group">
                    <label for="id_division">Divisi</label>
                    <select class="form-control" id="id_division" name="id_division" required>
                        <option value="">Pilih Divisi</option>
                        @foreach($daftarDivisi as $divisi)
                        <option value="{{ $divisi->id }}" {{ $dataAnggota->id_division == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $dataAnggota->name }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Ubah</button>
            </form>
        </div>
    </div>
</div>

<!-- Script AJAX untuk load divisi -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function loadDivisi(clubId, selectedDivisionId = null) {
            if (clubId) {
                $.ajax({
                    url: '/get-divisi-by-club/' + clubId,
                    type: 'GET',
                    success: function(data) {
                        $('#id_division').empty().append('<option value="">Pilih Divisi</option>');
                        $.each(data, function(key, value) {
                            const selected = (value.id == selectedDivisionId) ? 'selected' : '';
                            $('#id_division').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#id_division').empty().append('<option value="">Gagal memuat divisi</option>');
                    }
                });
            } else {
                $('#id_division').empty().append('<option value="">Pilih Divisi</option>');
            }
        }

        // Trigger jika user mengubah club
        $('#club_id').on('change', function() {
            const clubId = $(this).val();
            loadDivisi(clubId);
        });

        // Cek user tidak punya id_club (artinya pakai select)
        const clubSelect = $('#club_id');
        const isClubDropdown = !clubSelect.prop('disabled'); // dropdown = tidak disabled
        const selectedClubId = clubSelect.val();
        const selectedDivisionId = "{{ $dataAnggota->id_division }}";

        if (isClubDropdown && selectedClubId) {
            // Kalau club sudah terisi saat load awal, langsung muat divisinya
            loadDivisi(selectedClubId, selectedDivisionId);
        }

        // Jika user punya id_club (input hidden), pakai cara sebelumnya
        if (!isClubDropdown) {
            const hiddenClubId = $('input[name="id_club"]').val();
            if (hiddenClubId) {
                loadDivisi(hiddenClubId, selectedDivisionId);
            }
        }
    });
</script>
@endsection