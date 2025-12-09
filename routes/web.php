<?php

use App\Models\Rekonsiliasi;
use App\Mail\GantiPasswordEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BuksController;
use App\Http\Controllers\UndoController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BdmukController;
use App\Http\Controllers\DithnController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\ArusKasController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BangunanController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\BagiHasilController;
use App\Http\Controllers\InvestasiController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\AktivalainController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\CetakProkerController;
use App\Http\Controllers\CetakLaporanController;
use App\Http\Controllers\RekonsiliasiController;
use App\Http\Controllers\AdminDataUserController;
use App\Http\Controllers\GantiPasswordController;
use App\Http\Controllers\AdminLanggananController;
use App\Http\Controllers\AnalisaKetahananPangan\AkpsController;
use App\Http\Controllers\AnalisaKetahananPangan\CetakAKPController;
use App\Http\Controllers\AnalisaKetahananPangan\KebutuhanController;
use App\Http\Controllers\AnalisaKetahananPangan\PenjualanController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\PenambahanModalController;
use App\Http\Controllers\LaporanPerubahanModalController;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\ArsipKelembagaanController;
use App\Http\Controllers\ProsedurTransaksiController;
use App\Http\Controllers\ArsipPembukuan1Controller;
use App\Http\Controllers\ArsipPembukuan2Controller;
use App\Http\Controllers\ArsipSuratMasukController;
use App\Http\Controllers\ArsipSuratKeluarController;
use App\Http\Controllers\ArsipSopController;
use App\Http\Controllers\ArsipBeritaAcaraController;
use App\Http\Controllers\ArsipPerjanjianKerjaController;
use App\Http\Controllers\ArsipPerjalananDinasController;
use App\Http\Controllers\ArsipNotulenRapatController;
use App\Http\Controllers\ArsipDokumentasiFotoController;
use App\Http\Controllers\ArsipDokumentasiVideoController;
use App\Http\Controllers\ArsipDokumentasiBerkasDokumenController;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProfileController::class, 'index'])->middleware('auth', 'langganan', 'bumdes', 'create.user','role_user:2');
Route::get('/visi/misi/', [ProfileController::class, 'visiMisi'])->middleware('auth', 'langganan', 'bumdes', 'create.user');
Route::put('/{profil:id}', [ProfileController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/visi/misi/{profil:id}', [ProfileController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');

// Modal
Route::resource('/modal', ModalController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->except(['store', 'update', 'destroy']); // Hapus metode yang akan didefinisikan manual

// Terapkan middleware 'cache.neraca' hanya pada metode POST, PUT, DELETE
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/modal', [ModalController::class, 'store'])->name('modal.store');
    Route::put('/modal/{modal}', [ModalController::class, 'update'])->name('modal.update');
    Route::delete('/modal/{modal}', [ModalController::class, 'destroy'])->name('modal.destroy');
});

Route::get('/export-pdf/modal', [BuksController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/modal', [ModalController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');


// hutang
Route::resource('/hutang', HutangController::class)
    ->middleware(['auth', 'langganan', 'bumdes']) // Middleware utama tanpa cache.neraca
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan 'cache.neraca' hanya untuk metode POST, PUT, DELETE
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/hutang', [HutangController::class, 'store'])->name('hutang.store');
    Route::put('/hutang/{hutang}', [HutangController::class, 'update'])->name('hutang.update');
    Route::delete('/hutang/{hutang}', [HutangController::class, 'destroy'])->name('hutang.destroy');
});

