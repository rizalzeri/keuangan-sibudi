@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data Barang</h1>
            </div>

            <div class="row">
                <!-- Total Pinjaman Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Nilai Awal</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($total_nilai_awal) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Total Nilai Awal Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Nilai Akhir</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($total_nilai_akhir) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Total Nilai Akhir Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Laba</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($total_laba) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Total Laba Card -->
            </div>

            <div class="card overflow-auto">
                <div class="card-body">
                    <h5 class="card-title">Daftar Barang</h5>


                    <div class="row cols-2 cols-lg-2">
                        <div class="col">
                            <a href="/aset/persediaan/create" class="btn btn-sm btn-primary mb-3">Tambah Barang</a>
                            <a href="/aset/persediaan/reset/set-ulang" class="btn btn-sm btn-warning mb-3"
                                onclick="return confirm('Apakah yakin mau di reset?')"><i
                                    class="bi bi-arrow-counterclockwise"></i> Reset</a>

                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/persediaan" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i>
                                PDF</a>
                        </div>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable  table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Item</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">HPP</th>
                                <th scope="col">Nilai_Jual</th>
                                <th scope="col">Jumlah_Awal</th>
                                <th scope="col">Nilai_Awal</th>
                                <th scope="col">_____________Penjualan___________</th>
                                <th scope="col">______Pembelian_____</th>
                                <th scope="col">Jumlah_Akhir</th>
                                <th scope="col">Nilai_Akhir</th>
                                <th scope="col">Laba</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($barangs as $barang)
                                @php
                                    $nilai_awal = $barang->jml_awl * $barang->hpp;
                                    $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);
                                    $jumlah = $barang->jml_awl - $barang->masuk;
                                    $nilai_akhir = $jumlah_akhir * $barang->hpp;
                                    $laba = ($barang->jml_awl - $jumlah) * ($barang->nilai_jual - $barang->hpp);
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($barang->created_at) }}</td>
                                    <td>{{ $barang->item }}</td>
                                    <td>{{ $barang->satuan }}</td>
                                    <td>{{ formatRupiah($barang->hpp) }}</td>
                                    <td>{{ formatRupiah($barang->nilai_jual) }}</td>
                                    <td>
                                        <p class="text-center">{{ formatNomor($barang->jml_awl) }}</p>
                                    </td>
                                    <td>{{ formatRupiah($nilai_awal) }}</td>
                                    <td>

                                        <div class="d-flex justify-content-start">
                                            <form action="/aset/persedian/jual/{{ $barang->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group mb-3">
                                                    <div class="form-check  ">
                                                        <input class="form-check-input" type="checkbox" value="true"
                                                            name="no_kas" id="flexCheckChecked">
                                                        <label class="form-check-label" for="flexCheckChecked">
                                                            Hilang
                                                        </label>
                                                    </div>
                                                    <input type="number" class="form-control ms-2"
                                                        aria-describedby="button-addon2"
                                                        value="{{ $barang->masuk == null ? 0 : $barang->masuk }}"
                                                        name="masuk"
                                                        min="{{ $barang->masuk == null ? 0 : $barang->masuk }}">
                                                    <button class="btn btn-sm btn-success" type="submit"
                                                        id="button-addon2"><i class="bi bi-arrow-up-circle"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">

                                            <form action="/aset/persedian/jual/{{ $barang->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control"
                                                        aria-describedby="button-addon2"
                                                        value="{{ $barang->keluar == null ? 0 : $barang->keluar }}"
                                                        name="keluar"
                                                        min="{{ $barang->keluar == null ? 0 : $barang->keluar }}">
                                                    <button class="btn btn-sm btn-danger" type="submit"
                                                        id="button-addon2"><i class="bi bi-arrow-down-circle"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ formatNomor($jumlah_akhir) }}</p>
                                    </td>
                                    <td>{{ formatRupiah($nilai_akhir) }}</td>
                                    <td>{{ formatRupiah($laba) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/aset/persediaan/{{ $barang->id }}/edit"
                                                class="btn btn-sm btn-success"> <i class="bi bi-pencil-square"></i></a>
                                            <form action="/aset/persediaan/{{ $barang->id }}" class="ms-2"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin di Hapus?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
