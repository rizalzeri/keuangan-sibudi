@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Rincian Pinjaman</h1>
            </div>

            <div class="row">
                <!-- Total Pinjaman Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Sisa Pinjaman</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($tunggakan) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Total Pinjaman Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Saldo Bunga</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($saldo_bunga) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Total Pinjaman Card -->
            </div>

            <div class="card overflow-auto">
                <div class="card-body">

                    <h5 class="card-title">Data Pinjaman</h5>

                    <div class="row cols-2 cols-lg-2">
                        <div class="col">

                            <a href="/aset/pinjaman/create" class="btn btn-sm btn-primary"> Tambah Pinjaman</a>
                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/pinjaman" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
                        </div>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nasabah</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Alokasi</th>
                                <th scope="col">Realisasi</th>
                                <th scope="col">Angsuran</th>
                                <th scope="col">Sisa Pokok</th>
                                <th scope="col">Bunga</th>
                                <th scope="col">Bunga Pokok</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($pinjamans as $pinjaman)
                                @php
                                    $sisa = $pinjaman->alokasi - $pinjaman->realisasi;
                                    $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);
                                    $total_bunga = $bunga * $pinjaman->angsuran;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>

                                    <td>{{ $pinjaman->nasabah }}</td>
                                    <td>{{ date('Y-m-d', strtotime($pinjaman->tgl_pinjam)) }}</td>
                                    <td>{{ formatRupiah($pinjaman->alokasi) }}</td>
                                    <td>{{ formatRupiah($pinjaman->realisasi) }}</td>
                                    <td>{{ $pinjaman->angsuran }}</td>
                                    <td>{{ formatRupiah($sisa) }}</td>
                                    <td>{{ $pinjaman->bunga }}%</td>
                                    <td>{{ formatRupiah($total_bunga) }}</td>

                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a data-bs-toggle="modal" class="btn btn-info btn-sm"
                                                data-bs-target="#bayar{{ $pinjaman->id }}Modal">
                                                <i class="bi bi-credit-card"></i>
                                            </a>

                                            <a href="/aset/pinjaman/{{ $pinjaman->id }}/edit"
                                                class="btn btn-sm btn-success ms-2">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="/aset/pinjaman/{{ $pinjaman->id }}" class="ms-2"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin di Hapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="bayar{{ $pinjaman->id }}Modal" tabindex="-1"
                                    aria-labelledby="bayar{{ $pinjaman->id }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="bayar{{ $pinjaman->id }}ModalLabel">Bayar
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/aset/pinjaman/bayar/{{ $pinjaman->id }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <label for="bayar" class="mb-3">Aksi</label><br>
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $pinjaman->id }}1" value="+" autocomplete="off"
                                                            checked>
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $pinjaman->id }}1">Tambah</label>

                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $pinjaman->id }}2" value="-"
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $pinjaman->id }}2">Kurang</label>
                                                    </div>

                                                    <br class=" mt-3 mb-3">
                                                    <label for="bayar">Jumlah Realisasi</label>
                                                    <input type="text" name="realisasi" id="bayar"
                                                        onkeyup="onlyNumberAmount(this)" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Bayar</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
