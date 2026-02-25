@extends('layouts.spj.main')

@section('container')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    // $record mungkin null (create) atau App\Models\ArsipKasMasuk instance (edit)
    $isEdit = isset($record) && $record;

    /**
     * Normalize dokumen pendukung:
     */
    function normalizeDokumenPendukung($val) {
        if (is_array($val)) return array_values($val);
        if ($val === null || $val === '') return [];
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return array_values($decoded);
            $trim = trim($val);
            if ($trim === '') return [];
            if (strpos($trim, ',') !== false) {
                $parts = array_map('trim', explode(',', $trim));
                return array_values(array_filter($parts, function($x){ return $x !== ''; }));
            }
            // If JSON-like but already array-ish, return single-item
            return [$trim];
        }
        return (array) $val;
    }

    // prepare dokumen pendukung
    $oldDok = old('dokumen_pendukung');
    if ($oldDok !== null) {
        $dokSel = normalizeDokumenPendukung($oldDok);
    } elseif ($isEdit && isset($record->dokumen_pendukung)) {
        $dokSel = normalizeDokumenPendukung($record->dokumen_pendukung);
    } else {
        $dokSel = [];
    }

    // initial value for JS (nominal only)
    $initialNominal = $isEdit ? ($record->nominal ?? '') : old('nominal', '');
