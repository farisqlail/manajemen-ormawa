<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: stretch;
            /* Mengatur agar item di dalam flex container bisa menyesuaikan tinggi */
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto;
            /* Center card */
        }

        .proker-list {
            max-width: 600px;
            /* Lebar maksimum untuk daftar proker */
            margin-left: 20px;
            /* Spasi kiri untuk daftar proker */
            overflow-y: auto;
            /* Menambahkan scroll jika konten melebihi tinggi */
            height: 100vh;
            /* Tinggi menyesuaikan layar */
            padding: 20px;
            /* Padding untuk daftar proker */
            background-color: white;
            /* Warna latar belakang untuk card */
            border-radius: 8px;
            /* Sudut melengkung */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Bayangan untuk card */
        }

        .proker-list h5 {
            margin-top: 20px;
            /* Spasi atas untuk judul ormawa */
        }

        @media (max-width: 768px) {
            body {
                padding-top: 20px;

                padding-bottom: 20px;
            }

            .proker-list {
                margin-left: 0;
                margin-top: 20px;
                padding-top: 40px;
                margin-bottom: 40px;
                height: auto;
            }

            .card {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-stretch">
        <div class="card">
            <div class="card-body">
                <h2>Daftar Ormawa</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="id_club">Ormawa Dituju</label>
                        <select class="form-control" id="id_club" name="id_club" required>
                            <option value="">Select a Club</option>
                            @foreach($clubs as $club)
                            <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_division">Divisi Dituju</label>
                        <select class="form-control" id="id_division" name="id_division" required>
                            <option value="">Select a Division</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </form>
            </div>
        </div>

        <div class="proker-list">
            <h2 class="text-center mb-4">Daftar Proker</h2>
            @if($prokers->isEmpty())
            <p class="text-muted text-center">No prokers available.</p>
            @else
            @foreach($prokers as $clubId => $clubProkers)
            <h5>Ormawa: {{ $clubProkers->first()->club->name }}</h5>
            <ul class="list-group mb-3">
                @foreach($clubProkers as $proker)
                <li class="list-group-item">
                    {{ $proker->name }} - Tanggal Target: {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}
                </li>
                @endforeach
            </ul>
            @endforeach
            @endif
        </div>
    </div>
</body>

</html>