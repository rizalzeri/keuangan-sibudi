<!-- Modal Form Dokumentasi Video -->
<div class="modal fade" id="formVideoModal" tabindex="-1" aria-labelledby="formVideoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="videoForm" action="{{ url('/spj/arsip_dokumentasi_video') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" id="rowIndexVideo" value="">

            <div class="modal-header">
                <h5 class="modal-title" id="formVideoLabel">Tambah Dokumentasi Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="videoTanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="videoTanggal" name="tanggal_video" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="videoKegiatan" class="form-label">Kegiatan</label>
                        <input type="text" id="videoKegiatan" name="kegiatan" class="form-control" placeholder="Kegiatan">
                    </div>

                    <div class="col-12">
                        <label for="videoGdrive" class="form-label">Link GDrive (opsional)</label>
                        <input type="text" id="videoGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
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
