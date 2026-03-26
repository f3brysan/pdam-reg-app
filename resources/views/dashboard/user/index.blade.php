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
                                <div class="row gy-3 justify-content-center">
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Verifikasi Petugas</span>
                                            @if(isset($permohonanTransactions) && ($permohonanTransactions->status == 'DIVERIFIKASI' || $permohonanTransactions->status == 'DISETUJUI' || $permohonanTransactions->status == 'SELESAI' || $permohonanTransactions->status == 'DIBAYAR' || $permohonanTransactions->status == 'PEMASANGAN'))
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @else
                                                <i class="mdi mdi-timer-sand text-warning fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Pembayaran</span>
                                            @if(isset($permohonanTransactions) && ($permohonanTransactions->status == 'DIBAYAR' || $permohonanTransactions->status == 'PEMASANGAN' || $permohonanTransactions->status == 'SELESAI'))
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @else
                                                <i class="mdi mdi-close-circle text-danger fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Pemasangan</span>
                                            @if(isset($permohonanTransactions) && ($permohonanTransactions->status == 'PEMASANGAN' || $permohonanTransactions->status == 'SELESAI'))
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @else
                                                <i class="mdi mdi-close-circle text-danger fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Selesai</span>
                                            @if(isset($permohonanTransactions) && $permohonanTransactions->status == 'SELESAI')
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @else
                                                <i class="mdi mdi-close-circle text-danger fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-file-document-outline me-2"></i>No. Permohonan</span>
                                            <span class="fw-semibold">{{ $permohonanTransactions->no_register }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-calendar-check-outline me-2"></i>Tanggal Permohonan</span>
                                            <span>{{ \Carbon\Carbon::parse($permohonanTransactions->tgl_daftar)->locale('id')->translatedFormat('d F Y') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-checkbox-marked-circle-outline me-2"></i>Status
                                                Permohonan</span>
                                            <span class="badge bg-info">{{ $permohonanTransactions->status }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-file-outline me-2"></i>Jenis Permohonan</span>
                                            <span>Sambungan Baru</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-clock-outline me-2"></i>Status Pembayaran</span>
                                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-semibold my-3">Dokumen yang Diupload</h6>
                                        <ul class="list-group list-group-flush">
                                            @if(isset($dokumenPendukung) && $dokumenPendukung->count() > 0)
                                                @foreach($dokumenPendukung as $dokumen)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="mdi mdi-file-check-outline me-2"></i>
                                                            {{ $dokumen->nama }}
                                                        </span>
                                                        @if(! empty($dokumen->path))
                                                            <a href="{{ asset('storage/'.$dokumen->path) }}" target="_blank"
                                                                class="btn btn-sm btn-outline-success">
                                                                <i class="mdi mdi-eye"></i> Lihat
                                                            </a>
                                                        @endif
                                                        @if(! empty($dokumen->file_name))
                                                            <span class="badge bg-secondary">{{ $dokumen->file_name }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">Tidak ada dokumen diupload.</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
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