@extends('layouts.main')

@section('title', 'Laporan Pemasangan')

@section('content')
    <div class="row gy-4 mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">Laporan pemasangan</h4>
                    <p class="mb-0 text-muted">Filter data berdasarkan rentang tanggal pemasangan dan kecamatan pemohon.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filter</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan-pemasangan.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-3">
                                <label for="tgl_permohonan_from" class="form-label">Tgl permohonan (dari)</label>
                                <input type="date" class="form-control" id="tgl_permohonan_from" name="tgl_permohonan_from"
                                    value="{{ request('tgl_permohonan_from') }}">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="tgl_permohonan_to" class="form-label">Tgl permohonan (sampai)</label>
                                <input type="date" class="form-control" id="tgl_permohonan_to" name="tgl_permohonan_to"
                                    value="{{ request('tgl_permohonan_to') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="kecamatan" class="form-label">Kecamatan pemohon</label>
                                <select class="form-select" id="kecamatan" name="kecamatan">
                                    <option value="">Semua kecamatan</option>
                                    @foreach ($kecamatanList as $kec)
                                        <option value="{{ $kec }}" @selected(request('kecamatan') === $kec)>
                                            {{ $kec }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                                <a href="{{ route('laporan-pemasangan.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    <a class="btn btn-outline-danger"
                                        href="{{ route('laporan-pemasangan.export.pdf', request()->query()) }}" target="_blank">
                                        Unduh PDF
                                    </a>
                                    <a class="btn btn-outline-success"
                                        href="{{ route('laporan-pemasangan.export.excel', request()->query()) }}" target="_blank">
                                        Unduh Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="card-title mb-0">Daftar pemasangan</h5>
                        <small class="text-muted">
                            Total: <strong>{{ number_format($permohonanTransactions->count(), 0, ',', '.') }}</strong> data
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-laporan-pemasangan">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>                                    
                                    <th class="text-center">Tgl permohonan</th>
                                    <th class="text-center">Merk WM</th>
                                    <th class="text-center">Nomor WM</th>
                                    <th class="text-center">No. pelanggan</th>
                                    <th class="text-center">Nama pemohon</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Kecamatan</th>
                                    <th class="text-center">Petugas</th>
                                    <th class="text-center">Nominal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permohonanTransactions as $row)
                                    <tr>
                                        <td class="text-end">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            @if ($row->tgl_daftar)
                                                {{ \Carbon\Carbon::parse($row->tgl_daftar)->locale('id')->translatedFormat('d-m-Y') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $row->msMeteran->nama ?? '—' }}</td>
                                        <td>{{ $row->nomor_seri ?? '—' }}</td>
                                        <td>{{ $row->no_pelanggan ?? '—' }}</td>
                                        <td>{{ $row->nama ?? '—' }}</td>    
                                        <td>{{ $row->alamat ?? '—' }}</td>
                                        <td>{{ $row->kecamatan ?? '—' }}</td>
                                        <td>{{ optional(optional($row->permohonanOfficer)->petugas)->name ?? '—' }}</td>
                                        <td class="text-end">Rp.&nbsp;{{ number_format(optional($row->permohonanBiling)->price ?? 0, 0, ',', '.') ?? '—' }}</td>
                                        <td class="text-center">
                                            @if (!empty($row->status))
                                                <span class="badge bg-label-info">{{ $row->status }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada data untuk filter ini.</td>
                                    </tr>
                                @endforelse
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
            if (document.getElementById('table-laporan-pemasangan')) {
                $('#table-laporan-pemasangan').DataTable({
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
        });
    </script>
@endpush

