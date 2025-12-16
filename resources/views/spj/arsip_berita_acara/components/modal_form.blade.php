<!-- Modal Form Berita Acara -->
<div class="modal fade" id="formModalBA" tabindex="-1" aria-labelledby="formModalLabelBA" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="baForm" action="{{ url('/spj/arsip_berita_acara') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndexBa" value="">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelBA">Tambah Berita Acara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="baJudul" class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" id="baJudul" name="judul_berita_acara" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="baTanggal" class="form-label">Tanggal Peristiwa <span class="text-danger">*</span></label>
            <input type="text" id="baTanggal" name="tanggal_peristiwa" class="form-control"  required>
          </div>

          <div class="mb-3">
            <label for="baDeskripsi" class="form-label">Deskripsi</label>
            <textarea id="baDeskripsi" name="deskripsi" class="form-control" rows="5"></textarea>
          </div>

          <div class="mb-3">
            <label for="baGdrive" class="form-label">Link GDrive (opsional)</label>
            <input type="text" id="baGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
