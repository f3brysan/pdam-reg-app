@extends('layouts.main')
@section('title', 'Master Jenis Meteran')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Master Jenis Meteran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex mb-3">
                            <button class="btn btn-sm btn-primary" id="addJenisMeteranButton">Tambah
                                Jenis Meteran</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered" id="jenis-meteran-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jenis Meteran</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('master.jenis-meteran.modal')
@endsection
@push('scripts')

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        $(document).ready(function () {
            var table = $('#jenis-meteran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ms_jenis_meterans.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                    },
                ]
            });
        });

        $('#addJenisMeteranButton').on('click', function () {
            $('#jenisMeteranModal').modal('show');
            $('#jenisMeteranForm')[0].reset();
            $('#jenisMeteranModalLabel').text('Tambah Jenis Meteran');
        });

        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('ms_jenis_meterans.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#jenisMeteranModal').modal('show');
                    $('#jenisMeteranId').val(response.data.id);
                    $('#jenisMeteranName').val(response.data.nama);
                    $('#jenisMeteranModalLabel').text('Edit Jenis Meteran');
                },
                error: function (xhr, status, error) {
                    toastr.error(xhr.responseJSON.message);
                },
            })
        });

        $(document).on('click', '.delete-btn', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('ms_jenis_meterans.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (response) {
                            toastr.success(response.message);
                            $('#jenis-meteran-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

        $('#jenisMeteranForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#jenisMeteranSubmitButton').prop('disabled', true);
            $('#jenisMeteranSubmitButton').text('Menyimpan...');
            $.ajax({
                url: "{{ route('ms_jenis_meterans.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $('#jenisMeteranModal').modal('hide');
                    $('#jenisMeteranForm')[0].reset();
                    $('#jenisMeteranSubmitButton').prop('disabled', false);
                    $('#jenisMeteranSubmitButton').text('Simpan');
                    $('#jenis-meteran-table').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function (xhr, status, error) {
                    $('#jenisMeteranSubmitButton').prop('disabled', false);
                    $('#jenisMeteranSubmitButton').text('Simpan');
                    toastr.error(xhr.responseJSON.message);
                },
            });
        })

    </script>

@endpush
