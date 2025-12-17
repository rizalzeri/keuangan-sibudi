
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
          <!-- Nama Kerjasama (baru) -->
          <div class="mb-3">
            <label for="perjNama" class="form-label">Nama Kerjasama <span class="text-danger">*</span></label>
            <input type="text" id="perjNama" name="nama_kerjasama" class="form-control" required>
          </div>

          <!-- Nomor disembunyikan di UI pada create; saat edit akan ditampilkan -->
          <div class="mb-3 d-none" id="perjNomorWrapper">
            <label for="perjNomor" class="form-label">Nomor Dokumen</label>
            <input type="text" id="perjNomor" name="nomor_dokumen" class="form-control">
            <small class="text-muted">Nomor akan digenerate otomatis saat create: PKS/{no}/{BULAN_ROMAWI}/{TAHUN}</small>
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

          <div class="mb-3 d-none" id="perjDurasiWrapper">
            <label for="perjDurasi" class="form-label">Durasi Kerjasama</label>
            <select id="perjDurasi" name="durasi" class="form-select">
              <option value="Berjalan">Berjalan</option>
              <option value="Selesai">Selesai</option>
            </select>
            <small class="text-muted">Gunakan dropdown untuk mengubah status durasi (hanya tersedia saat edit).</small>
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
