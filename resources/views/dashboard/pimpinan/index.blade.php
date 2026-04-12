@extends('layouts.main')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="row gy-4 mb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">Ringkasan eksekutif</h4>
                    <p class="mb-0 text-muted">Halo, <strong>{{ Auth::user()->name }}</strong> — berikut agregat pelanggan
                        (permohonan) dan pemasangan dari data sistem.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="d-block text-muted small">Total permohonan</span>
                    <h3 class="mb-0 mt-2">{{ number_format($totalPermohonan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="d-block text-muted small">Sudah ada no. pelanggan</span>
                    <h3 class="mb-0 mt-2 text-primary">{{ number_format($pelangganBermeter, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="d-block text-muted small">Selesai / pasang selesai</span>
                    <h3 class="mb-0 mt-2 text-success">{{ number_format($statusSelesai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="d-block text-muted small">Antrian pemasangan</span>
                    <h3 class="mb-0 mt-2 text-warning">{{ number_format($antrianPemasangan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card h-100 border-start border-primary border-3">
                <div class="card-body">
                    <span class="d-block text-muted small">Pemasangan selesai (total)</span>
                    <h3 class="mb-0 mt-2">{{ number_format($totalPemasanganSelesai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card h-100 border-start border-info border-3">
                <div class="card-body">
                    <span class="d-block text-muted small">Pemasangan selesai (bulan ini)</span>
                    <h3 class="mb-0 mt-2">{{ number_format($pemasanganSelesaiBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Permohonan vs pemasangan selesai</h5>
                    <span class="badge bg-label-secondary">6 bulan terakhir</span>
                </div>
                <div class="card-body">
                    <div id="chartPimpinanBulanan"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Sebaran status permohonan</h5>
                </div>
                <div class="card-body">
                    @if ($statusDistribution->isEmpty())
                        <p class="text-muted mb-0">Belum ada data permohonan.</p>
                    @else
                        <div id="chartPimpinanStatus"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="card-title mb-0">Data pelanggan / pemohon</h5>
                        <small class="text-muted">100 entri terbaru berdasarkan pembaruan data. Gunakan pencarian tabel
                            untuk memfilter.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-pimpinan-pelanggan">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>No. register</th>
                                    <th>No. pelanggan</th>
                                    <th>Nama</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th class="text-center">Tgl. daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarPelanggan as $row)
                                    <tr>
                                        <td class="text-end">{{ $loop->iteration }}</td>
                                        <td>{{ $row->no_register ?? '—' }}</td>
                                        <td>{{ $row->no_pelanggan ?? '—' }}</td>
                                        <td>{{ $row->nama ?? '—' }}</td>
                                        <td>{{ $row->kecamatan ?? '—' }}</td>
                                        <td>{{ $row->kelurahan ?? '—' }}</td>
                                        <td>{{ $row->telepon ?? '—' }}</td>
                                        <td><span class="badge bg-label-info">{{ $row->status ?? '—' }}</span></td>
                                        <td class="text-center">
                                            @if ($row->tgl_daftar)
                                                {{ \Carbon\Carbon::parse($row->tgl_daftar)->locale('id')->translatedFormat('d M Y') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('table-pimpinan-pelanggan')) {
                $('#table-pimpinan-pelanggan').DataTable({
                    order: [
                        [0, 'asc']
                    ],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ baris',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_',
                        infoEmpty: 'Tidak ada data',
                        zeroRecords: 'Tidak ada data yang cocok',
                        paginate: {
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        }
                    },
                    pageLength: 10
                });
            }

            const labels = @json($chartLabels);
            const permohonan = @json($chartPermohonan);
            const pemasangan = @json($chartPemasangan);

            if (document.querySelector('#chartPimpinanBulanan') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.querySelector('#chartPimpinanBulanan'), {
                    chart: {
                        type: 'bar',
                        height: 340,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                            name: 'Permohonan (baru)',
                            data: permohonan
                        },
                        {
                            name: 'Pemasangan selesai',
                            data: pemasangan
                        }
                    ],
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '55%'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: labels
                    },
                    colors: ['#26c6da', '#1e88e5'],
                    legend: {
                        position: 'top'
                    },
                    grid: {
                        borderColor: '#eee'
                    }
                }).render();
            }

            @if ($statusDistribution->isNotEmpty())
                const statusLabels = @json($statusDistribution->map(fn($r) => $r->status ?: 'Tanpa status')->values());
                const statusSeries = @json($statusDistribution->pluck('total')->values());

                if (document.querySelector('#chartPimpinanStatus') && typeof ApexCharts !== 'undefined') {
                    new ApexCharts(document.querySelector('#chartPimpinanStatus'), {
                        chart: {
                            type: 'donut',
                            height: 320
                        },
                        labels: statusLabels,
                        series: statusSeries,
                        legend: {
                            position: 'bottom',
                            fontSize: '12px'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '68%'
                                }
                            }
                        },
                        colors: ['#00bcd4', '#1e88e5', '#43a047', '#fb8c00', '#8e24aa', '#546e7a', '#78909c']
                    }).render();
                }
            @endif
        });
    </script>
@endpush
