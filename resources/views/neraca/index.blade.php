@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-10">
            <div class="card overflow-auto">
                <div class="card-body">
                    <div class="d-flex justify-content-start mt-3">

                        <a href="/export-pdf/neraca" class="btn btn-danger "><i class="bi bi-filetype-pdf"></i>
                            PDF</a>

                        @if (session('selected_year', date('Y')) <= date('Y'))
                            <form
                                action="{{ $setatus == true ? '/laporan-keuangan/neraca/tutup/delete/' . $data_tutup->id : '/laporan-keuangan/neraca/tutup' }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn {{ $setatus == true ? 'btn-warning' : 'btn-primary' }} ms-3"
                                    onclick="return confirm('Apakah yakin neraca mau lanjut?')"><i
                                        class="bi
                                bi-book-half"></i>
                                    {{ $setatus == true ? 'Buka Kembali' : 'Tutup Buku' }}</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-title">1. Laporan Neraca</div>

                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">
                                    Aktiva
                                </th>
                                <th colspan="2">
                                    Passiva
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kas atau setara Kas</td>
                                <td>{{ formatRupiah($kas) }}</td>
                                <td>Hutang</td>
                                <td>{{ formatRupiah($hutang) }}</td>
                            </tr>
                            <tr>
                                <td>Bank</td>
                                <td>{{ formatRupiah($bank) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Piutang</td>
                                <td>{{ formatRupiah($piutang) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @can('referral')
                                <tr>
                                    <td>Saldo Pinjam</td>
                                    <td>{{ formatRupiah($saldo_pinjam) }}</td>
                                    <td>Simpanan Pokok</td>
                                    <td>{{ formatRupiah($modal_desa) }}</td>
                                </tr>
                                <tr>
                                    <td>Persediaan dagang</td>
                                    <td>{{ formatRupiah($persediaan_dagang) }}</td>
                                    <td>Simpanan Wajib</td>
                                    <td>{{ formatRupiah($modal_masyarakat) }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Dibayar di muka</td>
                                    <td>{{ formatRupiah($bayar_dimuka) }}</td>
                                    <td>Simpanan Sukarela</td>
                                    <td>{{ formatRupiah($modal_bersama) }}</td>
                                </tr>
                                <tr>
                                    <td>Iventaris</td>
                                    <td>{{ formatRupiah($investasi) }}</td>
                                    <td>{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </td>
                                    <td>{{ formatRupiah($ditahan) }}</td>
                                <tr>
                                    <td>Bangunan</td>
                                    <td>{{ formatRupiah($bangunan) }}</td>
                                    <td>{{ $laba_rugi_berjalan < 0 ? 'Rugi' : 'Laba' }} Berjalan <strong>
                                        </strong></td>
                                    <td>{{ formatRupiah($laba_rugi_berjalan) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>Saldo Pinjam</td>
                                    <td>{{ formatRupiah($saldo_pinjam) }}</td>
                                    <td>Pernyertaan Modal Desa</td>
                                    <td>{{ formatRupiah($modal_desa) }}</td>
                                </tr>
                                <tr>
                                    <td>Persediaan dagang</td>
                                    <td>{{ formatRupiah($persediaan_dagang) }}</td>
                                    <td>Pernyertaan Modal Masyarakat</td>
                                    <td>{{ formatRupiah($modal_masyarakat) }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Dibayar di muka</td>
                                    <td>{{ formatRupiah($bayar_dimuka) }}</td>
                                    <td>{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </td>
                                    <td>{{ formatRupiah($ditahan) }}</td>
                                </tr>
                                <tr>
                                    <td>Iventaris</td>
                                    <td>{{ formatRupiah($investasi) }}</td>
                                    <td>{{ $laba_rugi_berjalan < 0 ? 'Rugi' : 'Laba' }} Berjalan <strong>
                                        </strong></td>
                                    <td>{{ formatRupiah($laba_rugi_berjalan) }}</td>

                                <tr>
                                    <td>Bangunan</td>
                                    <td>{{ formatRupiah($bangunan) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endcan
                            <tr>
                                <td>Aktiva Lain</td>
                                <td>{{ formatRupiah($aktiva_lain) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="fw-bold">
                                <td>Total Aktiva</td>
                                <td>{{ formatRupiah($total_aktiva) }}</td>
                                <td>Total Pasiva</td>
                                <td>{{ formatRupiah($passiva) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
