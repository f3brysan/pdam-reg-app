@extends('layouts.main')

@section('title', 'Detail permohonan')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            <h4 class="mb-0">Detail permohonan</h4>
            <a href="{{ route('permohonan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi permohonan</h5>
                </div>
                <div class="card-body">
                    @if (!$permohonan)
                        <div class="alert alert-warning mb-0">Data permohonan tidak ditemukan.</div>
                    @else
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor registrasi</label>
                                <p class="mb-0">{{ $permohonan->no_register }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal daftar</label>
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
                                <label class="form-label fw-semibold">Jenis tempat tinggal</label>
                                <p class="mb-0">{{ optional($permohonan->msJenisTempatTinggal)->nama }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <p class="mb-0">{{ $permohonan->alamat }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Nomor rumah</label>
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

            @if (!empty($permohonanBiling))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Bukti pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <h6>Nomor VA: {{ $permohonanBiling->no_va }}</h6>
                        <h6>Jumlah tagihan: Rp {{ number_format($permohonanBiling->price, 0, ',', '.') }}</h6>
                        @if ($permohonanBiling->path)
                            <h6>File bukti pembayaran</h6>
                            <a href="{{ asset('storage/' . $permohonanBiling->path) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">Lihat</a>

                            <div class="d-flex justify-content-end align-items-center">
                                @if ($permohonanBiling->is_valid == false)
                                    <button class="btn btn-sm btn-success" id="btn-verifikasi-pembayaran">
                                        <i class="fa fa-check"></i> Verifikasi Pembayaran
                                    </button>
                                @else
                                    <p class="small">Pembayaran sudah diverifikasi<br>Pada:
                                        {{ \Carbon\Carbon::parse($permohonanBiling->valid_at)->locale('id')->translatedFormat('d F Y H:i:s') }}
                                        <br>Diverifikasi oleh: {{ optional($permohonanBiling->validBy)->name }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <h6 class="text-danger">Bukti pembayaran belum diunggah</h6>
                        @endif
                    </div>
                </div>
            @endif

            @if (!empty($permohonanBiling) && $permohonanBiling->is_valid == true)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Penetapan petugas pemasangan</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($permohonanOfficer))
                            <p>Petugas pemasangan: {{ $permohonanOfficer->petugas ? $permohonanOfficer->petugas->name : 'User tidak ditemukan' }}
                                <br>Jenis meteran: {{ optional($permohonanOfficer->msMeteran)->nama }}
                                <br>Nomor meter: {{ $permohonanOfficer->nomor_seri }}
                                <br>Tanggal pasang:
                                {{ \Carbon\Carbon::parse($permohonanOfficer->tgl_pasang)->locale('id')->translatedFormat('d F Y') }}
                            </p>

                            @if ($permohonanOfficer->is_done == true)
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Hasil pemasangan: <span class="badge bg-success">Selesai</span>
                                            <br>
                                            Tanggal pemasangan:
                                            {{ \Carbon\Carbon::parse($permohonanOfficer->done_at)->locale('id')->translatedFormat('d F Y H:i:s') }}
                                            WIB
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <button class="btn btn-sm btn-success" id="btn-set-petugas-pemasangan">
                                <i class="fa fa-check"></i> Tetapkan petugas pemasangan
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            @if (!empty($permohonanOfficer) && $permohonanOfficer->is_done == true)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Laporan hasil pemasangan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Jumlah dokumen terlampir: {{ $officerDocuments->count() }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">                               
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Dokumen</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($officerDocuments as $officerDocument)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ asset('storage/' . $officerDocument->path) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('storage/' . $officerDocument->path) }}"
                                                                    alt="Dokumen"
                                                                    style="max-width: 80px; max-height: 80px;"
                                                                    class="img-thumbnail">
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
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dokumen terlampir</h5>
                </div>
                <div class="card-body">
                    @if ($permohonanDokumen->isEmpty())
                        <p class="text-muted mb-0">Belum ada dokumen.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($permohonanDokumen as $dokumen)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ optional($dokumen->msJenisDokumen)->nama }}</span>
                                    <a href="{{ asset('storage/' . $dokumen->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">Lihat</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lokasi pemasangan (peta)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-semibold">Lintang</label>
                            <p class="mb-0" id="latitude-value">{{ $permohonan->latitude }}</p>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-semibold">Bujur</label>
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

    <!-- Modal input nominal tagihan -->
    <div class="modal fade" id="modalInputHarga" tabindex="-1" aria-labelledby="modalInputHargaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-input-harga">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInputHargaLabel">Masukkan nominal tagihan permohonan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="input-no-pelanggan" class="form-label">No. Pelanggan</label>
                            <input type="text" class="form-control" id="input-no-pelanggan" name="no_pelanggan"
                                placeholder="Masukkan no pelanggan" required>
                        </div>
                   
                        <div class="mb-3">
                            <label for="input-harga" class="form-label">Nominal tagihan (Rp)</label>
                            <input type="text" class="form-control" id="input-harga" name="harga"
                                placeholder="Masukkan nominal tagihan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan nominal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalSetPetugasPemasangan" tabindex="-1"
        aria-labelledby="modalSetPetugasPemasanganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-set-petugas-pemasangan">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSetPetugasPemasanganLabel">Tetapkan petugas pemasangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Petugas</label>
                            <select class="form-select select2-modal" id="petugas-id" name="petugas_id" required>
                                <option value="">Pilih petugas</option>
                                @foreach ($officers as $officer)
                                    <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis meteran</label>
                            <select class="form-select select2-modal" id="ms-meteran-id" name="ms_meteran_id" required>
                                <option value="">Pilih jenis meteran</option>
                                @foreach ($msMeteran as $meteran)
                                    <option value="{{ $meteran->id }}">{{ $meteran->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal pasang</label>
                            <input type="date" class="form-control" id="tgl-pasang" name="tgl_pasang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor meter</label>
                            <input type="text" class="form-control" id="nomor-seri" name="nomor_seri"
                                placeholder="Masukkan nomor meter" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        $(document).ready(function() {
            $('#input-harga').mask('000.000.000.000', {
                reverse: true
            });
            $('.select2-modal').select2({
                dropdownParent: $('#modalSetPetugasPemasangan'),
                width: '100%'
            });
        });

        $('#btn-validasi-permohonan').click(function() {
            $('#modalInputHarga').modal('show');
        });

        $('#form-input-harga').submit(function(e) {
            e.preventDefault();

            $('#modalInputHarga').modal('hide');

            let harga = $('#input-harga').val();
            if (!harga || harga < 0) {
                Swal.fire('Kesalahan', 'Nominal tidak valid', 'error');
                return;
            }

            const formData = new FormData(this);

            Swal.fire({
                title: 'Validasi Permohonan',
                text: 'Apakah Anda yakin ingin validasi permohonan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memuat',
                        text: 'Memproses validasi permohonan...',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });

                    $.ajax({
                        url: '{{ route('permohonan.validasi', Crypt::encrypt($permohonan->id)) }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            Swal.close();
                            toastr.success('Permohonan berhasil divalidasi');

                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);

                            $("#modalInputHarga").modal("show");
                        }
                    })
                }
            })


        });

        $('#btn-verifikasi-pembayaran').click(function() {
            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: 'Apakah Anda yakin ingin verifikasi pembayaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memuat',
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
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Pembayaran berhasil diverifikasi',
                                icon: 'success',
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat verifikasi pembayaran',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });

        $('#btn-set-petugas-pemasangan').click(function() {
            $('#modalSetPetugasPemasangan').modal('show');
        });

        $('#form-set-petugas-pemasangan').submit(function(e) {
            e.preventDefault();

            // Disable the submit button to prevent double submission
            $(this).find('button[type="submit"]').prop('disabled', true);
            $(this).find('button[type="submit"]').text('Menyimpan...');
    
            $.ajax({
                url: "{{ route('permohonan.set-petugas-pemasangan', Crypt::encryptString($permohonan->id)) }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    petugas_id: $('#petugas-id').val(),
                    ms_meteran_id: $('#ms-meteran-id').val(),
                    tgl_pasang: $('#tgl-pasang').val(),
                    nomor_seri: $('#nomor-seri').val(),
                },
                dataType: 'json',
                success: function(response) {
                    $('#modalSetPetugasPemasangan').modal('hide');
                    Swal.fire({
                        title: 'Berhasil',
                        text: response.message,
                        icon: 'success',
                    }).then(function() {
                        location.reload();
                    });
                    $(this).find('button[type="submit"]').prop('disabled', false);
                    $(this).find('button[type="submit"]').text('Simpan');
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                    $(this).find('button[type="submit"]').prop('disabled', false);
                }
            });
        });
    </script>
@endpush
