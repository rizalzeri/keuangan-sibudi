<!-- Modal View -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Detail Surat Keluar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <dl class="row">
            <dt class="col-sm-3">Nomor Dokumen</dt>
            <dd class="col-sm-9" id="viewNomor"></dd>

            <dt class="col-sm-3">Tujuan</dt>
            <dd class="col-sm-9" id="viewTujuan"></dd>

            <dt class="col-sm-3">Judul Surat</dt>
            <dd class="col-sm-9" id="viewJudul"></dd>

            <dt class="col-sm-3">Isi Surat</dt>
            <dd class="col-sm-9" id="viewIsi"></dd>

            <!-- NOTE: Link GDrive tidak ditampilkan di view -->
          </dl>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
