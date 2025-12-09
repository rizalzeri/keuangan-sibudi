<!-- Modal Form Berkas Dokumen -->
<div class="modal fade" id="formBerkasModal" tabindex="-1" aria-labelledby="formBerkasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="berkasForm" action="{{ url('/spj/arsip_dokumentasi_berkas_dokumen') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" id="rowIndexBerkas" value="">

            <div class="modal-header">
                <h5 class="modal-title" id="formBerkasLabel">Tambah Berkas Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="berkasTanggal" class="form-label">Tanggal (untuk penyimpanan)</label>
                        <input type="date" id="berkasTanggal" name="tanggal_berkas_dokumen" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="berkasNama" class="form-label">Nama Dokumen</label>
                        <input type="text" id="berkasNama" name="nama_dokumen" class="form-control" placeholder="Nama Dokumen">
                    </div>

                    <div class="col-12">
                        <label for="berkasGdrive" class="form-label">Link GDrive (opsional)</label>
                        <input type="text" id="berkasGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
