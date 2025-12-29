@php
    use App\Models\Tutup;
    use Illuminate\Support\Facades\Cache;

    // Ambil tahun yang dipilih, default ke tahun saat ini jika tidak ada
    $tahunDipilih = session('selected_year', date('Y'));

    $cacheKey = 'neraca_' . auth()->id() . '_' . $tahunDipilih;

    // Hapus cache jika tahun berubah
    if (session()->has('previous_selected_year') && session('previous_selected_year') != $tahunDipilih) {
        Cache::forget($cacheKey);
        Log::debug("Cache forgotten for year: $tahunDipilih"); // Debug log
    }

    // Simpan tahun saat ini sebagai referensi untuk perubahan berikutnya
    session(['previous_selected_year' => $tahunDipilih]);

    // Cek apakah ada data yang sudah ditutup di tabel 'Tutup'
    $tutup = Tutup::user()->where('tahun', $tahunDipilih)->first();

    if ($tutup) {
        // Jika ada, gunakan data dari tabel
        $neraca = json_decode($tutup->data, true);
    } else {
        // Jika tidak ada, ambil dari cache atau hitung ulang
        $neraca = Cache::rememberForever($cacheKey, function () use ($tahunDipilih) {
            Log::debug("Cache miss for year: $tahunDipilih"); // Debug log
            return neracaAktiva($tahunDipilih); // Pastikan fungsi `neracaAktiva` menerima $tahunDipilih
        });
    }

    // Pastikan 'aktiva' dan 'passiva' ada di array untuk menghindari error
    $aktiva = $neraca['aktiva'] ?? 0; // Jika 'aktiva' tidak ada, set 0
    $passiva = $neraca['passiva'] ?? 0; // Jika 'passiva' tidak ada, set 0

    // Cek keseimbangan neraca
    $neracaSeimbang = formatRupiah($aktiva) === formatRupiah($passiva);
@endphp

