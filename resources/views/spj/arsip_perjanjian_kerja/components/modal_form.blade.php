<!-- Modal Form Perjanjian Kerja -->
<div class="modal fade" id="formModalPerj" tabindex="-1" aria-labelledby="formModalLabelPerj" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="perjForm" action="{{ url('/spj/arsip_perjanjian_kerja') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndexPerj" value="">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelPerj">Tambah Perjanjian Kerja</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="perjNomor" class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
            <input type="text" id="perjNomor" name="nomor_dokumen" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="perjPihak" class="form-label">Pihak</label>
            <input type="text" id="perjPihak" name="pihak" class="form-control">
          </div>

          <div class="mb-3">
            <label for="perjBentuk" class="form-label">Bentuk Kerjasama</label>
            <input type="text" id="perjBentuk" name="bentuk_kerja_sama" class="form-control">
          </div>

          <div class="mb-3">
            <label for="perjDeskripsi" class="form-label">Deskripsi</label>
            <textarea id="perjDeskripsi" name="deskripsi" class="form-control" rows="4"></textarea>
          </div>

          <div class="mb-3">
            <label for="perjDurasi" class="form-label">Durasi Kerjasama</label>
            <input type="text" id="perjDurasi" name="durasi" class="form-control" placeholder="Contoh: 1 Tahun">
          </div>

          <div class="mb-3">
            <label for="perjGdrive" class="form-label">Link GDrive (opsional)</label>
            <input type="text" id="perjGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