@endphp

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
        width: 75%;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ $isEdit ? 'Edit Bukti Kas Masuk' : 'Buat Bukti Kas Masuk' }}</h4>
    </div>
    <div class="card shadow-sm">
        <br>

        <form id="buktiForm"
              action="{{ $isEdit ? url('/spj/bukti_kas_masuk/'.$record->id) : url('/spj/bukti_kas_masuk') }}"
              method="POST" class="card-body">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            <input type="hidden" name="return" value="{{ request()->query('return', '/spj/arsip_pembukuan_1') }}">
            <div class="row-flex">
                <label for="tanggal">Tanggal</label>
                <div class="input-wrap">
                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                        value="{{ old('tanggal', $isEdit ? ($record->tanggal_transaksi ? $record->tanggal_transaksi->format('Y-m-d') : '') : date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="row-flex">
                <label for="nama_transaksi">Nama Transaksi</label>
                <div class="input-wrap">
                    <input type="text" id="nama_transaksi" name="nama_transaksi"
                        class="form-control" value="{{ old('nama_transaksi', $isEdit ? $record->nama_transaksi : '') }}" required>
                </div>
            </div>

            <div class="row-flex">
                <label for="sumber">Sumber</label>
                <div class="input-wrap">
                    <input type="text" id="sumber" name="sumber" class="form-control"
                           value="{{ old('sumber', $isEdit ? $record->sumber : '') }}">
                </div>
            </div>

            <div class="row-flex">
                <label for="nominal_display">Nominal</label>
                <div class="input-wrap">
                    {{-- tampilkan dalam format Rp. jika ada nominal awal --}}
                    <input type="text" id="nominal_display" class="form-control"
                        placeholder="Rp.0" autocomplete="off" inputmode="numeric"
                        value="{{ old('nominal') ? 'Rp.'.number_format(old('nominal'),0,',','.') : ($initialNominal ? 'Rp.'.number_format($initialNominal,0,',','.') : '') }}">
                    <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal', $initialNominal) }}">
                </div>
            </div>

            <div class="row-flex">
                <label for="penerima">Penerima</label>
                <div class="input-wrap">
                    <input type="text" id="penerima" name="penerima" class="form-control"
                           value="{{ old('penerima', $isEdit ? $record->penerima : '') }}">
                </div>
            </div>

            {{-- MENYETUJUI dan MENGETAHUI dihapus sesuai permintaan --}}

            <div class="row-flex">
                <label>Kategori Pembukuan</label>
                <div class="input-wrap">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="1"
                               {{ old('kategori_pembukuan', $isEdit ? $record->kategori_pembukuan : '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="2"
                               {{ old('kategori_pembukuan', $isEdit ? $record->kategori_pembukuan : '') == '2' ? 'checked' : '' }}>
                        <label class="form-check-label">2</label>
                    </div>
                </div>
            </div>

            <!-- Dokumen pendukung -->
            <div class="row-flex">
                <label>Dokumen Pendukung</label>
                <div class="input-wrap">
                    <div class="row">
                        {{-- $dokSel sudah dinormalisasi di bagian atas --}}
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="kwitansi" {{ in_array('kwitansi', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Kwitansi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="nota" {{ in_array('nota', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Nota</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="bukti_transfer" {{ in_array('bukti_transfer', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Bukti Transfer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="surat" {{ in_array('surat', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Surat</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="berita_acara" {{ in_array('berita_acara', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Berita Acara</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="laporan" {{ in_array('laporan', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Laporan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="tanda_terima" {{ in_array('tanda_terima', $dokSel) ? 'checked' : '' }}>
                                <label class="form-check-label">Tanda Terima</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="lainnya" {{ in_array('lainnya', $dokSel) ? 'checked' : '' }}>
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
                           placeholder="https://drive.google.com/drive/folders/..." value="{{ old('link_gdrive', $isEdit ? $record->link_gdrive : '') }}">
                    <div class="form-text">Opsional — masukkan link file/folder Google Drive yang terkait.</div>
                </div>
            </div>

            <div class="row-flex">
                <label for="catatan">Catatan</label>
                <div class="input-wrap">
                    <input type="text" id="catatan" name="catatan" class="form-control" value="{{ old('catatan', $isEdit ? $record->catatan : '') }}">
                </div>
            </div>

            <div class="text-end mt-3">
                <button id="saveBtn" type="submit" class="btn {{ $isEdit ? 'btn-warning' : 'btn-success' }}">
                    <i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-save' }}"></i>
                    {{ $isEdit ? 'Update & Cetak' : 'Simpan & Cetak' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 CDN (untuk alert) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // initial value for JS (nominal only)
    const initialNominal = {!! json_encode($initialNominal ?: '') !!};

    document.addEventListener('DOMContentLoaded', function () {
        const display = document.getElementById('nominal_display');
        const hidden  = document.getElementById('nominal');

        /* -----------------------
           FORMAT NOMINAL RUPIAH
        -------------------------*/
        function formatRupiahDigits(digits) {
            // digits: string or number of only digits (e.g. "1200000")
            if (digits === null || typeof digits === 'undefined') return '';
            const s = String(digits).replace(/\D/g, '');
            return s.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function setDisplayFromHidden() {
            const val = hidden.value || '';
            if (!val) {
                display.value = '';
            } else {
                display.value = 'Rp.' + formatRupiahDigits(val);
            }
        }

        // initialize display from hidden (so edit mode shows formatted Rp.)
        setDisplayFromHidden();

        function updateNominalFromInput(rawValue) {
            const digits = String(rawValue).replace(/\D/g, '').replace(/^0+/, '') || '';
            hidden.value = digits;
            display.value = digits === '' ? '' : 'Rp.' + formatRupiahDigits(digits);
            // otorisasi otomatis dihapus — tidak ada pemanggilan API
        }

        display.addEventListener('input', function () {
            updateNominalFromInput(this.value);
        });

        display.addEventListener('focus', function () {
            // show digits-only formatted while editing (without "Rp." prefix)
            const val = hidden.value || '';
            this.value = val ? formatRupiahDigits(val) : '';
        });

        display.addEventListener('blur', function () {
            setDisplayFromHidden();
        });

        display.addEventListener('keydown', function (e) {
            const allow = [8,9,13,27,37,38,39,40,46];
            if (allow.includes(e.keyCode)) return;
            const isNumber = (e.keyCode >= 48 && e.keyCode <= 57) ||
                             (e.keyCode >= 96 && e.keyCode <= 105);
            if (!isNumber) e.preventDefault();
        });

        /* -----------------------------------
           HANDLE SUBMIT: PRINT + SIMPAN DATA
           (tetap menggunakan AJAX dan server mengembalikan JSON)
        --------------------------------------*/
        const form = document.getElementById('buktiForm');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled');
            }

            const fd = new FormData(form);

            // open blank tab early to avoid popup blocking
            let printWindow = null;
            try { printWindow = window.open('about:blank', '_blank'); } catch (err) { printWindow = null; }

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

                const contentType = response.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') === -1) {
                    const txt = await response.text();
                    throw new Error("Unexpected response format: " + txt.slice(0, 400));
                }

                const result = await response.json();

                const isSuccess = result && (result.success === true || result.status === 'success' || result.status === 'ok');

                if (!isSuccess) {
                    if (printWindow && !printWindow.closed) printWindow.close();
                    Swal.fire({ icon: "error", title: "Gagal!", text: (result && (result.message || result.error || result.status)) || "Terjadi kesalahan saat menyimpan data" });
                    return;
                }

                const created = result.data || {};
                const newId = created.id || created.ID || createdIdFromResult(result);

                if (!newId) {
                    if (printWindow && !printWindow.closed) printWindow.close();
                    Swal.fire({ icon: "warning", title: "Sukses disimpan, tapi ID tidak ditemukan", text: "Data berhasil disimpan tetapi server tidak mengembalikan ID. Coba refresh halaman." }).then(() => location.reload());
                    return;
                }

                const printBase = "{{ url('/spj/bukti_kas_masuk/print') }}";
                const printUrl = printBase + '?id=' + encodeURIComponent(newId);

                if (!printWindow || printWindow.closed) {
                    window.open(printUrl, '_blank');
                } else {
                    printWindow.location.href = printUrl;
                }

                Swal.fire({ icon: "success", title: "Berhasil!", text: result.message || "Data berhasil disimpan", confirmButtonText: "OK" }).then(() => location.reload());

            } catch (err) {
                console.error('Error saat menyimpan & membuka print:', err);
                if (printWindow && !printWindow.closed) printWindow.close();
                Swal.fire({ icon: "error", title: "Gagal!", text: err.message || "Terjadi error saat mengirim data!" });
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled');
                }
            }

            function createdIdFromResult(res) {
                try {
                    if (!res) return null;
                    if (res.data && (res.data.id || res.data.ID)) return res.data.id || res.data.ID;
                    if (res.id) return res.id;
                    if (res.record && res.record.id) return res.record.id;
                    return null;
                } catch (e) { return null; }
            }
        });

    });

    flatpickr("#tanggal", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "l, d F Y",
        locale: "id",
        allowInput: true
    });
</script>

@endsection