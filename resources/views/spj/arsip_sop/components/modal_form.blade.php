<!-- Modal Form SOP -->
<div class="modal fade" id="formModalSOP" tabindex="-1" aria-labelledby="formModalLabelSOP" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="sopForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabelSOP">Tambah SOP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" id="rowIndexSop" value="">

              <div class="mb-3">
                  <label for="sopNama" class="form-label">Nama SOP</label>
                  <input type="text" id="sopNama" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="sopNomor" class="form-label">Nomor Dokumen</label>
                  <input type="text" id="sopNomor" class="form-control">
              </div>

              <div class="mb-3">
                  <label for="sopRuang" class="form-label">Ruang Lingkup</label>
                  <textarea id="sopRuang" class="form-control" rows="3"></textarea>
              </div>

              <div class="mb-3">
                  <label for="sopStatus" class="form-label">Status</label>
                  <select id="sopStatus" class="form-select" required>
                      <option value="Berlaku">Berlaku</option>
                      <option value="Tidak">Tidak</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="sopGdrive" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="sopGdrive" class="form-control" placeholder="https://drive.google.com/....">
                  <div class="form-text">Link GDrive disimpan namun tidak tampil di modal view.</div>
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
