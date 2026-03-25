@extends('layouts.main')
@section('title', 'Master Jenis Dokumen')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Master Jenis Dokumen</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex mb-3">
                            <button class="btn btn-sm btn-primary" id="addJenisDokumenButton">Tambah
                                Jenis Dokumen</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered" id="jenis-dokumen-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jenis Dokumen</th>
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

    @include('master.jenis-dokumen.modal')
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
            var table = $('#jenis-dokumen-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ms_jenis_dokumens.index') }}",
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

        $('#addJenisDokumenButton').on('click', function () {
            $('#jenisDokumenModal').modal('show');
            $('#jenisDokumenForm')[0].reset();
            $('#jenisDokumenId').val('');
            $('#jenisDokumenModalLabel').text('Tambah Jenis Dokumen');
        });

        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('ms_jenis_dokumens.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#jenisDokumenModal').modal('show');
                    $('#jenisDokumenId').val(response.data.id);
                    $('#jenisDokumenName').val(response.data.nama);
                    $('#jenisDokumenModalLabel').text('Edit Jenis Dokumen');
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
                        url: "{{ route('ms_jenis_dokumens.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (response) {
                            toastr.success(response.message);
                            $('#jenis-dokumen-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

        $('#jenisDokumenForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#jenisDokumenSubmitButton').prop('disabled', true);
            $('#jenisDokumenSubmitButton').text('Menyimpan...');
            $.ajax({
                url: "{{ route('ms_jenis_dokumens.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $('#jenisDokumenModal').modal('hide');
                    $('#jenisDokumenForm')[0].reset();
                    $('#jenisDokumenSubmitButton').prop('disabled', false);
                    $('#jenisDokumenSubmitButton').text('Simpan');
                    $('#jenis-dokumen-table').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function (xhr, status, error) {
                    $('#jenisDokumenSubmitButton').prop('disabled', false);
                    $('#jenisDokumenSubmitButton').text('Simpan');
                    toastr.error(xhr.responseJSON.message);
                },
            });
        })

    </script>

@endpush
