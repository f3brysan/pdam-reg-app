@extends('layouts.main')

@section('title', 'Detail Permohonan')

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
                    @if (!$permohonan)
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
                                <p class="mb-0">
                                    {{ $permohonan->telepon }}
                                    @if ($permohonan->telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $permohonan->telepon) }}"
                                            target="_blank" style="text-decoration:none; margin-left: 6px;">
                                            <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                                        </a>
                                    @endif
                                </p>
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

            @if (!empty($permohonanBiling) && $permohonanBiling->is_valid == true)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Petugas Pemasangan</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($permohonanOfficer))
                            <div class="row">
                                <div class="col-md-12">
                                    <p> Petugas pemasangan : {{ optional($permohonanOfficer->petugas)->name }}
                                        <br> Jenis meteran : {{ optional($permohonanOfficer->msMeteran)->nama }}
                                        <br> Nomor meteran : {{ $permohonanOfficer->nomor_seri }}
                                        <br> Tanggal pasang :
                                        {{ \Carbon\Carbon::parse($permohonanOfficer->tgl_pasang)->locale('id')->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if ($permohonanOfficer->is_done == true)
                                <div class="row">
                                    <div class="col-md-12">
                                        <p> Hasil pemasangan : <span class="badge bg-success">Selesai</span>
                                        <br>
                                        Tanggal pemasangan : {{ \Carbon\Carbon::parse($permohonanOfficer->done_at)->locale('id')->translatedFormat('d F Y H:i:s') }} WIB</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <h5 class="text-muted mb-0">Belum ada petugas pemasangan.</h5>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Laporkan Hasil Pemasangan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p> Dokumen terlampir : {{ $officerDocuments->count() }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <button class="btn btn-sm btn-success" id="btn-upload-dokumen">
                                            <i class="fa fa-upload"></i> Upload Dokumen
                                        </button>
                                        @if ($officerDocuments->count() > 0 and $permohonanOfficer->is_done == false)
                                            <button class="btn btn-sm btn-primary" id="btn-laporkan-hasil-pemasangan">
                                                <i class="fa fa-check"></i> Laporkan Hasil Pemasangan
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Dokumen</th>
                                                    <th>Aksi</th>
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
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-danger" id="btn-delete-dokumen"
                                                                data-id="{{ $officerDocument->id }}" {{ $permohonanOfficer->is_done == true ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i> Hapus
                                                            </button>
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

    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="uploadImageForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadImageModalLabel">Unggah Gambar/Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">Pilih Gambar/Foto (bisa lebih dari satu):</label>
                            <input class="form-control" type="file" id="images" name="images[]" accept="image/*"
                                multiple required>
                            <div class="form-text text-muted">Format gambar: jpg, jpeg, png. Bisa pilih lebih dari satu
                                file.</div>
                        </div>
                        <div id="image-preview" class="mb-3 d-flex flex-wrap gap-1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Unggah</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
        $('#btn-upload-dokumen').on('click', function(e) {
            e.preventDefault();
            $('#uploadImageModal').modal('show');
        });

        $('#uploadImageForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('permohonan.upload-dokumen-teknisi', Crypt::encrypt($permohonan->id)) }}",
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function(response) {
                    swal.fire({
                        title: 'Dokumen berhasil diunggah',
                        text: 'Dokumen yang diunggah tidak dapat dikembalikan.',
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-success',
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                },
                error: function(xhr) {
                    swal.fire({
                        title: 'Gagal mengunggah dokumen',
                        text: xhr.responseText,
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                        }
                    })
                }
            });
        });

        $('#btn-delete-dokumen').on('click', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            swal.fire({
                title: 'Yakin ingin menghapus dokumen ini?',
                text: 'Dokumen yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('permohonan.delete-dokumen-teknisi', ':id') }}".replace(
                            ':id', id),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        type: 'DELETE',
                        success: function(response) {
                            swal.fire({
                                title: 'Dokumen berhasil dihapus',
                                text: 'Dokumen yang dihapus tidak dapat dikembalikan.',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    })
                }
            })
        })

        $('#btn-laporkan-hasil-pemasangan').on('click', function(e) {
            e.preventDefault();

            swal.fire({
                title: 'Yakin ingin laporkan hasil pemasangan?',
                text: 'Hasil pemasangan akan dikirim ke pimpinan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, laporkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-success',
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('permohonan.laporkan-hasil-pemasangan', Crypt::encrypt($permohonan->id)) }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        dataType: 'json',
                        success: function(response) {
                            swal.fire({
                                title: 'Hasil pemasangan berhasil dilaporkan',
                                text: 'Hasil pemasangan akan dikirim ke pimpinan.',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
                        },
                        error: function(xhr) {
                            swal.fire({
                                title: 'Gagal melaporkan hasil pemasangan',
                                text: xhr.responseText,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-danger',
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        })
    </script>
@endpush
