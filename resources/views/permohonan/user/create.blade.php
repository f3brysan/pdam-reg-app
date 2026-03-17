@extends('layouts.main')

@section('title', 'Permohonan Baru')
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header pb-0">
                <ul class="nav nav-tabs card-header-tabs" id="permohonanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="persyaratan-tab" data-bs-toggle="tab"
                            data-bs-target="#persyaratan" type="button" role="tab" aria-controls="persyaratan"
                            aria-selected="true">
                            Persyaratan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="data-pemohon-tab" data-bs-toggle="tab" data-bs-target="#data-pemohon"
                            type="button" role="tab" aria-controls="data-pemohon" aria-selected="false">
                            Data Pemohon
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lokasi-tab" data-bs-toggle="tab" data-bs-target="#lokasi" type="button"
                            role="tab" aria-controls="lokasi" aria-selected="false">
                            Lokasi Pemasangan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen"
                            type="button" role="tab" aria-controls="dokumen" aria-selected="false">
                            Kebutuhan Dokumen
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="konfirmasi-tab" data-bs-toggle="tab" data-bs-target="#konfirmasi"
                            type="button" role="tab" aria-controls="konfirmasi" aria-selected="false">
                            Konfirmasi
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="permohonanTabContent">
                    <div class="tab-pane fade show active" id="persyaratan" role="tabpanel"
                        aria-labelledby="persyaratan-tab">
                        <!-- Persyaratan Content -->
                        <h5>Persyaratan Permohonan</h5>
                        <ul>
                            <li>Salinan KTP Pemohon</li>
                            <li>Surat Keterangan Domisili (jika diperlukan)</li>
                            <li>Surat Pernyataan / Pengantar RT/RW</li>
                            <li>Formulir Permohonan</li>
                            <li>Dokumen pendukung lainnya</li>
                        </ul>
                        <div class="mt-3">
                            <button class="btn btn-primary" type="button"
                                onclick="nextTab('data-pemohon-tab')">Lanjut</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="data-pemohon" role="tabpanel" aria-labelledby="data-pemohon-tab">
                        <!-- Data Pemohon Content -->
                        <h5>Data Pemohon</h5>
                        <form id="data-pemohon-form">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" maxlength="16" pattern="\d{1,16}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,16);">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor WA</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                            <div class="mb-3">
                                <label for="jenis-tempat-tinggal" class="form-label">Jenis Tempat Tinggal</label>
                                <select class="form-select" id="jenis-tempat-tinggal" name="jenis-tempat-tinggal">
                                    <option value="">Pilih</option>
                                    @foreach ($msJenisTempatTinggal as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <select class="form-select" id="pekerjaan" name="pekerjaan">
                                    <option value="">Pilih</option>
                                    @foreach ($msPekerjaan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Banyak Kran</label>
                                <div id="pipes-container">
                                    <div class="input-group mb-2 pipe-row">
                                        <input type="text" class="form-control" name="jumlah_kran" placeholder="Masukkan jumlah kran" min="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                            </div>                            
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <button class="btn btn-secondary me-2" type="button"
                                    onclick="prevTab('persyaratan-tab')">Sebelumnya</button>
                                <button class="btn btn-primary" type="button"
                                    onclick="nextTab('lokasi-tab', 'submit-data-pemohon-form')">Lanjut</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="lokasi" role="tabpanel" aria-labelledby="lokasi-tab">
                        <!-- Lokasi Pemasangan Content -->
                        <h5>Lokasi Pemasangan</h5>
                        <form>
                            <div class="mb-3">
                                <label for="tgl_daftar" class="form-label">Tanggal Daftar</label>
                                <input type="date" class="form-control" id="tgl_daftar" name="tgl_daftar" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_rumah" class="form-label">Nomor Rumah</label>
                                <input type="text" class="form-control" id="nomor_rumah" name="nomor_rumah">
                            </div>
                            <div class="mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan">
                            </div>
                            <div class="mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <input type="text" class="form-control" id="kelurahan" name="kelurahan">
                            </div>
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" readonly class="form-control"
                                    value="{{ old('latitude', $data->latitude ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" readonly class="form-control"
                                    value="{{ old('longitude', $data->longitude ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Lokasi</label>
                                <div id="map" style="height: 400px; border-radius: 8px;"></div>
                            </div>
                            <button class="btn btn-secondary" type="button"
                                onclick="prevTab('data-pemohon-tab')">Sebelumnya</button>
                            <button class="btn btn-primary" type="button" onclick="nextTab('dokumen-tab')">Lanjut</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                        <!-- Kebutuhan Dokumen Content -->
                        <h5>Kebutuhan Dokumen</h5>
                        <form>
                            <div class="mb-3">
                                <label for="ktp" class="form-label">Upload KTP</label>
                                <input type="file" class="form-control" id="ktp" name="ktp">
                            </div>
                            <div class="mb-3">
                                <label for="pengantar" class="form-label">Upload Surat Pengantar</label>
                                <input type="file" class="form-control" id="pengantar" name="pengantar">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen-lainnya" class="form-label">Dokumen Lainnya</label>
                                <input type="file" class="form-control" id="dokumen-lainnya" name="dokumen-lainnya">
                            </div>
                            <button class="btn btn-secondary" type="button"
                                onclick="prevTab('lokasi-tab')">Sebelumnya</button>
                            <button class="btn btn-primary" type="button"
                                onclick="nextTab('konfirmasi-tab')">Lanjut</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="konfirmasi" role="tabpanel" aria-labelledby="konfirmasi-tab">
                        <!-- Konfirmasi Content -->
                        <h5>Konfirmasi Permohonan</h5>
                        <p>Pastikan semua data sudah benar sebelum mengirim permohonan.</p>
                        <!-- Ringkasan data dapat ditambahkan di sini -->
                        <button class="btn btn-secondary" type="button" onclick="prevTab('dokumen-tab')">Sebelumnya</button>
                        <button class="btn btn-success" type="submit">Kirim Permohonan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- leaflet map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map;
        let marker;

        document.addEventListener('DOMContentLoaded', function () {
            let defaultLat = parseFloat(document.getElementById('latitude').value) || -7.6493413;
            let defaultLng = parseFloat(document.getElementById('longitude').value) || 111.3381593;

            map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
            }

            marker.on('dragend', function () {
                let position = marker.getLatLng();
                updateInputs(position.lat, position.lng);

                reverseGeocode(position.lat, position.lng);
            });

            map.on('click', function (e) {
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                marker.setLatLng([lat, lng]);
                updateInputs(lat, lng);

                reverseGeocode(lat, lng);
            });

            // kalau tab lokasi aktif saat load awal
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        });

        document.getElementById('lokasi-tab').addEventListener('shown.bs.tab', function () {
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        });

        async function reverseGeocode(lat, lng) {
            try {
                const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

                const res = await fetch(url);
                const data = await res.json();

                console.log(data);

                const address = data.address;

                document.getElementById('kelurahan').value =
                    address.village ||
                    address.suburb ||
                    address.hamlet ||
                    address.neighbourhood ||
                    '';

                document.getElementById('kecamatan').value =
                    address.village ||
                    address.municipality ||
                    address.subdistrict ||
                    address.city_district ||
                    address.county ||
                    '';

            } catch (err) {
                console.error(err);
            }
        }
    </script>

    <script>
        function nextTab(tabId, submitFormId) {
            switch (submitFormId) {
                case 'submit-data-pemohon-form':
                    submitForm('data-pemohon-form');
                    break;
                default:
                    break;
            }

            var el = document.getElementById(tabId);
            if (el) el.click();



        }
        function prevTab(tabId, submitFormId) {
            var el = document.getElementById(tabId);
            if (el) el.click();
        }

        function submitForm(formId) {
            const form = document.getElementById(formId);
            if (!form) {
                console.error('Form not found:', formId);
                return null;
            }
            const formData = new FormData(form);

            $.ajax({
                url: "{{ route('permohonan.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error(xhr.responseJSON.message);
                }
            })
        }
    </script>
@endpush