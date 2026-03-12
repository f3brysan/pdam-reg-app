<div class="modal fade" id="jenisMeteranModal" tabindex="-1" aria-labelledby="jenisMeteranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="jenisMeteranForm">
        @csrf
        <input type="hidden" name="id" id="jenisMeteranId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jenisMeteranModalLabel">Tambah Jenis Meteran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="jenisMeteranName">Nama Jenis Meteran</label>
                            <input type="text" class="form-control" id="jenisMeteranName" name="jenisMeteranName" required placeholder="Nama Jenis Meteran">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="jenisMeteranSubmitButton" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
