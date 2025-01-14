<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: stretch;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .proker-list {
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
            overflow-y: auto;
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
            .proker-list {
                margin-left: 0;
                padding-top: 40px;
                margin-bottom: 40px;
            }

            .card {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <div class="mt-3" align="center">
                        <a href="/register">Daftar ormawa</a>
                    </div>
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