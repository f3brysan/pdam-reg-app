@extends('layouts.main')
@section('title', 'Master Jenis Tempat Tinggal')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Master Jenis Tempat Tinggal</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex mb-3">
                            <button class="btn btn-sm btn-primary" id="addJenisTempatTinggalButton">Tambah
                                Jenis Tempat Tinggal</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered" id="jenis-tempat-tinggal-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jenis Tempat Tinggal</th>
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

    @include('master.jenis-tempat-tinggal.modal')
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
            var table = $('#jenis-tempat-tinggal-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ms_jenis_tempat_tinggals.index') }}",
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

        $('#addJenisTempatTinggalButton').on('click', function () {
            $('#jenisTempatTinggalModal').modal('show');
            $('#jenisTempatTinggalForm')[0].reset();
            $('#jenisTempatTinggalModalLabel').text('Tambah Jenis Tempat Tinggal');
        });

        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('ms_jenis_tempat_tinggals.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#jenisTempatTinggalModal').modal('show');
                    $('#jenisTempatTinggalId').val(response.data.id);
                    $('#jenisTempatTinggalName').val(response.data.nama);
                    $('#jenisTempatTinggalModalLabel').text('Edit Jenis Tempat Tinggal');
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
                        url: "{{ route('ms_jenis_tempat_tinggals.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (response) {
                            toastr.success(response.message);
                            $('#jenis-tempat-tinggal-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

        $('#jenisTempatTinggalForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#jenisTempatTinggalSubmitButton').prop('disabled', true);
            $('#jenisTempatTinggalSubmitButton').text('Menyimpan...');
            $.ajax({
                url: "{{ route('ms_jenis_tempat_tinggals.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $('#jenisTempatTinggalModal').modal('hide');
                    $('#jenisTempatTinggalForm')[0].reset();
                    $('#jenisTempatTinggalSubmitButton').prop('disabled', false);
                    $('#jenisTempatTinggalSubmitButton').text('Simpan');
                    $('#jenis-tempat-tinggal-table').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function (xhr, status, error) {
                    $('#jenisTempatTinggalSubmitButton').prop('disabled', false);
                    $('#jenisTempatTinggalSubmitButton').text('Simpan');
                    toastr.error(xhr.responseJSON.message);
                },
            });
        })

    </script>

@endpush
