@extends('layouts.front')

@section('title', 'Perumdam Lawu Tirta Magetan')

@push('styles')
    <style>
        :root {
            --water-50: #f0f9ff;
            --water-100: #e0f2fe;
            --water-200: #bae6fd;
            --water-300: #7dd3fc;
            --water-400: #38bdf8;
            --water-500: #0ea5e9;
            --water-600: #0284c7;
            --water-700: #0369a1;
            --water-800: #075985;
            --ink: #0c4a6e;
        }

        .front-body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(180deg, var(--water-50) 0%, #fff 28%);
            color: var(--ink);
            min-height: 100vh;
        }

        .navbar-front {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--water-200);
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(145deg, var(--water-300), var(--water-600));
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.25);
        }

        .hero-strip {
            background: radial-gradient(1200px 400px at 10% -20%, var(--water-200), transparent),
                radial-gradient(800px 300px at 90% 0%, var(--water-100), transparent);
        }

        .stat-card {
            border: 1px solid var(--water-200);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 40px rgba(7, 89, 133, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 48px rgba(7, 89, 133, 0.1);
        }

        .stat-value {
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            font-weight: 700;
            color: var(--water-700);
            letter-spacing: -0.02em;
        }

        .carousel-hero .carousel-item {
            min-height: 280px;
        }

        @media (min-width: 768px) {
            .carousel-hero .carousel-item {
                min-height: 320px;
            }
        }

        .carousel-caption-custom {
            left: 8%;
            right: 8%;
            bottom: auto;
            top: 50%;
            transform: translateY(-50%);
            text-align: left;
            padding: 0;
        }

        .carousel-hero .carousel-control-prev,
        .carousel-hero .carousel-control-next {
            width: 48px;
            height: 48px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            opacity: 1;
        }

        .carousel-hero .carousel-control-prev-icon,
        .carousel-hero .carousel-control-next-icon {
            filter: brightness(0) invert(1);
        }

        .section-title {
            font-weight: 700;
            color: var(--water-800);
        }

        .table-hours {
            --bs-table-bg: transparent;
        }

        .table-hours tbody tr {
            border-color: var(--water-200);
        }

        .footer-front {
            background: linear-gradient(180deg, var(--water-100) 0%, var(--water-200) 100%);
            border-top: 1px solid var(--water-300);
        }

        .btn-water {
            background: linear-gradient(135deg, var(--water-500), var(--water-700));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
        }

        .btn-water:hover {
            color: #fff;
            filter: brightness(1.05);
        }

        .btn-outline-water {
            border: 2px solid var(--water-500);
            color: var(--water-700);
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-outline-water:hover {
            background: var(--water-100);
            color: var(--water-800);
        }
    </style>
@endpush

@section('content')
    <nav class="navbar navbar-expand-lg navbar-front sticky-top">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('index') }}">
                <span class="brand-badge">
                    <img src="{{ asset('assets/img/pdam-logo.jpg') }}" alt="Logo PDAM" style="height: 38px; width: auto; border-radius: 8px; box-shadow: 0 2px 6px #0001;">
                </span>
           
                <span class="fw-bold text-dark lh-sm">Perumdam Lawu Tirta Magetan<br><small class="fw-normal text-muted"
                        style="font-size: 0.7rem;">Kabupaten Magetan</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navFront"
                aria-controls="navFront" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navFront">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link fw-medium" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#statistik">Statistik</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#jam">Jam Kerja</a></li>
                    <li class="nav-item pt-2 pt-lg-0">
                        <a class="btn btn-outline-water btn-sm" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item pt-2 pt-lg-0">
                        <a class="btn btn-water btn-sm" href="{{ route('register') }}">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-strip py-4 py-lg-5">
        <div class="container">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-lg-6">
                    <p class="text-uppercase small fw-semibold mb-2" style="color: var(--water-600); letter-spacing: 0.08em;">
                        Perusahaan Daerah Air Minum
                    </p>
                    <h1 class="display-5 fw-bold mb-3" style="color: var(--water-800);">
                        Melayani kebutuhan air bersih warga Magetan
                    </h1>
                    <p class="lead text-muted mb-4">
                        Informasi layanan, jam operasional kantor, dan ringkasan data pelanggan — dalam satu halaman.
                        Angka statistik berikut bersifat ilustrasi; sesuaikan dengan data resmi PDAM Anda.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-water" href="{{ route('register') }}">Ajukan pasang baru</a>
                        <a class="btn btn-outline-water" href="#jam">Lihat jam layanan</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="heroCarousel" class="carousel slide carousel-hero shadow rounded-4 overflow-hidden"
                        data-bs-ride="carousel" data-bs-interval="6000">
                        <div class="carousel-indicators">
                            @foreach ($slides as $i => $_)
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}"
                                    class="{{ $i === 0 ? 'active' : '' }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $i + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach ($slides as $i => $slide)
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <div class="d-block w-100 p-5 text-white position-relative"
                                        style="background: {{ $slide['gradient'] }};">
                                        <div class="carousel-caption-custom text-white">
                                            <h2 class="h3 fw-bold mb-3">{{ $slide['judul'] }}</h2>
                                            <p class="mb-0 opacity-90">{{ $slide['teks'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Berikutnya</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="statistik" class="py-5">
        <div class="container">
            <h2 class="section-title h4 mb-4">Ringkasan pelanggan & layanan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-card p-4 h-100">
                        <div class="text-muted small fw-semibold text-uppercase mb-2">Pelanggan aktif</div>
                        <div class="stat-value">{{ number_format($stats['pelanggan_aktif'], 0, ',', '.') }}</div>
                        <p class="text-muted small mb-0 mt-2">Estimasi sambungan aktif di wilayah pelayanan (contoh data statis).</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-4 h-100">
                        <div class="text-muted small fw-semibold text-uppercase mb-2">Sambungan baru (tahun ini)</div>
                        <div class="stat-value">{{ number_format($stats['sambungan_baru_tahun_ini'], 0, ',', '.') }}</div>
                        <p class="text-muted small mb-0 mt-2">Ilustrasi pencapaian program perluasan jaringan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-4 h-100">
                        <div class="text-muted small fw-semibold text-uppercase mb-2">Wilayah layanan</div>
                        <div class="stat-value">{{ number_format($stats['wilayah_layanan'], 0, ',', '.') }}</div>
                        <p class="text-muted small mb-0 mt-2">Kecamatan / zona pelayanan (contoh angka).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jam" class="py-5 bg-white border-top border-bottom" style="border-color: var(--water-200) !important;">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-lg-5">
                    <h2 class="section-title h4 mb-3">Jam Aktif</h2>
                {{--  TODO: Tambahkan jam kerja kantor --}}
                </div>
                <div class="col-lg-7">
                    <div class="table-responsive rounded-3 border" style="border-color: var(--water-200) !important;">
                        <table class="table table-hours mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Hari</th>
                                    <th class="py-3">Jam operasional</th>
                                    <th class="pe-4 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jam_kerja as $row)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $row['hari'] }}</td>
                                        <td>{{ $row['jam'] }}</td>
                                        <td class="pe-4 text-muted small">{{ $row['keterangan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-5">
        <div class="container">
            <h2 class="section-title h4 mb-4">Layanan digital</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 h-100" style="background: var(--water-100); border: 1px solid var(--water-200);">
                        <h3 class="h6 fw-bold mb-2">Permohonan pasang baru</h3>
                        <p class="text-muted small mb-3">Daftar akun dan lengkapi data permohonan melalui sistem registrasi.</p>
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color: var(--water-700);">Mulai daftar →</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 h-100" style="background: #fff; border: 1px solid var(--water-200);">
                        <h3 class="h6 fw-bold mb-2">Pantau status permohonan</h3>
                        <p class="text-muted small mb-3">Masuk ke akun Anda untuk melihat tahapan verifikasi hingga pemasangan.</p>
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: var(--water-700);">Masuk ke akun →</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 h-100" style="background: var(--water-100); border: 1px solid var(--water-200);">
                        <h3 class="h6 fw-bold mb-2">Informasi & pengaduan</h3>
                        <p class="text-muted small mb-3">Untuk pengaduan teknis atau billing, gunakan kanal resmi kantor PDAM.</p>
                        <span class="text-muted small">Telepon / loket — sesuaikan dengan kontak resmi.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-front py-5 mt-auto">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <strong class="d-block" style="color: var(--water-800);">Perumdam Lawu Tirta Magetan</strong>
                    <span class="text-muted small">Halaman depan aplikasi registrasi pelanggan. Tampilan dapat disesuaikan dengan identitas visual resmi.</span>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="small text-muted">© {{ date('Y') }} PDAM Magetan</span>
                </div>
            </div>
        </div>
    </footer>
@endsection
