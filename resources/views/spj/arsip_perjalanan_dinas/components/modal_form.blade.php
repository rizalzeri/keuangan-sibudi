<!-- Modal Form Perjalanan Dinas -->
<div class="modal fade" id="formModalPD" tabindex="-1" aria-labelledby="formModalLabelPD" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="pdForm" action="{{ url('/spj/arsip_perjalanan_dinas') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndexPD" value="">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelPD">Tambah Perjalanan Dinas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="pdNomor" class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
            <input type="text" id="pdNomor" name="nomor_dokumen" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="pdTanggal" class="form-label">Tanggal</label>
            <input type="date" id="pdTanggal" name="tanggal_perjalanan_dinas" class="form-control">
          </div>

          <div class="mb-3">
            <label for="pdKegiatan" class="form-label">Kegiatan</label>
            <input type="text" id="pdKegiatan" name="kegiatan" class="form-control">
          </div>

          <div class="mb-3">
            <label for="pdTempat" class="form-label">Tempat</label>
            <input type="text" id="pdTempat" name="tempat" class="form-control">
          </div>

          <div class="mb-3">
            <label for="pdTransport" class="form-label">Transport</label>
            <input type="text" id="pdTransport" name="transport" class="form-control">
          </div>

          <div class="mb-3">
            <label for="pdGdrive" class="form-label">Link GDrive (opsional)</label>
            <input type="text" id="pdGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
