<!-- Modal Form Notulen -->
<div class="modal fade" id="formModalNotulen" tabindex="-1" aria-labelledby="formModalLabelNotulen" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="notulenForm" action="{{ url('/spj/arsip_notulen_rapat') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="rowIndexNotulen" value="">

        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelNotulen">Tambah Notulen Rapat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="tanggalNotulen" class="form-label">Tanggal <span class="text-danger">*</span></label>
              <input type="date" id="tanggalNotulen" name="tanggal_notulen_rapat" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label for="waktuNotulen" class="form-label">Waktu</label>
              <input type="time" id="waktuNotulen" name="waktu" class="form-control">
            </div>

            <div class="col-12">
              <label for="tempatNotulen" class="form-label">Tempat</label>
              <input type="text" id="tempatNotulen" name="tempat" class="form-control">
            </div>

            <div class="col-12">
              <label for="agendaNotulen" class="form-label">Agenda</label>
              <input type="text" id="agendaNotulen" name="agenda" class="form-control">
            </div>

            <div class="col-12">
              <label for="penyelenggaraNotulen" class="form-label">Penyelenggara</label>
              <input type="text" id="penyelenggaraNotulen" name="penyelenggara" class="form-control">
            </div>

            <div class="col-12">
              <label for="gdriveNotulen" class="form-label">Link GDrive (opsional)</label>
              <input type="text" id="gdriveNotulen" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
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
