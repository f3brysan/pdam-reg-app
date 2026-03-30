@extends('layouts.main')

@section('title', 'Detail Permohonan')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #detail-map {
            height: 350px;
            width: 100%;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detail Permohonan</h4>
            <a href="{{ route('permohonan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Permohonan</h5>
                </div>
                <div class="card-body">
                    @if (! $permohonan)
                        <div class="alert alert-warning mb-0">Data permohonan tidak ditemukan.</div>
                    @else
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">No Register</label>
                                <p class="mb-0">{{ $permohonan->no_register }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Daftar</label>
                                <p class="mb-0">
                                    {{ \Carbon\Carbon::parse($permohonan->tgl_daftar)->locale('id')->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama</label>
                                <p class="mb-0">{{ $permohonan->nama }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">NIK</label>
                                <p class="mb-0">{{ $permohonan->nik }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Telepon</label>
                                <p class="mb-0">{{ $permohonan->telepon }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <p class="mb-0"><span class="badge bg-info">{{ $permohonan->status }}</span></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Pekerjaan</label>
                                <p class="mb-0">{{ optional($permohonan->msPekerjaan)->nama }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Tempat Tinggal</label>
                                <p class="mb-0">{{ optional($permohonan->msJenisTempatTinggal)->nama }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <p class="mb-0">{{ $permohonan->alamat }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Nomor Rumah</label>
                                <p class="mb-0">{{ $permohonan->nomor_rumah }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Kecamatan</label>
                                <p class="mb-0">{{ $permohonan->kecamatan }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Kelurahan</label>
                                <p class="mb-0">{{ $permohonan->kelurahan }}</p>
                            </div>

                        </div>
                        @if (empty($permohonanBiling))
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-sm btn-success" id="btn-validasi-permohonan">
                                        <i class="fa fa-check"></i> Validasi Permohonan
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            @if (! empty($permohonanBiling))
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <h6>Nomor VA : {{ $permohonanBiling->no_va }}</h6>
                        <h6>Harga : Rp. {{ number_format($permohonanBiling->price, 0, ',', '.') }}</h6>
                        @if ($permohonanBiling->path)
                            <h6>Bukti Pembayaran</h6>
                            <a href="{{ asset('storage/'.$permohonanBiling->path) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">Lihat</a>

                            <div class="d-flex justify-content-end align-items-center">
                                <button class="btn btn-sm btn-success ms-2" id="btn-verifikasi-pembayaran">Verifikasi
                                    Pembayaran</button>
                            </div>
                        @else
                            <h6 class="text-danger">Bukti Pembayaran belum diupload</h6>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dokumen Terlampir</h5>
                </div>
                <div class="card-body">
                    @if ($permohonanDokumen->isEmpty())
                        <p class="text-muted mb-0">Belum ada dokumen.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($permohonanDokumen as $dokumen)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ optional($dokumen->msJenisDokumen)->nama }}</span>
                                    <a href="{{ asset('storage/'.$dokumen->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">Lihat</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lokasi Pemasangan (Maps)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-semibold">Latitude</label>
                            <p class="mb-0" id="latitude-value">{{ $permohonan->latitude }}</p>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-semibold">Longitude</label>
                            <p class="mb-0" id="longitude-value">{{ $permohonan->longitude }}</p>
                        </div>
                    </div>
                    @if ($permohonan && $permohonan->latitude && $permohonan->longitude)
                        <div id="detail-map"></div>
                    @else
                        <p class="text-muted mb-0">Koordinat belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Harga -->
    <div class="modal fade" id="modalInputHarga" tabindex="-1" aria-labelledby="modalInputHargaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-input-harga">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInputHargaLabel">Input Harga Permohonan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="input-harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="input-harga" name="harga"
                                placeholder="Masukkan harga pemasangan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Harga</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latEl = document.getElementById('latitude-value');
            var lngEl = document.getElementById('longitude-value');
            var mapEl = document.getElementById('detail-map');

            if (!latEl || !lngEl || !mapEl) return;

            var lat = parseFloat(latEl.textContent);
            var lng = parseFloat(lngEl.textContent);

            if (isNaN(lat) || isNaN(lng)) return;

            var map = L.map('detail-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('Lokasi pemasangan')
                .openPopup();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#input-harga').mask('000.000.000.000', { reverse: true });
        });

        $('#btn-validasi-permohonan').click(function () {
            $('#modalInputHarga').modal('show');
        });

        $('#form-input-harga').submit(function (e) {
            e.preventDefault();

            $('#modalInputHarga').modal('hide');

            let harga = $('#input-harga').val();
            if (!harga || harga < 0) {
                Swal.fire('Error', 'Harga tidak valid', 'error');
                return;
            }

            Swal.fire({
                title: 'Validasi Permohonan',
                text: 'Apakah Anda yakin ingin validasi permohonan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading',
                        text: 'Memproses validasi permohonan...',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });

                    $.ajax({
                        url: '{{ route('permohonan.validasi', Crypt::encryptString($permohonan->id)) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            harga: harga,
                        },
                        success: function (response) {
                            Swal.close();
                            toastr.success('Permohonan berhasil divalidasi');

                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);

                            $("#modalInputHarga").modal("show");
                        }
                    })
                }
            })


        });

        $('#btn-verifikasi-pembayaran').click(function () {
            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: 'Apakah Anda yakin ingin verifikasi pembayaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading',
                        text: 'Memproses verifikasi pembayaran...',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });

                    $.ajax({
                        url: "{{ route('permohonan.verifikasi-pembayaran', Crypt::encryptString($permohonan->id)) }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Pembayaran berhasil diverifikasi',
                                icon: 'success',
                            }).then(function () {
                                location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Terjadi kesalahan saat verifikasi pembayaran',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush