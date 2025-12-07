@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Rincian Hutang</h1>
            </div>

            <div class="row">
                <!-- Nilai Hutang Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Sisa Hutang</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($sisa) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Hutang Card -->


            </div>

            <div class="card overflow-auto">
                <div class="card-body">

                    <h5 class="card-title">Data Hutang</h5>

                    <div class="row cols-2 cols-lg-2">
                        <div class="col">

                            <a href="/hutang/create" class="btn btn-sm btn-primary"> Tambah Hutang</a>
                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/hutang" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
                        </div>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kreditur</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Pembayaran</th>
                                <th scope="col">Sisa</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($hutangs as $hutang)
                                @php
                                    $sisa = $hutang->nilai - $hutang->pembayaran;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $hutang->kreditur }}</td>
                                    <td>{{ $hutang->keterangan }}</td>
                                    <td>{{ formatRupiah($hutang->nilai) }}</td>
                                    <td>{{ formatRupiah($hutang->pembayaran) }}</td>
                                    <td>{{ formatRupiah($sisa) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a data-bs-toggle="modal" class="btn btn-info btn-sm"
                                                data-bs-target="#bayar{{ $hutang->id }}Modal">
                                                <i class="bi bi-credit-card"></i>
                                            </a>


                                            <a href="/hutang/{{ $hutang->id }}/edit" class="btn btn-sm btn-success ms-2">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="/hutang/{{ $hutang->id }}" class="ms-2" method="POST">
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
                                <div class="modal fade" id="bayar{{ $hutang->id }}Modal" tabindex="-1"
                                    aria-labelledby="bayar{{ $hutang->id }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="bayar{{ $hutang->id }}ModalLabel">Bayar
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/hutang/bayar/{{ $hutang->id }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <label for="bayar" class="mb-3">Aksi</label><br>
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $hutang->id }}1" value="+" autocomplete="off"
                                                            checked>
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $hutang->id }}1">Tambah</label>

                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $hutang->id }}2" value="-"
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $hutang->id }}2">Kurang</label>
                                                    </div>


                                                    <br class=" mt-3 mb-3">
                                                    <label for="bayar">Jumlah Pembayaran</label>
                                                    <input type="text" name="pembayaran" onkeyup="onlyNumberAmount(this)"
                                                        id="rupiah" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Bayar</button>
                                            </div>
                                            </form>
                                        </div>
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
