<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="pdForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Tambah Perjalanan Dinas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="rowIndex">

                    <div class="mb-2">
                        <label>Nomor Dokumen *</label>
                        <input type="text" id="nomor" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Tanggal *</label>
                        <input type="date" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-2">
                        <label>Kegiatan *</label>
                        <textarea id="kegiatan" class="form-control"></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Tempat</label>
                        <input type="text" id="tempat" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Transport</label>
                        <input type="text" id="transport" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Link GDrive</label>
                        <input type="text" id="gdrive" class="form-control" placeholder="https://drive.google.com/...">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
