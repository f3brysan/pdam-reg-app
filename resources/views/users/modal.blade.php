<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="userForm">
        @csrf
        <input type="hidden" name="id" id="userId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="userName">Nama</label>
                            <input type="text" class="form-control" id="userName" name="name" required placeholder="Nama lengkap">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userEmail">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required placeholder="Email">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userPassword">Password <span id="passwordOptionalLabel" class="text-muted">(kosongkan jika tidak diubah)</span></label>
                            <input type="password" class="form-control" id="userPassword" name="password" placeholder="Password">
                            <input type="password" class="form-control mt-2" id="userPasswordConfirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userRole">Role</label>
                            <select class="form-control" id="userRole" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="userSubmitButton" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
