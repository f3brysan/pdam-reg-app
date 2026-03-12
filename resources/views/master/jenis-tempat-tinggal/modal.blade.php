<div class="modal fade" id="jenisTempatTinggalModal" tabindex="-1" aria-labelledby="jenisTempatTinggalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="jenisTempatTinggalForm">
        @csrf
        <input type="hidden" name="id" id="jenisTempatTinggalId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jenisTempatTinggalModalLabel">Tambah Jenis Tempat Tinggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="jenisTempatTinggalName">Nama Jenis Tempat Tinggal</label>
                            <input type="text" class="form-control" id="jenisTempatTinggalName" name="jenisTempatTinggalName" required placeholder="Nama Jenis Tempat Tinggal">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="jenisTempatTinggalSubmitButton" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
