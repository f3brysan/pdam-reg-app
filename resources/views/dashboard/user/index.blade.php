@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-4 mb-4">
        <!-- User Dashboard Mockup: Informasi Permohonan & Status -->
        <div class="col-md-12 col-lg-12">
            <div class="card mb-3">
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
                                            @if (! empty($permohonanTransactions->permohonanBiling))
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @else
                                                <i class="mdi mdi-timer-sand text-warning fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Pembayaran</span>
                                            @if (empty($permohonanTransactions->permohonanBiling))
                                                <i class="mdi mdi-close-circle text-danger fs-2"></i>
                                            @elseif ($permohonanTransactions->permohonanBiling->path == null)
                                                <i class="fa fa-file-invoice-dollar text-warning fs-2"></i>
                                            @elseif ($permohonanTransactions->permohonanBiling->path != null && $permohonanTransactions->permohonanBiling->is_valid == false)
                                                <i class="mdi mdi-timer-sand text-warning fs-2"></i>
                                            @else
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="d-flex flex-column align-items-center p-3 border rounded bg-light h-100">
                                            <span class="fw-semibold mb-2">Pemasangan</span>
                                            @if(isset($permohonanTransactions) && ($permohonanTransactions->status == 'PEMASANGAN' || $permohonanTransactions->status == 'SELESAI'))
                                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                                            @elseif(isset($permohonanTransactions) && $permohonanTransactions->status == 'TERJADWAL PEMASANGAN')
                                                <i class="mdi mdi-timer-sand text-warning fs-2"></i>
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
                                            @if (! empty($permohonanTransactions->permohonanBiling) && $permohonanTransactions->permohonanBiling->path != null)
                                                @if($permohonanTransactions->permohonanBiling->is_valid == false)
                                                    <span class="badge bg-warning text-dark">Belum Tervalidasi</span>
                                                @else
                                                    <span class="badge bg-success text-dark">Lunas</span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                                            @endif
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

            @if ($permohonanTransactions && $permohonanTransactions->permohonanBiling)
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6 text-center">
                                <h6 class="fw-semibold my-3">Nomor VA</h6>
                                <span class="fw-semibold">{{ $permohonanTransactions->permohonanBiling->no_va ?? '-' }}</span>
                                <h6 class="fw-semibold my-3">Nominal Pembayaran</h6>
                                <span class="fw-semibold">Rp. {{ number_format($permohonanTransactions->permohonanBiling->price, 0, ',', '.') ?? '-' }}</span>
                            </div>
                            <div class="col-6 text-center">
                                <h6 class="fw-semibold my-3">Cara Pembayaran</h6>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-cash-multiple-outline me-2"></i>
                                    Lihat Cara Pembayaran
                                </button>
                            </div>
                        </div>

                        @if(! empty($permohonanTransactions->permohonanBiling) && $permohonanTransactions->permohonanBiling->path !== null)
                            <div class="row mb-3">
                                <div class="col-6 text-center">
                                    <h6>Bukti Pembayaran</h6>
                                </div>
                                <div class="col-6 text-center">
                                    <a href="{{ asset('storage/'.$permohonanTransactions->permohonanBiling->path) }}"
                                        target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="mdi mdi-eye-outline me-2"></i>
                                        Lihat Bukti Pembayaran
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(! empty($permohonanTransactions->permohonanBiling) && $permohonanTransactions->permohonanBiling->is_valid == false)
                        <div class="row mb-3">
                            <form id="form-upload-bukti-pembayaran" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="permohonan_transaction_id" value="{{ $permohonanTransactions->id ?? null }}">
                                <div class="col-12 text-center">
                                    <h6>Unggah Bukti Pembayaran</h6>
                                    <p class="text-muted">Format file yang diizinkan: PDF, JPG, JPEG, PNG. Maksimal 2MB</p>
                                    <div class="form-group">
                                        <input type="file" name="bukti_pembayaran" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-outline-primary btn-sm mt-3 float-end">
                                        <i class="mdi mdi-upload-outline me-2"></i>
                                        Unggah
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($permohonanTransactions && $permohonanTransactions->permohonanOfficer)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pemasangan</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex flex-column align-items-center shadow-sm p-3 rounded bg-light h-100">
                                <i class="mdi mdi-account-hard-hat fs-2 text-primary mb-2"></i>
                                <span class="fw-semibold">Petugas Pemasangan</span>
                                <h6 class="mb-0 mt-1">{{ optional($permohonanTransactions->permohonanOfficer->petugas)->name ?? '-' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex flex-column align-items-center shadow-sm p-3 rounded bg-light h-100">
                                <i class="mdi mdi-water fs-2 text-info mb-2"></i>
                                <span class="fw-semibold">Jenis Meteran</span>
                                <h6 class="mb-0 mt-1">{{ optional($permohonanTransactions->permohonanOfficer->msMeteran)->nama ?? '-' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex flex-column align-items-center shadow-sm p-3 rounded bg-light h-100">
                                <i class="mdi mdi-numeric fs-2 text-success mb-2"></i>
                                <span class="fw-semibold">Nomor Meteran</span>
                                <h6 class="mb-0 mt-1">{{ $permohonanTransactions->permohonanOfficer->nomor_seri ?? '-' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex flex-column align-items-center shadow-sm p-3 rounded bg-light h-100">
                                <i class="mdi mdi-calendar-check fs-2 text-warning mb-2"></i>
                                <span class="fw-semibold">Tanggal Pasang</span>
                                <h6 class="mb-0 mt-1">
                                    @if($permohonanTransactions->permohonanOfficer->tgl_pasang)
                                        {{ \Carbon\Carbon::parse($permohonanTransactions->permohonanOfficer->tgl_pasang)->locale('id')->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            $('#form-upload-bukti-pembayaran').on('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('permohonan.upload-bukti-pembayaran') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        });

                        location.reload();
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        } else {
                            Swal.fire({ title: 'Gagal', text: msg, icon: 'error' });
                        }
                    }
                });
            });
        });
    </script>
@endpush