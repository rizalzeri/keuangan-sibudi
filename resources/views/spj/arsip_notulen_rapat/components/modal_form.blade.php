<!-- Modal Form Notulen Rapat -->
<div class="modal fade" id="formModalNotulen" tabindex="-1" aria-labelledby="formModalLabelNotulen" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="notulenForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabelNotulen">Tambah Notulen Rapat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" id="rowIndexNotulen" value="">

              <div class="mb-3">
                  <label for="tanggalNotulen" class="form-label">Tanggal</label>
                  <input type="date" id="tanggalNotulen" class="form-control" value="{{ date('Y-m-d') }}" required>
                  <div class="form-text">Format tampilan: Hari,DD/MM/YYYY (mis: Kamis,20/12/2025)</div>
              </div>

              <div class="mb-3">
                  <label for="waktuNotulen" class="form-label">Waktu</label>
                  <input type="time" id="waktuNotulen" class="form-control">
              </div>

              <div class="mb-3">
                  <label for="tempatNotulen" class="form-label">Tempat</label>
                  <input type="text" id="tempatNotulen" class="form-control">
              </div>

              <div class="mb-3">
                  <label for="agendaNotulen" class="form-label">Agenda</label>
                  <textarea id="agendaNotulen" class="form-control" rows="3"></textarea>
              </div>

              <div class="mb-3">
                  <label for="penyelenggaraNotulen" class="form-label">Penyelenggara</label>
                  <input type="text" id="penyelenggaraNotulen" class="form-control">
              </div>

              <div class="mb-3">
                  <label for="gdriveNotulen" class="form-label">Link GDrive (opsional)</label>
                  <input type="url" id="gdriveNotulen" class="form-control" placeholder="https://drive.google.com/...">
                  <div class="form-text">Link disimpan tapi tidak ditampilkan pada modal view.</div>
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
