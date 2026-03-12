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
                    <div class="row">
                        <div class="col-12 d-flex mb-3">
                            <button class="btn btn-sm btn-primary" id="addPekerjaanButton">Tambah
                                Pekerjaan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
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
        </div>
    </div>

    @include('master.pekerjaan.modal')
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
            var table = $('#pekerjaan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ms_pekerjaans.index') }}",
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

        $('#addPekerjaanButton').on('click', function () {
            $('#pekerjaanModal').modal('show');
            $('#pekerjaanForm')[0].reset();
            $('#pekerjaanModalLabel').text('Tambah Pekerjaan');
        });

        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('ms_pekerjaans.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#pekerjaanModal').modal('show');
                    $('#pekerjaanId').val(response.data.id);
                    $('#pekerjaanName').val(response.data.nama);
                    $('#pekerjaanModalLabel').text('Edit Pekerjaan');
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
                        url: "{{ route('ms_pekerjaans.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (response) {
                            toastr.success(response.message);
                            $('#pekerjaan-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

        $('#pekerjaanForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#pekerjaanSubmitButton').prop('disabled', true);
            $('#pekerjaanSubmitButton').text('Menyimpan...');
            $.ajax({
                url: "{{ route('ms_pekerjaans.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $('#pekerjaanModal').modal('hide');
                    $('#pekerjaanForm')[0].reset();
                    $('#pekerjaanSubmitButton').prop('disabled', false);
                    $('#pekerjaanSubmitButton').text('Simpan');
                    $('#pekerjaan-table').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function (xhr, status, error) {
                    $('#pekerjaanSubmitButton').prop('disabled', false);
                    $('#pekerjaanSubmitButton').text('Simpan');
                    toastr.error(xhr.responseJSON.message);
                }
            });
        })

    </script>

@endpush