@extends('layouts.main')

@section('title', 'Dashboard Teknisi')

@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">Halo, <strong>{{ Auth::user()->name }}</strong> 👋</h4>
                    <p class="mb-0">Ringkasan pekerjaan pemasangan Anda ditampilkan di bawah ini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Total Pemasangan</span>
                    <h3 class="mt-2 mb-0">{{ $totalPemasangan }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Sudah Selesai</span>
                    <h3 class="mt-2 mb-0 text-success">{{ $totalSelesai }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Antrian Selanjutnya</span>
                    <h3 class="mt-2 mb-0 text-warning">{{ $totalAntrian }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Jadwal Hari Ini</span>
                    <h3 class="mt-2 mb-0 text-info">{{ $jadwalHariIni }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jadwal Terdekat</h5>
                </div>
                <div class="card-body">
                    @if ($jadwalTerdekat)
                        <p class="mb-1"><strong>No. Register:</strong> {{ optional($jadwalTerdekat->permohonanTransaction)->no_register ?? '-' }}</p>
                        <p class="mb-1"><strong>Nama Pemohon:</strong> {{ optional($jadwalTerdekat->permohonanTransaction)->nama ?? '-' }}</p>
                        <p class="mb-1"><strong>Tanggal Pasang:</strong> {{ \Carbon\Carbon::parse($jadwalTerdekat->tgl_pasang)->locale('id')->translatedFormat('d F Y') }}</p>
                        <p class="mb-0"><strong>Alamat:</strong> {{ optional($jadwalTerdekat->permohonanTransaction)->alamat ?? '-' }}</p>
                    @else
                        <p class="text-muted mb-0">Belum ada jadwal pemasangan berikutnya.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Pemasangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-teknisi">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>No. Register</th>
                                    <th>Nama Pemohon</th>
                                    <th>Tanggal Pasang</th>
                                    <th>Jenis Meteran & Nomor Seri</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarPemasangan as $item)
                                    <tr>
                                        <td class="text-end">{{ $loop->iteration }}</td>
                                        <td>{{ optional($item->permohonanTransaction)->no_register ?? '-' }}</td>
                                        <td>{{ optional($item->permohonanTransaction)->nama ?? '-' }}</td>
                                        <td class="text-center">{{ ! empty($item->tgl_pasang) ? \Carbon\Carbon::parse($item->tgl_pasang)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                        <td>Meteran: {{ optional($item->msMeteran)->nama ?? '-' }} <br> Nomor Seri: {{ $item->nomor_seri ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($item->is_done)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Dalam Antrian</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('permohonan.show', Crypt::encrypt($item->id)) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
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

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#table-teknisi').DataTable({
                pageLength: 10,
                order: [],
                language: {
                    emptyTable: 'Belum ada data pemasangan.'
                }
            });
        });
    </script>
@endpush
