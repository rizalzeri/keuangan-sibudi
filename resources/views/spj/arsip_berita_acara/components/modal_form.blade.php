<!-- Modal Form Berita Acara -->
<div class="modal fade" id="formModalBerita" tabindex="-1" aria-labelledby="formModalLabelBerita" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="beritaForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabelBerita">Tambah Berita Acara</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" id="rowIndexBerita" value="">

              <div class="mb-3">
                  <label for="judulBerita" class="form-label">Judul Berita Acara</label>
                  <input type="text" id="judulBerita" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="tanggalPeristiwa" class="form-label">Tanggal Peristiwa</label>
                  <input type="date" id="tanggalPeristiwa" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>

              <div class="mb-3">
                  <label for="deskripsiBerita" class="form-label">Deskripsi</label>
                  <textarea id="deskripsiBerita" class="form-control" rows="4"></textarea>
              </div>

              <div class="mb-3">
                  <label for="gdriveBerita" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="gdriveBerita" class="form-control" placeholder="https://drive.google.com/....">
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
