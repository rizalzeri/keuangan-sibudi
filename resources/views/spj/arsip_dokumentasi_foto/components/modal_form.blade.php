<!-- Modal Form Dokumentasi Foto -->
<div class="modal fade" id="formFotoModal" tabindex="-1" aria-labelledby="formFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="fotoForm" action="{{ url('/spj/arsip_dokumentasi_foto') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" id="rowIndex" value="">

            <div class="modal-header">
                <h5 class="modal-title" id="formFotoLabel">Tambah Dokumentasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-12">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" name="tanggal_foto" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="kegiatan" class="form-label">Kegiatan</label>
                        <input type="text" id="kegiatan" name="kegiatan" class="form-control" placeholder="Kegiatan">
                    </div>

                    <div class="col-12">
                        <label for="gdrive" class="form-label">Link GDrive (opsional)</label>
                        <input type="text" id="gdrive" name="link_gdrive" class="form-control"
                               placeholder="https://drive.google.com/...">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
