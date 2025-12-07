@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <!-- Nilai Hutang Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| kas bank</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($rekonsiliasis->sum('jumlah')) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Hutang Card -->


            </div>

            <div class="card overflow-auto">
                <div class="card-body">
                    <h3 class="card-title">Kas Bank</h3>

                    <div class="row cols-2 cols-lg-2">
                        <div class="col">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#tambahPosisiModal">
                                Tambah Posisi
                            </button>
                        </div>
                        <div class="col text-end">
                            <a href="/aset/export-pdf/bank" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i>
                                PDF</a>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="tambahPosisiModal" tabindex="-1" aria-labelledby="tambahPosisiModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Posisi</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="/rekonsiliasi" method="POST">
                                    <div class="modal-body">
                                        @csrf
                                        <label for="posisi">Nama Posisi</label>
                                        <input type="text" name="posisi" class="form-control" id="posisi">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Posisi Kas</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($rekonsiliasis as $rekonsiliasi)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $rekonsiliasi->posisi }}</td>
                                    <td>{{ formatRupiah($rekonsiliasi->jumlah) }}</td>

                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a data-bs-toggle="modal" class="btn btn-info btn-sm"
                                                data-bs-target="#bayar{{ $rekonsiliasi->id }}Modal">
                                                <i class="bi bi-credit-card"></i>
                                            </a>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-success ms-2"
                                                data-bs-toggle="modal" data-bs-target="#edit{{ $rekonsiliasi->id }}Modal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>


                                            <form action="/rekonsiliasi/{{ $rekonsiliasi->id }}" class="ms-2"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin dihapus?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Pembayaran -->
                                <div class="modal fade" id="bayar{{ $rekonsiliasi->id }}Modal" tabindex="-1"
                                    aria-labelledby="bayar{{ $rekonsiliasi->id }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="bayar{{ $rekonsiliasi->id }}ModalLabel">
                                                    Bayar
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/aset/rekonsiliasi/bayar/{{ $rekonsiliasi->id }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <label for="bayar" class="mb-3">Aksi</label><br>
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $rekonsiliasi->id }}1" value="+"
                                                            autocomplete="off" checked>
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $rekonsiliasi->id }}1">Tambah</label>

                                                        <input type="radio" class="btn-check" name="aksi"
                                                            id="aksi{{ $rekonsiliasi->id }}2" value="-"
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary"
                                                            for="aksi{{ $rekonsiliasi->id }}2">Kurang</label>
                                                    </div>


                                                    <br class=" mt-3 mb-3">
                                                    <label for="bayar">Jumlah Pembayaran</label>
                                                    <input type="text" name="jumlah" id="bayar"
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
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ $rekonsiliasi->id }}Modal" tabindex="-1"
                                    aria-labelledby="edit{{ $rekonsiliasi->id }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="edit{{ $rekonsiliasi->id }}ModalLabel">
                                                    Edit {{ $rekonsiliasi->posisi }}
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="/rekonsiliasi/{{ $rekonsiliasi->id }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-body">
                                                    <label class="mt-3" for="posisi">Nama Posisi</label>
                                                    <input type="text" name="posisi" id="posisi"
                                                        class="form-control" value="{{ old('posisi', $rekonsiliasi) }}">
                                                    <label class="mt-3" for="jumlah">jumlah</label>
                                                    <input type="text" name="jumlah" id="jumlah"
                                                        class="form-control"
                                                        value="{{ old('jumlah', formatNomor($rekonsiliasi->jumlah)) }}"
                                                        onkeyup="onlyNumberAmount(this)">


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Kembali</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
