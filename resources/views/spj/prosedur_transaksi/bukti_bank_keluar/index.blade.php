@extends('layouts.spj.main')

@section('container')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .row-flex {
        display: flex;
        width: 100%;
        margin-bottom: 15px;
    }
    .row-flex label {
        width: 50%;
        padding-right: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    .row-flex .input-wrap {
        width: 50%;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Buat Bukti Bank Keluar</h4>
    </div>
    <div class="card shadow-sm">
        <br>

        <!-- NOTE: name="sumber" tetap agar controller menerima field yang sama dengan bank_masuk,
             namun label tetap "Tujuan" untuk UX -->
        <form id="buktiForm" action="{{ url('/spj/bukti_bank_keluar') }}" method="POST" class="card-body">
            @csrf
            <div class="row-flex">
                <label for="tanggal">Tanggal</label>
                <div class="input-wrap">
                    <input type="text" id="tanggal" name="tanggal" class="form-control"
                        value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row-flex">
                <label for="nama_transaksi">Nama Transaksi</label>
                <div class="input-wrap">
                    <input type="text" id="nama_transaksi" name="nama_transaksi"
                        class="form-control" placeholder="Contoh: Pembayaran, Pembelian ..." required>
                </div>
            </div>

            <div class="row-flex">
                <label for="tujuan">Tujuan</label>
                <div class="input-wrap">
                    <!-- gunakan name="sumber" agar backend kompatibel -->
                    <input type="text" id="tujuan" name="tujuan" class="form-control"
                        placeholder="Contoh: Vendor, Karyawan, Bank">
                </div>
            </div>

            <div class="row-flex">
                <label for="nominal_display">Nominal</label>
                <div class="input-wrap">
                    <input type="text" id="nominal_display" class="form-control"
                        placeholder="Rp.0" autocomplete="off" inputmode="numeric">
                    <input type="hidden" id="nominal" name="nominal">
                </div>
            </div>

            <div class="row-flex">
                <label for="penerima">Penerima</label>
                <div class="input-wrap">
                    <input type="text" id="penerima" name="penerima" class="form-control">
                </div>
            </div>

              <!-- MENYETUJUI -> dropdown (disabled awalnya) -->
            <div class="row-flex">
                <label for="menyetujui_select">Menyetujui</label>
                <div class="input-wrap">
                    <select id="menyetujui_select" class="form-control" disabled>
                        <option value="">-- Pilih Menyetujui --</option>
                    </select>

                    <!-- Hidden: hanya nama yang akan disimpan -->
                    <input type="hidden" id="menyetujui" name="menyetujui" value="">
                    <!-- optional id (tetap ada, dikosongkan sekarang) -->
                    <input type="hidden" id="menyetujui_id" name="menyetujui_id" value="">
                </div>
            </div>

            <!-- MENGETAHUI -> dropdown (disabled awalnya) -->
            <div class="row-flex">
                <label for="mengetahui_select">Mengetahui</label>
                <div class="input-wrap">
                    <select id="mengetahui_select" class="form-control" disabled>
                        <option value="">-- Pilih Mengetahui --</option>
                    </select>

                    <input type="hidden" id="mengetahui" name="mengetahui" value="">
                    <input type="hidden" id="mengetahui_id" name="mengetahui_id" value="">
                </div>
            </div>

            <div class="row-flex">
                <label>Kategori Pembukuan</label>
                <div class="input-wrap">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="1" checked>
                        <label class="form-check-label">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="2">
                        <label class="form-check-label">2</label>
                    </div>
                </div>
            </div>

            <!-- Dokumen pendukung : kirim sebagai dokumen_pendukung[] supaya controller bisa terima array -->
            <div class="row-flex">
                <label>Dokumen Pendukung</label>
                <div class="input-wrap">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="kwitansi">
                                <label class="form-check-label">Kwitansi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="nota">
                                <label class="form-check-label">Nota</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="bukti_transfer">
                                <label class="form-check-label">Bukti Transfer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="surat">
                                <label class="form-check-label">Surat</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="berita_acara">
                                <label class="form-check-label">Berita Acara</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="laporan">
                                <label class="form-check-label">Laporan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="tanda_terima">
                                <label class="form-check-label">Tanda Terima</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="lainnya">
                                <label class="form-check-label">Lainnya</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-flex">
                <label for="link_gdrive">Link Google Drive</label>
                <div class="input-wrap">
                    <input type="url" id="link_gdrive" name="link_gdrive" class="form-control"
                           placeholder="https://drive.google.com/drive/folders/..." value="{{ old('link_gdrive') }}">
                    <div class="form-text">Opsional — masukkan link file/folder Google Drive yang terkait.</div>
                </div>
            </div>
            <div class="text-end mt-3">
                <button id="saveBtn" type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan & Cetak</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 CDN (untuk alert) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const display = document.getElementById('nominal_display');
        const hidden  = document.getElementById('nominal');

        const mengetahuiSelect = document.getElementById('mengetahui_select');
        const menyetujuiSelect = document.getElementById('menyetujui_select');

        const mengetahuiHidden = document.getElementById('mengetahui');
        const mengetahuiIdHidden = document.getElementById('mengetahui_id');
        const menyetujuiHidden = document.getElementById('menyetujui');
        const menyetujuiIdHidden = document.getElementById('menyetujui_id');
        /* -----------------------
           FORMAT NOMINAL RUPIAH
        -------------------------*/
        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateNominal(value) {
            const angka = value.replace(/\D/g, "").replace(/^0+/, "") || "0";
            hidden.value = angka;
            display.value = angka === "0" ? "" : "Rp." + formatRupiah(angka);
            // panggil API untuk memperbarui dropdown
            loadOtorisasiOptions(parseInt(angka || 0, 10));
        }

        display.addEventListener('input', function () {
            const hanyaAngka = this.value.replace(/\D/g, "");
            updateNominal(hanyaAngka);
        });

        display.addEventListener('focus', function () {
            const angka = hidden.value;
            display.value = angka === "" ? "" : formatRupiah(angka);
        });

        display.addEventListener('blur', function () {
            const angka = hidden.value;
            display.value = angka === "" ? "" : "Rp." + formatRupiah(angka);
        });

        display.addEventListener('keydown', function (e) {
            const allow = [8,9,13,27,37,38,39,40,46];
            if (allow.includes(e.keyCode)) return;
            const isNumber = (e.keyCode >= 48 && e.keyCode <= 57) ||
                             (e.keyCode >= 96 && e.keyCode <= 105);
            if (!isNumber) e.preventDefault();
        });

        /* ---------------------------
           Helpers: clear select (keep placeholder)
        ----------------------------*/
        function clearSelectKeepPlaceholder(sel) {
            while (sel.options.length > 1) sel.remove(1); // keep the first placeholder
        }

        function setSelectSingleOption(sel, value, text, personalisasi_id = '') {
            clearSelectKeepPlaceholder(sel);
            const opt = document.createElement('option');
            opt.value = value || '';
            opt.text = text || '';
            if (personalisasi_id) opt.setAttribute('data-personalisasi-id', personalisasi_id);
            sel.add(opt);
        }

        /* -----------------------------------
           Fetch opsi otorisasi berdasarkan nominal
           HANYA menampilkan 1 opsi rekomendasi (jika ada).
           Endpoint: /spj/klasifikasi/classify?nominal=...
        --------------------------------------*/
        async function loadOtorisasiOptions(nominalValue) {
            try {
                // jika nominal 0 atau kosong -> kosongkan dropdown dan disable
                if (!nominalValue || nominalValue <= 0) {
                    clearSelectKeepPlaceholder(mengetahuiSelect);
                    clearSelectKeepPlaceholder(menyetujuiSelect);
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;

                    // kosongkan hidden fields
                    mengetahuiHidden.value = '';
                    mengetahuiIdHidden.value = '';
                    menyetujuiHidden.value = '';
                    menyetujuiIdHidden.value = '';
                    return;
                }

                const url = "{{ route('spj.klasifikasi.classify') }}?nominal=" + encodeURIComponent(nominalValue || 0);
                const res = await fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" }});
                if (!res.ok) {
                    console.warn('classify API returned', res.status);
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;
                    return;
                }
                const body = await res.json();
                if (!body.success) {
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;
                    return;
                }

                const data = body.data || {};
                const recommended = data.recommended || {};

                // default: clear + disabled
                clearSelectKeepPlaceholder(mengetahuiSelect);
                clearSelectKeepPlaceholder(menyetujuiSelect);
                mengetahuiSelect.disabled = true;
                menyetujuiSelect.disabled = true;

                // --- MENGETAHUI: jika ada rekomendasi, tampilkan 1 option (nama saja) dan enable select
                if (recommended.mengetahui && recommended.mengetahui.nama) {
                    setSelectSingleOption(mengetahuiSelect, recommended.mengetahui.id, recommended.mengetahui.nama, recommended.mengetahui.personalisasi_id || '');
                    mengetahuiSelect.disabled = false;

                    // simpan *nama* ke hidden (sesuai permintaan)
                    mengetahuiHidden.value = recommended.mengetahui.nama || '';
                    mengetahuiIdHidden.value = '';
                } else {
                    mengetahuiHidden.value = '';
                    mengetahuiIdHidden.value = '';
                }

                // --- MENYETUJUI: jika ada rekomendasi, tampilkan 1 option (nama saja) dan enable select
                if (recommended.persetujuan && recommended.persetujuan.nama) {
                    setSelectSingleOption(menyetujuiSelect, recommended.persetujuan.id, recommended.persetujuan.nama, recommended.persetujuan.personalisasi_id || '');
                    menyetujuiSelect.disabled = false;

                    // simpan nama saja
                    menyetujuiHidden.value = recommended.persetujuan.nama || '';
                    menyetujuiIdHidden.value = '';
                } else {
                    menyetujuiHidden.value = '';
                    menyetujuiIdHidden.value = '';
                }

            } catch (err) {
                console.error('Error loadOtorisasiOptions', err);
                mengetahuiSelect.disabled = true;
                menyetujuiSelect.disabled = true;
            }
        }

        /* -----------------------------------
           Sync hidden fields jika user ubah pilihan manual (meski hanya 1 opsi)
        --------------------------------------*/
        mengetahuiSelect.addEventListener('change', function () {
            const selOpt = this.options[this.selectedIndex];
            const nama = selOpt ? selOpt.text : '';
            mengetahuiHidden.value = nama || '';
            mengetahuiIdHidden.value = '';
        });

        menyetujuiSelect.addEventListener('change', function () {
            const selOpt = this.options[this.selectedIndex];
            const nama = selOpt ? selOpt.text : '';
            menyetujuiHidden.value = nama || '';
            menyetujuiIdHidden.value = '';
        });

        /* -----------------------------------
           On page load: keep selects empty & disabled
        --------------------------------------*/
        clearSelectKeepPlaceholder(mengetahuiSelect);
        clearSelectKeepPlaceholder(menyetujuiSelect);
        mengetahuiSelect.disabled = true;
        menyetujuiSelect.disabled = true;
        /* -----------------------------------
           HANDLE SUBMIT: PRINT + SIMPAN DATA
        --------------------------------------*/
        const form = document.getElementById('buktiForm');

        form.addEventListener('submit', async function (e) {
            e.preventDefault(); // cegah submit biasa

            // disable tombol agar user tidak spam klik
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled');
            }

            const fd = new FormData(form);

            // buka tab kosong sekarang supaya browser tidak memblok popup.
            // kita akan isi lokasinya setelah simpan sukses.
            let printWindow = null;
            try {
                printWindow = window.open('about:blank', '_blank');
            } catch (err) {
                printWindow = null;
            }

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    },
                    body: fd
                });

                // coba parse JSON, jika bukan JSON -> treat as error
                const contentType = response.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') === -1) {
                    const txt = await response.text();
                    throw new Error("Unexpected response format: " + txt.slice(0, 400));
                }

                const result = await response.json();

                // sukses? (menyamakan pola response sebelumnya)
                const isSuccess = result && (result.success === true || result.status === 'success' || result.status === 'ok');

                if (!isSuccess) {
                    // tutup printWindow kalau dibuka
                    if (printWindow && !printWindow.closed) printWindow.close();

                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: (result && (result.message || result.error || result.status)) || "Terjadi kesalahan saat menyimpan data"
                    });
                    return;
                }

                // ambil id record yang baru dibuat
                // backend Anda sebelumnya mengembalikan 'data' => $record
                const created = result.data || {};
                const newId = created.id || created.ID || createdIdFromResult(result);

                if (!newId) {
                    // jika id tidak ditemukan di payload, tutup window & beri info
                    if (printWindow && !printWindow.closed) printWindow.close();
                    Swal.fire({
                        icon: "warning",
                        title: "Sukses disimpan, tapi ID tidak ditemukan",
                        text: "Data berhasil disimpan tetapi server tidak mengembalikan ID. Coba refresh halaman."
                    }).then(() => location.reload());
                    return;
                }

                // build url print berdasarkan id (gunakan endpoint print yang benar)
                const printBase = "{{ url('/spj/bukti_bank_keluar/print') }}";
                const printUrl = printBase + '?id=' + encodeURIComponent(newId);

                // jika printWindow null (browser blok popup), buka normal sekarang (tab dipicu programmatically)
                if (!printWindow || printWindow.closed) {
                    window.open(printUrl, '_blank');
                } else {
                    // arahkan tab kosong tadi ke print url
                    printWindow.location.href = printUrl;
                }

                // tampilkan notifikasi sukses lalu reload saat OK
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: result.message || "Data berhasil disimpan",
                    confirmButtonText: "OK"
                }).then(() => location.reload());

            } catch (err) {
                console.error('Error saat menyimpan & membuka print:', err);
                if (printWindow && !printWindow.closed) printWindow.close();

                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: err.message || "Terjadi error saat mengirim data!"
                });
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled');
                }
            }

            // helper: jika server mengembalikan id dengan struktur lain, coba terbaca
            function createdIdFromResult(res) {
                try {
                    if (!res) return null;
                    // common locations
                    if (res.data && (res.data.id || res.data.ID)) return res.data.id || res.data.ID;
                    if (res.id) return res.id;
                    if (res.record && res.record.id) return res.record.id;
                    return null;
                } catch (e) {
                    return null;
                }
            }
        });

    });
    flatpickr("#tanggal", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "l, d F Y",
        locale: "id", // Bahasa Indonesia
        allowInput: true
    });
</script>

@endsection
