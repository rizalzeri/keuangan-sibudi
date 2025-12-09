<!-- Modal View Berkas -->
<div class="modal fade" id="viewBerkasModal" tabindex="-1" aria-labelledby="viewBerkasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Berkas Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Tahun</dt>
                    <dd class="col-sm-8" id="viewBerkasYear">-</dd>

                    <dt class="col-sm-4">Tanggal (lengkap)</dt>
                    <dd class="col-sm-8" id="viewBerkasTanggalFull">-</dd>

                    <dt class="col-sm-4">Nama Dokumen</dt>
                    <dd class="col-sm-8" id="viewBerkasNama">-</dd>

                    <dt class="col-sm-4">Link GDrive</dt>
                    <dd class="col-sm-8">
                        <a href="#" target="_blank" id="viewBerkasGdrive" class="text-decoration-none text-muted">Tidak ada link</a>
                    </dd>
                </dl>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
