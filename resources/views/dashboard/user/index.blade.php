@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-4 mb-4">
        <!-- User Dashboard Mockup: Informasi Permohonan & Status -->
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-md-12 order-2 order-md-1">
                    
                        @if ($permohonanTransactions)
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
                        @else
                            <div class="card-body">
                                <h4 class="card-title pb-xl-2">Selamat datang <strong>{{ Auth::user()->name }}</strong> 👋</h4>
                                <p class="mb-2">Anda belum memiliki permohonan</p>
                                <p>Silahkan lakukan permohonan pemasangan baru</p>
                                <a href="{{ route('permohonan.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-plus-circle-outline me-2"></i>
                                    Permohonan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection