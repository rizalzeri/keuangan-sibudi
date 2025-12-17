
<!-- Modal Form Perjalanan Dinas -->
<div class="modal fade" id="formModalPD" tabindex="-1" aria-labelledby="formModalLabelPD" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="pdForm" action="{{ url('/spj/arsip_perjalanan_dinas') }}" method="POST" class="modal-content">
        @csrf
        <input type="hidden" name="_method" id="_method_pd" value="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabelPD">Tambah Perjalanan Dinas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row gy-2">
            <div class="col-12">
              <label for="pdKegiatan" class="form-label">Kegiatan</label>
              <input type="text" id="pdKegiatan" name="kegiatan" class="form-control">
            </div>

            <div class="col-12 d-none" id="pdNomorWrapper">
              <label for="pdNomor" class="form-label">Nomor Dokumen</label>
              <input type="text" id="pdNomor" name="nomor_dokumen" class="form-control">
              <small class="text-muted">Nomor akan digenerate otomatis saat create: SPPD/{no}/{BULAN_ROMAWI}/{TAHUN}</small>
            </div>

            <div class="col-md-4">
              <label for="pdTanggal" class="form-label">Tanggal</label>
              <input type="date" id="pdTanggal" name="tanggal_perjalanan_dinas" class="form-control" autocomplete="off">
            </div>

            <div class="col-md-8"></div>

            {{-- Tujuan 1 & Tempat 1 (DB column: tempat) side-by-side --}}
            <div class="col-md-6">
              <label for="tujuan1" class="form-label">Tujuan 1</label>
              <input type="text" id="tujuan1" name="tujuan_1" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="pdTempat" class="form-label">Tempat 1</label>
              <input type="text" id="pdTempat" name="tempat" class="form-control">
            </div>

            {{-- Tujuan 2 & Tempat 2 side-by-side --}}
            <div class="col-md-6">
              <label for="tujuan2" class="form-label">Tujuan 2</label>
              <input type="text" id="tujuan2" name="tujuan_2" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="tempat2" class="form-label">Tempat 2</label>
              <input type="text" id="tempat2" name="tempat_2" class="form-control">
            </div>

            <div class="col-12">
              <label class="form-label">Transportasi</label>
              <div id="transportOptions" class="mb-2">
                <div class="form-check form-check-inline">
                  <input class="form-check-input transport-checkbox" type="checkbox" id="tr_pesawat" name="transport[]" value="Pesawat">
                  <label class="form-check-label" for="tr_pesawat">Pesawat</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input transport-checkbox" type="checkbox" id="tr_bus" name="transport[]" value="Bus">
                  <label class="form-check-label" for="tr_bus">Bus</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input transport-checkbox" type="checkbox" id="tr_mobil" name="transport[]" value="Mobil">
                  <label class="form-check-label" for="tr_mobil">Mobil</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input transport-checkbox" type="checkbox" id="tr_lainnya" value="Lainnya">
                  <label class="form-check-label" for="tr_lainnya">Lainnya</label>
                </div>
              </div>
              <div id="transportOtherWrapper" class="d-none mb-2">
                <input type="text" id="transportOtherInput" class="form-control form-control-sm" placeholder="Tuliskan transportasi lainnya">
              </div>
              <small class="text-muted">Pilih lebih dari satu jika perlu.</small>
            </div>

            <div class="col-12">
              <label for="pdGdrive" class="form-label">Link GDrive (opsional)</label>
              <input type="text" id="pdGdrive" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/...">
            </div>

            <!-- Additional fields -->
            <div class="col-12 mt-3">
              <h6>Detail Perjalanan (opsional)</h6>
            </div>

            <div class="col-12">
              <label for="dasarPerjalanan" class="form-label">Dasar Perjalanan Tugas</label>
              <textarea id="dasarPerjalanan" name="dasar_perjalanan_tugas" class="form-control" rows="2"></textarea>
            </div>

            <div class="col-md-6">
              <label for="pejabatPemberi" class="form-label">Pejabat Pemberi Tugas</label>
              <input type="text" id="pejabatPemberi" name="pejabat_pemberi_tugas" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="jabatanPejabat" class="form-label">Jabatan Pejabat</label>
              <input type="text" id="jabatanPejabat" name="jabatan_pejabat" class="form-control">
            </div>

            <!-- Personil dynamic -->
            <div class="col-12">
              <label class="form-label">Pegawai / Personil</label>
              <div id="personilList"></div>
              <button type="button" id="btnAddPersonil" class="btn btn-sm btn-outline-primary mt-2">Tambah Personil</button>
              <small class="text-muted d-block mt-1">Masukkan nama dan jabatan. Bisa lebih dari satu.</small>
            </div>

            <div class="col-12">
              <label for="maksudPerjalanan" class="form-label">Maksud Perjalanan Tugas</label>
              <textarea id="maksudPerjalanan" name="maksud_perjalanan_tugas" class="form-control" rows="2"></textarea>
            </div>

            <div class="col-md-6">
              <label for="lamaHari" class="form-label">Lama Perjalanan (hari)</label>
              <input type="number" id="lamaHari" name="lama_perjalanan_hari" class="form-control" min="0">
            </div>

            <div class="col-md-6">
              <label for="dasarPembeban" class="form-label">Dasar Pembebanan Anggaran</label>
              <input type="text" id="dasarPembeban" name="dasar_pembebanan_anggaran" class="form-control">
            </div>

            <div class="col-md-6">
              <label for="pembiayaan" class="form-label">Pembiayaan</label>
              <input type="number" id="pembiayaan" name="pembiayaan" class="form-control" step="0.01" min="0">
            </div>

            <div class="col-12">
              <label for="keterangan" class="form-label">Keterangan</label>
              <textarea id="keterangan" name="keterangan" class="form-control" rows="2"></textarea>
            </div>

            <div class="col-md-6">
              <label for="tempatDikeluarkan" class="form-label">Tempat Dikeluarkan</label>
              <input type="text" id="tempatDikeluarkan" name="tempat_dikeluarkan" class="form-control">
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