@extends('layouts.main')
@section('title', 'Manajemen User')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manajemen User</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex mb-3">
                            <button class="btn btn-sm btn-primary" id="addUserButton">Tambah User</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
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

    @include('users.modal')
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
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
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

        $('#addUserButton').on('click', function () {
            $('#userModal').modal('show');
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#userModalLabel').text('Tambah User');
            $('#userPassword').prop('required', true);
            $('#userPasswordConfirmation').prop('required', true);
            $('#passwordOptionalLabel').addClass('d-none');
        });

        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('users.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#userModal').modal('show');
                    $('#userId').val(response.data.id);
                    $('#userName').val(response.data.name);
                    $('#userEmail').val(response.data.email);
                    $('#userPassword').val('').prop('required', false);
                    $('#userPasswordConfirmation').val('').prop('required', false);
                    $('#passwordOptionalLabel').removeClass('d-none');
                    $('#userRole').val(response.data.role || '');
                    $('#userModalLabel').text('Edit User');
                },
                error: function (xhr, status, error) {
                    toastr.error(xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memuat data');
                },
            })
        });

        $(document).on('click', '.delete-btn', function () {
            var id = $(this).data('id');
            var deleteUrl = "{{ url('user') }}/" + id + "/delete";

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (response) {
                            toastr.success(response.message);
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            toastr.error(xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menghapus');
                        }
                    });
                }
            });
        });

        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#userSubmitButton').prop('disabled', true);
            $('#userSubmitButton').text('Menyimpan...');
            $.ajax({
                url: "{{ route('users.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $('#userModal').modal('hide');
                    $('#userForm')[0].reset();
                    $('#userSubmitButton').prop('disabled', false);
                    $('#userSubmitButton').text('Simpan');
                    $('#users-table').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function (xhr, status, error) {
                    $('#userSubmitButton').prop('disabled', false);
                    $('#userSubmitButton').text('Simpan');
                    toastr.error(xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan');
                },
            });
        })

    </script>

@endpush
