<!-- Modal Form -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="suratKeluarForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Tambah Surat Keluar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" id="rowIndex" value="">

              <div class="mb-3">
                  <label for="nomorDokumen" class="form-label">Nomor Dokumen</label>
                  <input type="text" id="nomorDokumen" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="tujuan" class="form-label">Tujuan</label>
                  <input type="text" id="tujuan" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="judul" class="form-label">Judul Surat</label>
                  <input type="text" id="judul" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="isi" class="form-label">Isi (ringkasan)</label>
                  <textarea id="isi" class="form-control" rows="3"></textarea>
              </div>

              <div class="mb-3">
                  <label for="gdrive" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="gdrive" class="form-control" placeholder="https://drive.google.com/....">
                  <div class="form-text">Masukkan link berbagi GDrive jika perlu (tidak akan tampil di modal view).</div>
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
