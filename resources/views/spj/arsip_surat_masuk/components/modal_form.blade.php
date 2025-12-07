<!-- Modal Form -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="suratForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Tambah Surat Masuk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" id="rowIndex" value="">
              <div class="mb-3">
                  <label for="pengirim" class="form-label">Pengirim</label>
                  <input type="text" id="pengirim" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="judul" class="form-label">Judul</label>
                  <input type="text" id="judul" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="isi" class="form-label">Isi (ringkasan)</label>
                  <textarea id="isi" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                  <label for="gdrive" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="gdrive" class="form-control" placeholder="https://drive.google.com/....">
                  <div class="form-text">Masukkan link share GDrive (pastikan permission view atau link dapat diakses).</div>
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
