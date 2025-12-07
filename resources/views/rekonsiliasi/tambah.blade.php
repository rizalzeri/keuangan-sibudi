@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data Unit</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal"
                        data-bs-target="#tambahPosisiModal">
                        Tambah Posisi
                    </button>

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
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $rekonsiliasi->id }}Modal">
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
                                                    <input type="text" name="posisi" id="posisi" class="form-control"
                                                        value="{{ old('posisi', $rekonsiliasi) }}">
                                                    <label class="mt-3" for="jumlah">jumlah</label>
                                                    <input type="text" name="jumlah" id="jumlah" class="form-control"
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