Route::get('/export-pdf/hutang', [HutangController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::put('hutang/bayar/{hutang:id}', [HutangController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes', 'cache.neraca');


// Buku Kas
Route::resource('/aset/buk', BuksController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan middleware 'cache.neraca' hanya pada metode POST, PUT, DELETE
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/buk', [BuksController::class, 'store'])->name('buk.store');
    Route::put('/aset/buk/{buk}', [BuksController::class, 'update'])->name('buk.update');
    Route::delete('/aset/buk/{buk}', [BuksController::class, 'destroy'])->name('buk.destroy');
});

Route::get('/export-pdf/buk', [BuksController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Pinjaman
Route::resource('/aset/pinjaman', PinjamanController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan middleware 'cache.neraca' hanya pada metode POST, PUT, DELETE
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::put('/aset/pinjaman/{pinjaman}', [PinjamanController::class, 'update'])->name('pinjaman.update');
    Route::delete('/aset/pinjaman/{pinjaman}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
});

Route::put('/aset/pinjaman/bayar/{pinjaman:id}', [PinjamanController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes', 'cache.neraca');
Route::get('/export-pdf/pinjaman', [PinjamanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/aset/pinjaman/unit/tambah', [PinjamanController::class, 'storeUnit'])->middleware('auth', 'langganan', 'bumdes', 'cache.neraca');


// Piutang
Route::resource('/aset/piutang', PiutangController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan middleware 'cache.neraca' hanya pada metode POST, PUT, DELETE
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/piutang', [PiutangController::class, 'store'])->name('piutang.store');
    Route::put('/aset/piutang/{piutang}', [PiutangController::class, 'update'])->name('piutang.update');
    Route::delete('/aset/piutang/{piutang}', [PiutangController::class, 'destroy'])->name('piutang.destroy');
});

Route::put('/aset/piutang/bayar/{piutang:id}', [PiutangController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes', 'cache.neraca');
Route::get('/export-pdf/piutang', [PiutangController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Persedian
// Rute utama dengan middleware standar
Route::resource('/aset/persediaan', PersediaanController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Rute tambahan tanpa 'cache.neraca'
Route::get('/aset/persediaan/reset/set-ulang', [PersediaanController::class, 'reset'])
    ->middleware(['auth', 'langganan', 'bumdes']);

Route::get('/export-pdf/persediaan', [PersediaanController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/persediaan', [PersediaanController::class, 'store'])->name('persediaan.store'); // Untuk create
    Route::put('/aset/persediaan/{persediaan}', [PersediaanController::class, 'update'])->name('persediaan.update'); // Untuk update
    Route::delete('/aset/persediaan/{persediaan}', [PersediaanController::class, 'destroy'])->name('persediaan.destroy'); // Untuk delete
    Route::put('/aset/persedian/jual/{persediaan:id}', [PersediaanController::class, 'penjualan']); // Untuk penjualan
    Route::post('/aset/persediaan/unit/tambah', [PersediaanController::class, 'storeUnit'])->name('persediaan.storeUnit'); // Tambah unit
});


// Bayar dimuka
// Rute utama tanpa 'cache.neraca'
Route::resource('/aset/bdmuk', BdmukController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Rute tambahan tanpa 'cache.neraca'
Route::get('/export-pdf/bdmuk', [BdmukController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/bdmuk', [BdmukController::class, 'store'])->name('bdmuk.store'); // Tambah data
    Route::put('/aset/bdmuk/{bdmuk}', [BdmukController::class, 'update'])->name('bdmuk.update'); // Update data
    Route::delete('/aset/bdmuk/{bdmuk}', [BdmukController::class, 'destroy'])->name('bdmuk.destroy'); // Hapus data
    Route::put('/aset/bdmuk/pakai/{bdmuk}', [BdmukController::class, 'pakai'])->name('bdmuk.pakai'); // Pakai aset
});


// Inventari
// Rute utama tanpa 'cache.neraca'
Route::resource('/aset/investasi', InvestasiController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Rute tambahan tanpa 'cache.neraca'
Route::get('/export-pdf/investasi', [InvestasiController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/investasi', [InvestasiController::class, 'store'])->name('investasi.store'); // Tambah data
    Route::put('/aset/investasi/{investasi}', [InvestasiController::class, 'update'])->name('investasi.update'); // Update data
    Route::delete('/aset/investasi/{investasi}', [InvestasiController::class, 'destroy'])->name('investasi.destroy'); // Hapus data
    Route::put('/aset/investasi/pakai/{investasi}', [InvestasiController::class, 'pakai'])->name('investasi.pakai'); // Pakai aset
});


// Bangunan
// Rute utama tanpa 'cache.neraca'
Route::resource('/aset/bangunan', BangunanController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Rute tambahan tanpa 'cache.neraca'
Route::get('/export-pdf/bangunan', [BangunanController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/bangunan', [BangunanController::class, 'store'])->name('bangunan.store'); // Tambah data
    Route::put('/aset/bangunan/{bangunan}', [BangunanController::class, 'update'])->name('bangunan.update'); // Update data
    Route::delete('/aset/bangunan/{bangunan}', [BangunanController::class, 'destroy'])->name('bangunan.destroy'); // Hapus data
    Route::put('/aset/bangunan/pakai/{bangunan}', [BangunanController::class, 'pakai'])->name('bangunan.pakai'); // Pakai aset
});


// Aktiva lain
// Rute utama tanpa 'cache.neraca'
Route::resource('/aset/aktivalain', AktivalainController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Rute tambahan tanpa 'cache.neraca'
Route::get('/export-pdf/aktivalain', [AktivalainController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/aktivalain', [AktivalainController::class, 'store'])->name('aktivalain.store'); // Tambah data
    Route::put('/aset/aktivalain/{aktivalain}', [AktivalainController::class, 'update'])->name('aktivalain.update'); // Update data
    Route::delete('/aset/aktivalain/{aktivalain}', [AktivalainController::class, 'destroy'])->name('aktivalain.destroy'); // Hapus data
    Route::put('/aset/aktivalain/pakai/{aktivalain}', [AktivalainController::class, 'pakai'])->name('aktivalain.pakai'); // Pakai aset
});


// Unit
Route::resource('/unit', UnitController::class)->middleware('auth', 'langganan', 'bumdes');

// Ditahan
// Rute utama tanpa 'cache.neraca'
Route::resource('/dithn', DithnController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/dithn', [DithnController::class, 'store'])->name('dithn.store'); // Tambah data
    Route::put('/dithn/{dithn}', [DithnController::class, 'update'])->name('dithn.update'); // Update data
    Route::delete('/dithn/{dithn}', [DithnController::class, 'destroy'])->name('dithn.destroy'); // Hapus data
});


// Rute export PDF tetap tanpa 'cache.neraca'
Route::get('/export-pdf/dithn', [DithnController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Rincian laba rugi
Route::get('/rincian-laba-rugi', [LabaRugiController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/rincian-laba-rugi', [LabaRugiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

//penyusutan
Route::get('/penyusutan', [PenyusutanController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');

// Route::get('/bagi-hasil', [BagiHasilController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
// Route::put('/bagi-hasil/{dithn:id}', [BagiHasilController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');

// Rekonsiliasi
// Rute utama tanpa 'cache.neraca'
Route::resource('/rekonsiliasi', RekonsiliasiController::class)
    ->middleware(['auth', 'langganan', 'bumdes'])
    ->only(['index', 'show', 'create', 'edit']); // Hanya metode GET

// Terapkan middleware 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/rekonsiliasi', [RekonsiliasiController::class, 'store'])->name('rekonsiliasi.store'); // Tambah data
    Route::put('/rekonsiliasi/{rekonsiliasi}', [RekonsiliasiController::class, 'update'])->name('rekonsiliasi.update'); // Update data
    Route::delete('/rekonsiliasi/{rekonsiliasi}', [RekonsiliasiController::class, 'destroy'])->name('rekonsiliasi.destroy'); // Hapus data
    Route::post('/rekonsiliasi/updateJumlah', [RekonsiliasiController::class, 'updateJumlah'])
        ->name('rekonsiliasi.updateJumlah'); // Update jumlah
});


// Rute export PDF tetap tanpa 'cache.neraca'
Route::get('/export-pdf/rekonsiliasi', [RekonsiliasiController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);


// Laporan keuangan neraca
// Rute GET tanpa 'cache.neraca'
Route::get('/laporan-keuangan/neraca', [NeracaController::class, 'index'])
    ->middleware(['auth', 'langganan', 'bumdes']);

Route::get('/export-pdf/neraca', [NeracaController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan 'cache.neraca' hanya pada POST yang memodifikasi data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/laporan-keuangan/neraca/tutup', [NeracaController::class, 'tutup']);
    Route::post('/laporan-keuangan/neraca/tutup/delete/{tutup:id}', [NeracaController::class, 'delete']);
});

// Laporan laba rugi
Route::get('/laporan-keuangan/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/laporan-laba-rugi', [LaporanLabaRugiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// laporan arus kas
Route::get('/laporan-keuangan/laporan-arus-kas', [ArusKasController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/laporan-arus-kas', [ArusKasController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Laporan perubahan modal
// Rute GET tanpa 'cache.neraca'
Route::get('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'index'])
    ->middleware(['auth', 'langganan', 'bumdes']);

Route::get('/export-pdf/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'exportPdf'])
    ->middleware(['auth', 'langganan', 'bumdes']);

Route::get('/laporan-keuangan/laporan-perubahan-modal/ditahan/{ekuit:id}', [LaporanPerubahanModalController::class, 'ditahan'])
    ->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'store']);
    Route::put('/laporan-keuangan/laporan-perubahan-modal/{ekuit:id}', [LaporanPerubahanModalController::class, 'update']);
});

Route::get('/export-pdf/cetak-laporan', [CetakLaporanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Langganan
Route::post('/set-tahun', [TahunController::class, 'setYear'])->name('setYear');
Route::get('/langganan', [LanggananController::class, 'index'])->middleware(['auth', 'bumdes']);
Route::post('/langganan', [LanggananController::class, 'createTransaction'])->middleware(['auth', 'bumdes']);
Route::get('/langganan/berhasil', [LanggananController::class, 'langgananSuccess'])->middleware(['auth', 'bumdes']);


// Admin
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth', 'admin');
Route::get('/admin/wilayah/kecamatan/{kecamatan}', [AdminDataUserController::class, 'index'])->middleware('auth', 'admin');
Route::get('/admin/data-user', [AdminDataUserController::class, 'allUser'])->middleware('auth', 'admin');
Route::get('/admin/data-user/create', [AdminDataUserController::class, 'create'])->middleware('auth', 'admin');
Route::post('/admin/data-user/store', [AdminDataUserController::class, 'store'])->middleware('auth', 'admin');
Route::put('/admin/data-user/{user:id}', [AdminDataUserController::class, 'ubahPassword'])->middleware('auth', 'admin');
Route::put('/admin/langganan/{user:id}', [AdminDataUserController::class, 'langganan'])->middleware('auth', 'admin');
Route::delete('/admin/data-user/{user:id}', [AdminDataUserController::class, 'destroy'])->middleware('auth', 'admin');
// Route::resource('/admin/langganan', AdminLanggananController::class)->middleware('auth', 'admin');


// Langganan Bumdes
Route::get('/admin/langganan/bumdesa', [AdminLanggananController::class, 'index'])->middleware(
    'auth',
    'admin'
);
Route::get('/admin/langganan/bumdesa/create', [AdminLanggananController::class, 'create'])->middleware('auth', 'admin');
Route::post('/admin/langganan/bumdesa/', [AdminLanggananController::class, 'store'])->middleware('auth', 'admin');
Route::get('/admin/langganan/bumdesa/{langganan:id}/edit', [AdminLanggananController::class, 'edit'])->middleware('auth', 'admin');
Route::put('/admin/langganan/bumdesa/{langganan:id}', [AdminLanggananController::class, 'update'])->middleware('auth', 'admin');
Route::delete('/admin/langganan/bumdesa/{langganan:id}', [AdminLanggananController::class, 'destroy'])->middleware('auth', 'admin');

// Langganan Bumdes Bersama
Route::get('/admin/langganan/bumdes-bersama', [AdminLanggananController::class, 'index'])->middleware('auth', 'admin');
Route::get('/admin/langganan/bumdes-bersama/create', [AdminLanggananController::class, 'create'])->middleware('auth', 'admin');
Route::post('/admin/langganan/bumdes-bersama/', [AdminLanggananController::class, 'store'])->middleware('auth', 'admin');
Route::get('/admin/langganan/bumdes-bersama/{langganan:id}/edit', [AdminLanggananController::class, 'edit'])->middleware('auth', 'admin');
Route::put('/admin/langganan/bumdes-bersama/{langganan:id}', [AdminLanggananController::class, 'update'])->middleware('auth', 'admin');
Route::delete('/admin/langganan/bumdes-bersama/{langganan:id}', [AdminLanggananController::class, 'destroy'])->middleware('auth', 'admin');

// Email
Route::post('/kirim-email', [GantiPasswordController::class, 'kirimEmail'])->name('kirim-email');
Route::get('/ganti-password', [GantiPasswordController::class, 'index'])->name('kirim-email')->middleware('guest');
Route::get('/kontak/admin', [GantiPasswordController::class, 'kontak'])->name('kontak')->middleware('auth', 'bumdes');

// program kerja
Route::get('/proker', [ProkerController::class, 'proker'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/proker/kualititif', [ProkerController::class, 'kualititif'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/proker/kualititif/{proker:id}', [ProkerController::class, 'kualititifUpdate'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/proker/strategi', [ProkerController::class, 'strategi'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/proker/strategi/{proker:id}', [ProkerController::class, 'strategiUpdate'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/proker/sasaran', [ProkerController::class, 'sasaran'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/proker/sasaran/{proker:id}', [ProkerController::class, 'sasaranUpdate'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/proker/rencana/kegiatan', [ProkerController::class, 'rencanaKegiatan'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/proker/rencana/kegiatan/store', [ProkerController::class, 'kegiatanStore'])->middleware('auth', 'langganan', 'bumdes');
Route::delete('/proker/rencana/kegiatan/{program:id}', [ProkerController::class, 'kegiatanDestroy'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/proker/rencana/kerjasama/store', [ProkerController::class, 'kerjasamaStore'])->middleware('auth', 'langganan', 'bumdes');
Route::delete('/proker/rencana/kerjasama/{kerjasama:id}', [ProkerController::class, 'kerjasamaDestroy'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/proker/penambahan/modal', [PenambahanModalController::class, 'penambahanModal'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/proker/alokasi/store', [PenambahanModalController::class, 'alokasiStore'])->middleware('auth', 'langganan', 'bumdes');
Route::resource('/proker/alokasi', PenambahanModalController::class)->only(['update', 'destroy']);

// Store Resiko Usaha
Route::post('/proker/resiko/store', [PenambahanModalController::class, 'resikoStore'])
    ->middleware('auth', 'langganan', 'bumdes')
    ->name('resiko.store');

// Update Resiko Usaha
Route::put('/proker/resiko/update/{id}', [PenambahanModalController::class, 'resikoUpdate'])
    ->middleware('auth', 'langganan', 'bumdes')
    ->name('resiko.update');

// Delete Resiko Usaha
Route::delete('/proker/resiko/delete/{id}', [PenambahanModalController::class, 'resikoDelete'])
    ->middleware('auth', 'langganan', 'bumdes')
    ->name('resiko.destroy');

Route::post('/update-status/{proker:id}', [PenambahanModalController::class, 'updateStatus'])->name('update.status')->middleware('auth', 'langganan', 'bumdes');
Route::put('/proker/penambahan/modal/{proker:id}', [PenambahanModalController::class, 'penambahanModalUpdate'])->middleware('auth', 'langganan', 'bumdes');

Route::get('/cetak/proker', [CetakProkerController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes')->middleware('auth', 'langganan', 'bumdes');

// LPJ
Route::get('/lpj', [LpjController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/lpj/{lpj:id}', [LpjController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/cetak/lpj', [LpjController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Bank
Route::resource('/aset/bank', BankController::class)->middleware(['auth', 'langganan', 'bumdes']);
Route::get('/aset/export-pdf/bank', [BankController::class, 'exportPdf'])->middleware(['auth', 'langganan', 'bumdes']);

// Terapkan 'cache.neraca' hanya pada metode yang mengubah data
Route::middleware(['auth', 'langganan', 'bumdes', 'cache.neraca'])->group(function () {
    Route::post('/aset/bank/update', [BankController::class, 'updateJumlah'])->name('Bank.update');
    Route::put('/aset/rekonsiliasi/bayar/{rekonsiliasi:id}', [BankController::class, 'bayar']);
});

// Akps
Route::get('/akp', [AkpsController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/akp/{akps:id}', [AkpsController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/akp/penjualan', [PenjualanController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/akp/kebutuhan', [KebutuhanController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/akp/pdf', [CetakAKPController::class, 'export'])->middleware('auth', 'langganan', 'bumdes');


// Undo
Route::get('/undo', [UndoController::class, 'undoController'])->name('undo')->middleware('cache.neraca');

Route::get('/wilayah/{type}/{id?}', function ($type, $id = null) {
    $url = "https://www.emsifa.com/api-wilayah-indonesia/api/{$type}/" . ($id ? "$id.json" : "33.json");
    return response()->json(json_decode(file_get_contents($url)));
});

Route::get('/api/wilayah/{type}/{id?}', function ($type, $id = null) {
    $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    $endpoint = match ($type) {
        'kabupaten' => "$baseUrl/regencies/33.json", // Jawa Tengah
        'kecamatan' => "$baseUrl/districts/$id.json",
        'desa' => "$baseUrl/villages/$id.json",
        default => abort(404),
    };

    $response = Http::get($endpoint);

    return $response->json();
});


// Auth
Auth::routes(['register' => false]);

// spj
Route::get('/spj', [SpjController::class, 'index'])->middleware(['auth', 'role_user:3']);

// arsip kelembagaan
Route::get('/spj/arsip_kelembagaan', [ArsipKelembagaanController::class, 'index'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/arsip_kelembagaan', [ArsipKelembagaanController::class,'store'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/arsip_kelembagaan/{id}', [ArsipKelembagaanController::class, 'update'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/arsip_kelembagaan/{id}', [ArsipKelembagaanController::class, 'destroy'])->middleware(['auth', 'role_user:3']);

// prosedur transaksi
Route::get('/spj/bukti_kas_masuk', [ProsedurTransaksiController::class, 'bukti_kas_masuk'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/bukti_kas_masuk', [ProsedurTransaksiController::class, 'store_kas_masuk'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/bukti_kas_masuk/{id}', [ProsedurTransaksiController::class, 'update_kas_masuk'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/bukti_kas_masuk/{id}', [ProsedurTransaksiController::class, 'destroy_kas_masuk'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/bukti_kas_masuk/print', [ProsedurTransaksiController::class, 'print_kas_masuk'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/bukti_kas_keluar', [ProsedurTransaksiController::class, 'bukti_kas_keluar'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/bukti_kas_keluar', [ProsedurTransaksiController::class, 'store_kas_keluar'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/bukti_kas_keluar/{id}', [ProsedurTransaksiController::class, 'update_kas_keluar'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/bukti_kas_keluar/{id}', [ProsedurTransaksiController::class, 'destroy_kas_keluar'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/bukti_kas_keluar/print', [ProsedurTransaksiController::class, 'print_kas_keluar'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/bukti_bank_masuk', [ProsedurTransaksiController::class, 'bukti_bank_masuk'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/bukti_bank_masuk', [ProsedurTransaksiController::class, 'store_bank_masuk'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/bukti_bank_masuk/{id}', [ProsedurTransaksiController::class, 'update_bank_masuk'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/bukti_bank_masuk/{id}', [ProsedurTransaksiController::class, 'destroy_bank_masuk'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/bukti_bank_masuk/print', [ProsedurTransaksiController::class, 'print_bank_masuk'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/bukti_bank_keluar', [ProsedurTransaksiController::class, 'bukti_bank_keluar'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/bukti_bank_keluar', [ProsedurTransaksiController::class, 'store_bank_keluar'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/bukti_bank_keluar/{id}', [ProsedurTransaksiController::class, 'update_bank_keluar'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/bukti_bank_keluar/{id}', [ProsedurTransaksiController::class, 'destroy_bank_keluar'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/bukti_bank_keluar/print', [ProsedurTransaksiController::class, 'print_bank_keluar'])->middleware(['auth', 'role_user:3']);

// arsip pembukuan
Route::get('/spj/arsip_pembukuan_1', [ArsipPembukuan1Controller::class, 'index'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/arsip_pembukuan_1/rekap', [ArsipPembukuan1Controller::class, 'print_rekap'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/arsip_pembukuan_1/delete/{id}', [ArsipPembukuan1Controller::class, 'delete'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/arsip_pembukuan_2', [ArsipPembukuan2Controller::class, 'index'])->middleware(['auth', 'role_user:3']);
Route::get('/spj/arsip_pembukuan_2/rekap', [ArsipPembukuan2Controller::class, 'print_rekap'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/arsip_pembukuan_2/delete/{id}', [ArsipPembukuan2Controller::class, 'delete'])->middleware(['auth', 'role_user:3']);


// arsip kegiatan
Route::get('/spj/arsip_surat_masuk', [ArsipSuratMasukController::class, 'index'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/arsip_surat_masuk', [ArsipSuratMasukController::class, 'store'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/arsip_surat_masuk/{id}', [ArsipSuratMasukController::class, 'update'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/arsip_surat_masuk/{id}', [ArsipSuratMasukController::class, 'destroy'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/arsip_surat_keluar', [ArsipSuratKeluarController::class, 'index'])->middleware(['auth', 'role_user:3']);
Route::post('/spj/arsip_surat_keluar', [ArsipSuratKeluarController::class, 'store'])->middleware(['auth', 'role_user:3']);
Route::put('/spj/arsip_surat_keluar/{id}', [ArsipSuratKeluarController::class, 'update'])->middleware(['auth', 'role_user:3']);
Route::delete('/spj/arsip_surat_keluar/{id}', [ArsipSuratKeluarController::class, 'destroy'])->middleware(['auth', 'role_user:3']);

Route::get('/spj/arsip_sop', [ArsipSopController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_sop', [ArsipSopController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_sop/{id}', [ArsipSopController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_sop/{id}', [ArsipSopController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_berita_acara', [ArsipBeritaAcaraController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_berita_acara', [ArsipBeritaAcaraController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_berita_acara/{id}', [ArsipBeritaAcaraController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_berita_acara/{id}', [ArsipBeritaAcaraController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_perjanjian_kerja', [ArsipPerjanjianKerjaController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_perjanjian_kerja', [ArsipPerjanjianKerjaController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_perjanjian_kerja/{id}', [ArsipPerjanjianKerjaController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_perjanjian_kerja/{id}', [ArsipPerjanjianKerjaController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_perjalanan_dinas', [ArsipPerjalananDinasController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_perjalanan_dinas', [ArsipPerjalananDinasController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_perjalanan_dinas/{id}', [ArsipPerjalananDinasController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_perjalanan_dinas/{id}', [ArsipPerjalananDinasController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_notulen_rapat', [ArsipNotulenRapatController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_notulen_rapat', [ArsipNotulenRapatController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_notulen_rapat/{id}', [ArsipNotulenRapatController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_notulen_rapat/{id}', [ArsipNotulenRapatController::class, 'destroy'])->middleware(['auth','role_user:3']);
Route::get('/spj/arsip_notulen_rapat/{id}/cetak',[ArsipNotulenRapatController::class, 'cetak'])->middleware(['auth','role_user:3']);

// arsip dokumentasi
Route::get('/spj/arsip_dokumentasi_foto', [ArsipDokumentasiFotoController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_dokumentasi_foto', [ArsipDokumentasiFotoController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_dokumentasi_foto/{id}', [ArsipDokumentasiFotoController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_dokumentasi_foto/{id}', [ArsipDokumentasiFotoController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_dokumentasi_video', [ArsipDokumentasiVideoController::class, 'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_dokumentasi_video', [ArsipDokumentasiVideoController::class, 'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_dokumentasi_video/{id}', [ArsipDokumentasiVideoController::class, 'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_dokumentasi_video/{id}', [ArsipDokumentasiVideoController::class, 'destroy'])->middleware(['auth','role_user:3']);

Route::get('/spj/arsip_dokumentasi_berkas_dokumen', [ArsipDokumentasiBerkasDokumenController::class,'index'])->middleware(['auth','role_user:3']);
Route::post('/spj/arsip_dokumentasi_berkas_dokumen', [ArsipDokumentasiBerkasDokumenController::class,'store'])->middleware(['auth','role_user:3']);
Route::put('/spj/arsip_dokumentasi_berkas_dokumen/{id}', [ArsipDokumentasiBerkasDokumenController::class,'update'])->middleware(['auth','role_user:3']);
Route::delete('/spj/arsip_dokumentasi_berkas_dokumen/{id}', [ArsipDokumentasiBerkasDokumenController::class,'destroy'])->middleware(['auth','role_user:3']);