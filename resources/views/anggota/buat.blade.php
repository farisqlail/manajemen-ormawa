@extends('app')

@section('content')
<div class="container mt-5">
    <h2>Tambah Anggota Baru</h2>
    <form action="{{ route('anggotas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="club_id">Ormawa</label>
            @if(Auth::user()->id_club)
            <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
            <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
            @else
            <select class="form-control" name="id_club" id="club_id" required>
                <option value="">-- Pilih Ormawa --</option>
                @foreach($daftarOrmawaNonUser as $ormawa)
                <option value="{{ $ormawa->id }}">{{ $ormawa->name }}</option>
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
                <option value="{{ $divisi->id }}">{{ $divisi->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#club_id').on('change', function() {
            const clubId = $(this).val();
            if (clubId) {
                $.ajax({
                    url: '/get-divisi-by-club/' + clubId,
                    type: 'GET',
                    success: function(data) {
                        $('#id_division').empty().append('<option value="">Pilih Divisi</option>');
                        $.each(data, function(key, value) {
                            $('#id_division').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Gagal memuat data divisi.');
                    }
                });
            } else {
                $('#id_division').empty().append('<option value="">Pilih Divisi</option>');
            }
        });
    });
</script>

@endsection