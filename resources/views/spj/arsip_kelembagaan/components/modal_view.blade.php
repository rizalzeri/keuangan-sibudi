<!-- Modal View -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Arsip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama Dokumen</dt>
                    <dd class="col-sm-8" id="viewNama"></dd>

                    <dt class="col-sm-4">Nomor</dt>
                    <dd class="col-sm-8" id="viewNomor"></dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8" id="viewStatus"></dd>

                    <dt class="col-sm-4">Link GDrive</dt>
                    <dd class="col-sm-8">
                        <a href="#" target="_blank" id="viewLink">Tidak ada link</a>
                    </dd>
                </dl>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
