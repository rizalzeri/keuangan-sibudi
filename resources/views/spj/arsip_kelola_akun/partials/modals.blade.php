<!-- CSRF meta tag required for fetch -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal Personalisasi (tetap seperti sekarang) -->
<!-- ... -->

<!-- Modal Klasifikasi -->
<div class="modal fade" id="modalKlasifikasi" tabindex="-1">
  <div class="modal-dialog">
    <form id="klasifikasi-form" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Klasifikasi Transaksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="klasifikasi-id" value="">
        <!-- hidden raw nominal (digits only) -->
        <input type="hidden" id="klasifikasi-nominal" value="">

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" id="klasifikasi-kategori" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <!-- visible input shows formatted rupiah, user types here -->
            <input type="text" id="klasifikasi-nominal-display" class="form-control" placeholder="Rp0" required>
            <div class="form-text">Masukkan angka (tanpa tanda), tampilan akan otomatis diformat.</div>
        </div>

        <div class="mb-3">
            <label>Otorisasi Mengetahui (pilih personalisasi)</label>
            <select id="klasifikasi-mengetahui_personalisasi_id" class="form-select">
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} @if($p->jabatan) ({{ $p->jabatan }}) @endif</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Otorisasi Persetujuan (pilih personalisasi)</label>
            <select id="klasifikasi-persetujuan_personalisasi_id" class="form-select">
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} @if($p->jabatan) ({{ $p->jabatan }}) @endif</option>
                @endforeach
            </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" id="klasifikasi-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit Akun / BUMDes -->
<div class="modal fade" id="modalAkunBumdes" tabindex="-1" aria-labelledby="modalAkunBumdesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="akun-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAkunBumdesLabel">Edit Profil BUMDes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="akun-form-errors" class="mb-2"></div>

            <div class="mb-3">
                <label for="akun-name" class="form-label">Nama Lengkap</label>
                <input type="text" id="akun-name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="akun-email" class="form-label">Email</label>
                <input type="email" id="akun-email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="akun-nama_bumdes" class="form-label">Nama BUMDes</label>
                <input type="text" id="akun-nama_bumdes" name="nama_bumdes" class="form-control">
            </div>

            <div class="mb-3">
                <label for="akun-alamat_bumdes" class="form-label">Alamat BUMDes</label>
                <input type="text" id="akun-alamat_bumdes" name="alamat_bumdes" class="form-control">
            </div>

            <div class="mb-3">
                <label for="akun-nomor_hukum_bumdes" class="form-label">Nomor Hukum BUMDes</label>
                <input type="text" id="akun-nomor_hukum_bumdes" name="nomor_hukum_bumdes" class="form-control">
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button id="akun-submit" type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
 
<!-- Partial: modals for Arsip Kelola Akun (personalisasi, klasifikasi, akun) -->

<!-- Modal: Personalisasi (Tambah / Edit) -->
<div class="modal fade" id="modalPersonalisasi" tabindex="-1" aria-labelledby="modalPersonalisasiLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="personalisasi-form" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalPersonalisasiLabel">Personalisasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="personalisasi-id" value="">

        <div class="mb-3">
          <label for="personalisasi-nama" class="form-label">Nama</label>
          <input type="text" id="personalisasi-nama" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="personalisasi-jabatan" class="form-label">Jabatan</label>
          <input type="text" id="personalisasi-jabatan" name="jabatan" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" id="personalisasi-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Klasifikasi Transaksi (jika ingin menaruh di partial bersama) -->
<div class="modal fade" id="modalKlasifikasi" tabindex="-1">
  <div class="modal-dialog">
    <form id="klasifikasi-form" class="modal-content">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Klasifikasi Transaksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="klasifikasi-id" value="">
        <input type="hidden" id="klasifikasi-nominal" value="">

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" id="klasifikasi-kategori" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="text" id="klasifikasi-nominal-display" class="form-control" placeholder="Rp0" required>
            <div class="form-text">Masukkan angka (tanpa tanda), tampilan akan otomatis diformat.</div>
        </div>

        <div class="mb-3">
            <label>Otorisasi Mengetahui (pilih personalisasi)</label>
            <select id="klasifikasi-mengetahui_personalisasi_id" class="form-select">
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} @if($p->jabatan) ({{ $p->jabatan }}) @endif</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Otorisasi Persetujuan (pilih personalisasi)</label>
            <select id="klasifikasi-persetujuan_personalisasi_id" class="form-select">
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} @if($p->jabatan) ({{ $p->jabatan }}) @endif</option>
                @endforeach
            </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" id="klasifikasi-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit Akun / BUMDes (jika ingin di partial juga) -->
<div class="modal fade" id="modalAkunBumdes" tabindex="-1" aria-labelledby="modalAkunBumdesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="akun-form" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalAkunBumdesLabel">Edit Profil BUMDes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div id="akun-form-errors" class="mb-2"></div>

          <div class="mb-3">
              <label for="akun-name" class="form-label">Nama Lengkap</label>
              <input type="text" id="akun-name" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
              <label for="akun-email" class="form-label">Email</label>
              <input type="email" id="akun-email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
              <label for="akun-nama_bumdes" class="form-label">Nama BUMDes</label>
              <input type="text" id="akun-nama_bumdes" name="nama_bumdes" class="form-control">
          </div>

          <div class="mb-3">
              <label for="akun-alamat_bumdes" class="form-label">Alamat BUMDes</label>
              <input type="text" id="akun-alamat_bumdes" name="alamat_bumdes" class="form-control">
          </div>

          <div class="mb-3">
              <label for="akun-nomor_hukum_bumdes" class="form-label">Nomor Hukum BUMDes</label>
              <input type="text" id="akun-nomor_hukum_bumdes" name="nomor_hukum_bumdes" class="form-control">
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button id="akun-submit" type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
