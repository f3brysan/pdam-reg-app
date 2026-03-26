@extends('layouts.main')
@section('title', 'Data Permohonan')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Permohonan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered" id="permohonan-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">No Register</th>
                                        <th class="text-center">Tgl Daftar</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permohonans as $item)
                                        <tr>
                                            <td class="text-end">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->no_register }}</td>
                                            <td class="text-center">{{ $item->tgl_daftar }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-label-primary">{{ $item->status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('permohonan.show', Crypt::encryptString($item->id)) }}" class="btn btn-sm btn-primary">Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada data permohonan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#permohonan-table').DataTable();
        });
    </script>
@endpush