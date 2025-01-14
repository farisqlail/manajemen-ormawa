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
            /* Mengatur agar item di dalam flex container bisa menyesuaikan lebar */
            align-items: center;
            /* Mengatur agar item di dalam flex container bisa menyesuaikan tinggi */
            height: 100vh;
            /* Mengatur tinggi body menjadi 100% dari viewport */
            margin: 0;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            flex-direction: row;
            /* Mengatur agar item ditampilkan dalam satu baris */
            justify-content: center;
            /* Mengatur agar item di dalam container bisa menyesuaikan lebar */
            align-items: flex-start;
            /* Mengatur agar item di dalam container bisa menyesuaikan tinggi */
            flex-wrap: wrap;
            /* Mengizinkan item untuk membungkus jika diperlukan */
        }

        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            /* Menambahkan margin untuk jarak antar elemen */
        }

        .proker-list {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            /* Menambahkan margin untuk jarak antar elemen */
            height: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .proker-list h5 {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 20px;
                /* Menambahkan padding atas untuk tampilan mobile */
                padding-bottom: 20px;
                /* Menambahkan padding bawah untuk tampilan mobile */
            }

            .card,
            .proker-list {
                margin: 10px 0;
                /* Mengatur margin untuk tampilan mobile */
            }
        }
    </style>
</head>

<body>
    <div class="container">
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
                        <label for="password">Password</label>
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
            <h2 class="text-center mb-4">Daftar Ormawa</h2>
            @if($prokers->isEmpty())
            <p class="text-muted text-center">Tidak ada proker yang tersedia.</p>
            @else
            <ul class="list-group mb-3">
                @foreach($prokers as $clubId => $clubProkers)
                @if($clubProkers->isNotEmpty())
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $clubProkers->first()->club->name }}</span>
                    <a href="{{ route('ormawa.profile', $clubProkers->first()->club->id) }}" class="btn btn-primary btn-sm">Lihat Ormawa</a>
                </li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</body>

</html>