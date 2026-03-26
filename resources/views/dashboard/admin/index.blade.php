@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-4 mb-4">
        <!-- Gamification Card -->
        <div class="col-md-12 col-lg-8">
            <div class="card h-100">
                <div class="d-flex align-items-end row">
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="card-body">
                            <h4 class="card-title pb-xl-2">Welcome Back <strong> {{ Auth::user()->name }}</strong>🎉</h4>
                            <p class="mb-0">Selamat datang kembali di dashboard admin.</p>
                            <p>Silakan cek permohonan baru yang masuk.</p>                            
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                        <div class="card-body pb-0 px-0 px-md-4 ps-0">
                            <img src="../../assets/img/illustrations/illustration-john-light.png" height="180"
                                alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                                data-app-dark-img="illustrations/illustration-john-dark.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Gamification Card -->     

        <!-- Online Users Card -->
        <div class="col-md-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Online Users</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($onlineUsers as $user)
                            <li class="list-group-item">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: #28a745; border-radius: 50%; margin-right: 8px;"></span>
                                {{ $user->name }} ({{ $user->roles->first()->name }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Online Users Card -->
    </div>

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Permohonan Baru</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="permohonan-table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">No. Permohonan</th>
                                    <th class="text-center">Tanggal Permohonan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permohonanTransactions as $permohonan)   
                                    <tr>
                                        <td class="text-end">{{ $loop->iteration }}</td>
                                        <td>{{ $permohonan->nama }}</td>
                                        <td class="text-center">{{ $permohonan->no_register }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($permohonan->tgl_daftar)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td class="text-center"><span class="badge bg-info">{{ $permohonan->status }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('permohonan.show', Crypt::encryptString($permohonan->id)) }}" class="btn btn-sm btn-primary">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#permohonan-table').DataTable();
        });
    </script>
@endpush