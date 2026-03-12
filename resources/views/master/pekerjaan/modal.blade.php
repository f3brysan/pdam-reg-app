<div class="modal fade" id="pekerjaanModal" tabindex="-1" aria-labelledby="pekerjaanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="pekerjaanForm">
        @csrf
        <input type="hidden" name="id" id="pekerjaanId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pekerjaanModalLabel">Tambah Pekerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="pekerjaanName">Nama Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaanName" name="pekerjaanName" required placeholder="Nama Pekerjaan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="pekerjaanSubmitButton" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>