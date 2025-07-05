<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ormawa->name }} - Profil Ormawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .club-header {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            color: white;
            padding: 2rem 1rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            border-left: 5px solid #4e73df;
            padding-left: 10px;
            margin-top: 3rem;
            margin-bottom: 1rem;
        }

        .activity-img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin-right: 0.5rem;
        }

        @media(max-width: 768px) {
            .logo-img {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="club-header text-center">
            <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo" class="logo-img mb-3">
            <h1 class="fw-bold">{{ $ormawa->name }}</h1>
            <p class="mt-3">{!! $ormawa->description !!}</p>
        </div>

        <div class="section-title">
            <h3 class="fw-semibold">📌 Daftar Proker</h3>
        </div>
        @if($daftarProker->isEmpty())
            <p class="text-muted">Belum ada program kerja.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($daftarProker as $proker)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $proker->name }}</h5>
                                <p class="card-text text-muted">
                                    Target Event: <span class="badge bg-info text-dark">{{ \Carbon\Carbon::parse($proker->target_event)->format('d M Y') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="section-title">
            <h3 class="fw-semibold">🎯 Daftar Aktivitas</h3>
        </div>
        @if($daftarKegiatan->isEmpty())
            <p class="text-muted">Belum ada aktivitas tercatat.</p>
        @else
            <div class="row g-3">
                @foreach($daftarKegiatan as $kegiatan)
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $kegiatan->name }}</h5>
                                <p class="card-text">{!! $kegiatan->description !!}</p>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @foreach (json_decode($kegiatan->photos) as $photo)
                                        <img src="{{ Storage::url($photo) }}" class="activity-img" style="width: 100px; height: 100px;" alt="Photo">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
