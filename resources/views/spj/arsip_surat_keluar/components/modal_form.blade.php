<!-- Modal Form -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="suratKeluarForm" action="/spj/arsip_surat_keluar" method="POST">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndex" name="rowIndex" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Tambah Surat Keluar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nomorDokumen" class="form-label">Nomor Dokumen</label>
              <input type="text" id="nomorDokumen" name="nomor_dokumen" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="tujuan" class="form-label">Tujuan</label>
              <input type="text" id="tujuan" name="tujuan" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="judul" class="form-label">Judul Surat</label>
              <input type="text" id="judul" name="judul_surat" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="isi" class="form-label">Isi</label>
              <textarea id="isi" name="isi" rows="4" class="form-control"></textarea>
            </div>
            <div class="mb-3">
              <label for="gdrive" class="form-label">Link GDrive</label>
              <input type="text" id="gdrive" name="link_gdrive" class="form-control">
              <small class="text-muted">Isi URL jika ada lampiran di Google Drive.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
