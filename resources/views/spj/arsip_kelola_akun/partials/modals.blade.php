<!-- CSRF meta tag required for fetch -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal Personalisasi -->
<div class="modal fade" id="modalPersonalisasi" tabindex="-1">
  <div class="modal-dialog">
    <form id="personalisasi-form" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Personalisasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="personalisasi-id" value="">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" id="personalisasi-nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" id="personalisasi-jabatan" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="personalisasi-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Mengetahui -->
<div class="modal fade" id="modalMengetahui" tabindex="-1">
  <div class="modal-dialog">
    <form id="mengetahui-form" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Otorisasi Mengetahui</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="mengetahui-id" value="">
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" id="mengetahui-kategori" class="form-control">
        </div>
        <div class="mb-3">
            <label>Personalisasi</label>
            <select id="mengetahui-personalisasi_id" class="form-select" required>
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="mengetahui-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Persetujuan -->
<div class="modal fade" id="modalPersetujuan" tabindex="-1">
  <div class="modal-dialog">
    <form id="persetujuan-form" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Otorisasi Persetujuan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="persetujuan-id" value="">
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" id="persetujuan-kategori" class="form-control">
        </div>
        <div class="mb-3">
            <label>Personalisasi</label>
            <select id="persetujuan-personalisasi_id" class="form-select" required>
                <option value="">-- pilih --</option>
                @foreach($personalisasi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="persetujuan-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Klasifikasi -->
<div class="modal fade" id="modalKlasifikasi" tabindex="-1">
  <div class="modal-dialog">
    <form id="klasifikasi-form" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Klasifikasi Transaksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="klasifikasi-id" value="">
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" id="klasifikasi-kategori" class="form-control">
        </div>
        <div class="mb-3">
            <label>Nominal</label>
            <input type="number" id="klasifikasi-nominal" class="form-control" step="0.01" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="klasifikasi-submit" data-action="create" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
