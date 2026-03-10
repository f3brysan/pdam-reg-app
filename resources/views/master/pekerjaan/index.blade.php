@extends('layouts.main')
@section('title', 'Master Pekerjaan')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Master Pekerjaan</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="pekerjaan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pekerjaan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
            $('#pekerjaan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ms_pekerjaans.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>

@endpush