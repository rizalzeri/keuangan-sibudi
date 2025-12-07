<div class="modal fade" id="formFotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="fotoForm" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="formFotoLabel">Tambah Dokumentasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="rowIndex">

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kegiatan</label>
                    <textarea class="form-control" id="kegiatan" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Google Drive</label>
                    <input type="text" class="form-control" id="gdrive" placeholder="Opsional">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>

        </form>
    </div>
</div>
