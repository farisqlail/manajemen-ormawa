<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $club->name }} - Profil Ormawa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">{{ $club->name }}</h1>
        <div class="text-center">
            <img src="{{ asset('storage/' . $club->logo) }}" alt="Logo" class="img-fluid rounded-circle" style="max-width: 100px; height: auto;">
        </div>
        <p class="text-center">{!! $club->description !!}</p>

        <div class="mt-4">
            <h2>Daftar Proker</h2>
            @if($prokers->isEmpty())
            <p class="text-muted">Tidak ada proker yang tersedia.</p>
            @else
            <ul class="list-group mb-3">
                @foreach($prokers as $proker)
                <li class="list-group-item">
                    <strong>{{ $proker->name }}</strong> - Tanggal Target: {{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="mt-4">
            <h2>Daftar Aktivitas</h2>
            @if($activities->isEmpty())
            <p class="text-muted">Tidak ada aktivitas yang tersedia.</p>
            @else
            <ul class="list-group mb-3">
                @foreach($activities as $activity)
                <li class="list-group-item">
                    <strong>{{ $activity->name }}</strong> - {!! $activity->description !!}
                    <div class="me-3">
                        @foreach (json_decode($activity->photos) as $photo)
                        <img src="{{ Storage::url($photo) }}" width="200" height="200" alt="Photo">
                        @endforeach
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</body>

</html>