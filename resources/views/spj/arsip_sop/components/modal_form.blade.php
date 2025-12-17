<div class="modal fade" id="formModalSOP" tabindex="-1" aria-labelledby="formModalLabelSOP" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="sopForm" action="{{ url('/spj/arsip_sop') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndexSop" value="">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelSOP">Tambah SOP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="sopNama" class="form-label">Nama SOP <span class="text-danger">*</span></label>
            <input type="text" id="sopNama" name="nama_sop" class="form-control" required>
          </div>

          <!-- Nomor disembunyikan di UI pada create; saat edit akan diisi dan wrapper di-removed d-none -->
          <div class="mb-3 d-none" id="sopNomorWrapper">
            <label for="sopNomor" class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
            <input type="text" id="sopNomor" name="nomor_dokumen" class="form-control">
          </div>

          <div class="mb-3">
            <label for="sopRuang" class="form-label">Ruang Lingkup</label>
            <input type="text" id="sopRuang" name="ruang_lingkup" class="form-control">
          </div>

          <div class="mb-3">
            <label for="sopStatus" class="form-label">Status</label>
            <select id="sopStatus" name="status" class="form-select">
              <option value="Berlaku">Berlaku</option>
              <option value="Tidak Berlaku">Tidak Berlaku</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="sopGdrive" class="form-label">Link GDrive (opsional)</label>
            <input type="text" id="sopGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>