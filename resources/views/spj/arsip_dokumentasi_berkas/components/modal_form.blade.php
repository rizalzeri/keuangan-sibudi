<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="berkasForm">

                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Tambah Dokumentasi Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="rowIndex">

                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" id="tahun" required></select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" class="form-control" id="nama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link Google Drive</label>
                        <input type="text" class="form-control" id="gdrive" placeholder="https://">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
