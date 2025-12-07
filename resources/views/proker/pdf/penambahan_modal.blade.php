<p>G. Rencana Penambahan Modal</p>
<div class="isi">
    <p>1. Deskripsi Unit Usaha yang akan dikembangkan</p>
    <div class="isi">
        <p>
            Nama unit/keterangan : {{ $proker->unit_usaha }}
        </p>

        <p>
            Status unit usaha : {{ $proker->status_unit }}
        </p>
    </div>
    <p>2. Nilai Pengajuan Penambahan Penyertaan Modal</p>
    <div class="isi">
        <p>
            Nilai : {{ formatRupiah($proker->jumlah) }}
        </p>
        <p>
            Rincian Penggunaan Modal:
        </p>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Jenis Biaya</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($alokasis as $alokasi)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $alokasi->item }}</td>
                        <td>{{ $alokasi->jenis_biaya }}</td>
                        <td>{{ formatRupiah($alokasi->nilai) }}</td>
                    </tr>
                @endforeach
                @if (count($alokasis) <= 0)
                    <tr class="text-center">
                        <td colspan="4">Data alokasi kosong</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
    <p>3. Analisa Kelayakan Usaha</p>
    <div class="isi">
        <p>
            a. Aspek pasar dan pemasaran
        </p>
        <div class="isi">
            <p>{!! $proker->aspek_pasar !!}</p>
        </div>
        <p>
            b. Aspek Keuangan
        </p>
        <div>

            <style>
                /* Style untuk tabel */
                .financial-table {
                    width: 100%;
                    border-collapse: collapse;

                    border: none !important;
                }

                /* Style untuk sel dalam tabel */
                .financial-table td {
                    border: none !important;
                    padding: 0px 20px;
                    vertical-align: middle;
                }

                /* Membuat teks 'Rp' lebih tebal */
                .financial-table td span {
                    font-weight: bold;
                    border: none !important;
                }

                /* Input dengan border bawah saja */
                .dotted-input {
                    border: none;
                    border-bottom: 1px dotted #000;
                    outline: none;
                    width: 100%;
                    padding: 5px;
                    font-size: 14px;
                    background: transparent;
                    text-align: start;

                }
            </style>
            @php
                // Decode JSON menjadi array, jika null, set array kosong
                $aspek_keuangan = json_decode($proker->aspek_keuangan, true) ?? [];

                // Pastikan array tidak kosong sebelum mengakses indeks pertama
                $data_keuangan = $aspek_keuangan[0] ?? [];
            @endphp
            {{-- <table class="financial-table mt-3">
                <tr>
                    <td>Pendapatan Unit dalam setahun</td>
                    <td>
                        <input type="text" name="aspek_keuangan[0][pendapatan]"
                            value="{{ formatRupiah(old('aspek_keuangan.0.pendapatan', $data_keuangan['pendapatan'] ?? '')) }}"
                            class="dotted-input">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Biaya operasional dalam setahun</td>
                    <td>
                        <input type="text" name="aspek_keuangan[0][biaya]"
                            value="{{ formatRupiah(old('aspek_keuangan.0.biaya', $data_keuangan['biaya'] ?? '')) }}"
                            class="dotted-input">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Laba diperoleh dalam satu tahun</td>
                    <td>
                        <input type="text" name="aspek_keuangan[0][laba]"
                            value="{{ formatRupiah(old('aspek_keuangan.0.laba', $data_keuangan['laba'] ?? '')) }}"
                            class="dotted-input">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Pengembalian Modal (ROI)</td>
                    <td>
                        <input type="text" name="aspek_keuangan[0][pengembalian]"
                            value="{{ formatRupiah(old('aspek_keuangan.0.pengembalian', $data_keuangan['pengembalian'] ?? '')) }}"
                            class="dotted-input">
                    </td>
                    <td>/Tahun</td>
                </tr>
            </table> --}}
            <div class="isi">
                {!! old('aspek_keuangan.0.rincian', $data_keuangan['rincian'] ?? '') !!}
            </div>


        </div>
        <p>
            c. Aspek Teknis
        </p>
        <div class="isi">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Resiko</th>
                        <th>Penyebab</th>
                        <th>Antisipasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    <!-- Contoh Data -->
                    @foreach ($rasios as $rasio)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $rasio->resiko }}</td>
                            <td>{{ $rasio->penyebab }}</td>
                            <td>{{ $rasio->antisipasi }}</td>
                        </tr>
                    @endforeach
                    @if (count($rasios) <= 0)
                        <tr class="text-center">
                            <td colspan="4">Data rasio kosong</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <p>
            d. Aspek lainnya
        </p>
        <div class="isi">
            <p class="p-0">{!! $proker->aspek_lainya !!}</p>

        </div>

        <p>4. Strategi Pemasaran</p>
        <div class="isi">
            <p class="p-0">
                {!! $proker->strategi_pemasaran !!}
            </p>
        </div>

        <p>5. Kesimpulan tentang usaha yang akan dirintis/dikembangkan</p>
        <div class="isi">
            <p class="p-0">
                {!! $proker->kesimpulan !!}
            </p>
        </div>
    </div>
