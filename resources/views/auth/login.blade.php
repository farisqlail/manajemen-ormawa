<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistem Ormawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #1cc88a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-wrap: wrap;
        }

        .login-form {
            flex: 1 1 400px;
            padding: 2rem;
        }

        .side-panel {
            flex: 1 1 600px;
            background-color: #f4f4f4;
            padding: 2rem;
            overflow-y: auto;
        }

        .side-panel h2 {
            color: #4e73df;
        }

        .ormawa-list .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        {{-- Login Form --}}
        <div class="login-form">
            <h2 class="text-center mb-4 text-primary fw-bold">Login Sistem</h2>

            {{-- Alert error validasi --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Alert pesan status (informasi) --}}
            @if(session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus value="{{ old('email') }}">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>

        {{-- Side Panel: Daftar Ormawa --}}
        <div class="side-panel">
            <h2 class="mb-4">Daftar Ormawa</h2>
            @if($daftarProker->isEmpty())
            <p class="text-muted">Belum ada data ormawa.</p>
            @else
            <ul class="list-group ormawa-list">
                @foreach($daftarProker as $idOrmawa => $prokerOrmawa)
                @if($prokerOrmawa->isNotEmpty())
                <li class="list-group-item">
                    <span>{{ $prokerOrmawa->first()->club->name }}</span>
                    <a href="{{ route('ormawa.profile', $prokerOrmawa->first()->club->id) }}" class="btn btn-outline-primary btn-sm">
                        Lihat Profil
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>