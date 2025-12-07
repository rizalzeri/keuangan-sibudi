@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- Nilai Hutang Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Saldo</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($saldo) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Hutang Card -->


            </div>
            <div class="card overflow-auto">
                <div class="card-body">
                    <div class="card-title">
                        Buku Kas
                    </div>


                    <div class="row cols-2 cols-lg-2">
                        <div class="col">
                            <a href="/aset/buk/create" class="btn btn-sm btn-primary mb-3">Tambah Data</a>
                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/buk" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
                        </div>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped table-hover  table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">(Masuk/Keluar)</th>
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Saldo</th>
                                <th scope="col">Pembukuan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $saldo = 0;
                            @endphp
                            @if ($saldo_lalu != 0)
                                @php
                                    $saldo = $saldo_lalu;
                                @endphp

                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>1 januari {{ session('selected_year', date('Y')) }}</td>
                                    <td>Saldo Awal</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ formatRupiah($saldo) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                            @foreach ($transaksis as $transaksi)
                                @php
                                    if ($transaksi->jenis == 'debit') {
                                        $saldo = $transaksi->nilai + $saldo;
                                    } elseif ($transaksi->jenis == 'kredit') {
                                        $saldo = $saldo - $transaksi->nilai;
                                        # code...
                                    }

                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($transaksi->tanggal) }}</td>
                                    <td>{{ $transaksi->transaksi }}</td>
                                    <td>
                                        <p
                                            class="{{ $transaksi->jenis == 'debit' ? 'text-success' : ($transaksi->jenis == 'kredit' ? 'text-danger' : 'text-warning') }} fw-bold">
                                            {{ $transaksi->jenis == 'debit' ? 'Masuk' : ($transaksi->jenis == 'kredit' ? 'Keluar' : 'Tetap') }}
                                        </p>
                                    </td>
                                    <td>
                                        <p
                                            class="{{ $transaksi->jenis == 'debit' ? 'text-success' : ($transaksi->jenis == 'kredit' ? 'text-danger' : 'text-warning') }} fw-bold">
                                            {{ $transaksi->jenis_dana }}
                                        </p>
                                    </td>

                                    <td>{{ formatRupiah($transaksi->nilai) }}</td>
                                    <td>{{ formatRupiah($saldo) }}</td>
                                    <td>
                                        @foreach (namaUnitUsaha() as $key => $value)
                                            @if ($transaksi->jenis_lr == $key)
                                                {{ $value }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/aset/buk/{{ $transaksi->id }}/edit" class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i></a>
                                            <form action="/aset/buk/{{ $transaksi->id }}" class="ms-2" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin dihapus?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-warning fw-bold">
                                <td colspan="6">Total Saldo</td>
                                <td>{{ formatRupiah($saldo) }}</td>
                                <td colspan="3"></td>

                            </tr>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>

    </div>
@endsection
