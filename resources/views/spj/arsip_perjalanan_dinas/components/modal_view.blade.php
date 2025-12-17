
<!-- Modal View Perjalanan Dinas (yang Anda berikan, kami gunakan dan isi dinamis) -->
<div class="modal fade" id="viewModalPD" tabindex="-1" aria-labelledby="viewModalPDLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Perjalanan Dinas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <dl class="row mb-0">
            <dt class="col-sm-4">Kegiatan</dt>
            <dd class="col-sm-8" id="viewPdKegiatan">-</dd>

            <dt class="col-sm-4">Nomor Dokumen</dt>
            <dd class="col-sm-8" id="viewPdNomor">-</dd>

            <dt class="col-sm-4">Tanggal</dt>
            <dd class="col-sm-8" id="viewPdTanggal">-</dd>

            <dt class="col-sm-4">Tempat</dt>
            <dd class="col-sm-8" id="viewPdTempat">-</dd>

            <dt class="col-sm-4">Transport</dt>
            <dd class="col-sm-8" id="viewPdTransport">-</dd>

            <dt class="col-sm-4">GDrive</dt>
            <dd class="col-sm-8">
              <a href="#" id="viewPdGdrive" target="_blank" class="text-decoration-none text-muted">Tidak ada link</a>
            </dd>
          </dl>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>
