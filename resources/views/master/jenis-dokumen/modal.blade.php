<div class="modal fade" id="jenisDokumenModal" tabindex="-1" aria-labelledby="jenisDokumenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="jenisDokumenForm">
        @csrf
        <input type="hidden" name="id" id="jenisDokumenId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jenisDokumenModalLabel">Tambah Jenis Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="jenisDokumenName">Nama Jenis Dokumen</label>
                            <input type="text" class="form-control" id="jenisDokumenName" name="jenisDokumenName" required placeholder="Nama Jenis Dokumen">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="jenisDokumenSubmitButton" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
