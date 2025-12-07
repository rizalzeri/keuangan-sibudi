<!-- Modal Tambah/Edit -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="arsipForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Tambah Arsip</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              @csrf
              <input type="hidden" id="rowIndex" value="">
              <div class="mb-3">
                  <label for="namaDokumen" class="form-label">Nama Dokumen</label>
                  <input type="text" id="namaDokumen" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="nomorDokumen" class="form-label">Nomor Dokumen</label>
                  <input type="text" id="nomorDokumen" class="form-control">
              </div>
              <div class="mb-3">
                  <label for="statusDokumen" class="form-label">Status</label>
                  <select id="statusDokumen" class="form-select">
                      <option value="Berlaku">Berlaku</option>
                      <option value="Tidak">Tidak</option>
                  </select>
              </div>
              <div class="mb-3">
                <label for="uploadArsip" class="form-label">Upload link Gdrive</label>
                <input type="text" id="uploadArsip" class="form-control">
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
