<!-- Modal Form Perjanjian Kerja -->
<div class="modal fade" id="formModalPerj" tabindex="-1" aria-labelledby="formModalLabelPerj" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="perjForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabelPerj">Tambah Perjanjian Kerja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" id="rowIndexPerj" value="">

              <div class="mb-3">
                  <label for="perjNomor" class="form-label">Nomor Dokumen</label>
                  <input type="text" id="perjNomor" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="perjPihak" class="form-label">Pihak</label>
                  <input type="text" id="perjPihak" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="perjBentuk" class="form-label">Bentuk Kerjasama</label>
                  <input type="text" id="perjBentuk" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="perjDeskripsi" class="form-label">Deskripsi</label>
                  <textarea id="perjDeskripsi" class="form-control" rows="3"></textarea>
              </div>

              <div class="mb-3">
                  <label for="perjDurasi" class="form-label">Durasi Kerjasama</label>
                  <input type="text" id="perjDurasi" class="form-control" placeholder="mis. 1 Tahun / 6 Bulan">
              </div>

              <div class="mb-3">
                  <label for="perjGdrive" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="perjGdrive" class="form-control" placeholder="https://drive.google.com/....">
                  <div class="form-text">Link GDrive disimpan namun tidak ditampilkan di modal view.</div>
              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
