@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-4 mb-4">
        {{-- Ajukan Permohonan Baru --}}
        <div class="col-md-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="mdi mdi-file-plus-outline" style="font-size: 48px; color: #2196f3;"></i>
                    <h5 class="mt-3 mb-2 text-center">Ajukan Permohonan Baru</h5>
                    <p class="text-muted text-center mb-4">Klik tombol di bawah untuk membuat permohonan baru seperti
                        pemasangan, perbaikan, atau permintaan lainnya.</p>
                    <a href="#" class="btn btn-primary px-4 fw-semibold">
                        + Permohonan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- User Dashboard Mockup: Informasi Permohonan & Status -->
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-md-12 order-2 order-md-1">
                        <div class="card-body">
                            <h4 class="card-title pb-xl-2">Selamat datang <strong>{{ Auth::user()->name }}</strong> 👋</h4>
                            <p class="mb-2">Berikut rangkuman status permohonan terakhir Anda:</p>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-file-document-outline me-2"></i>No. Permohonan</span>
                                    <span class="fw-semibold">PMH2024-00123</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-calendar-check-outline me-2"></i>Tanggal Permohonan</span>
                                    <span>12 Juni 2024</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-checkbox-marked-circle-outline me-2"></i>Status
                                        Permohonan</span>
                                    <span class="badge bg-info">Diproses</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-file-outline me-2"></i>Jenis Permohonan</span>
                                    <span>Pemasangan Baru</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-clock-outline me-2"></i>Status Pembayaran</span>
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Lihat Detail Permohonan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mb-4">
        <!-- Riwayat Transaksi Permohonan -->
        <div class="col-md-12 col-lg-12">
            <div class="card h-100">
                <div class="card-header pb-1">
                    <h5 class="card-title mb-0">Riwayat Permohonan</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">PMH2024-00123</div>
                                    <div class="small text-muted">12 Juni 2024</div>
                                </div>
                                <span class="badge bg-info">Diproses</span>
                            </div>
                            <div class="small mt-1">Pemasangan Baru</div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">PMH2024-00087</div>
                                    <div class="small text-muted">28 Mei 2024</div>
                                </div>
                                <span class="badge bg-success">Selesai</span>
                            </div>
                            <div class="small mt-1">Perbaikan Meter</div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">PMH2024-00065</div>
                                    <div class="small text-muted">7 Mei 2024</div>
                                </div>
                                <span class="badge bg-danger">Ditolak</span>
                            </div>
                            <div class="small mt-1">Balik Nama</div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center p-2">
                    <a href="#" class="btn btn-link fw-semibold">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
        <!--/ Riwayat Transaksi Permohonan -->
    </div>
@endsection