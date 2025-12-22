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
