@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Rincian Pernyertaan Modal</h1>
            </div>



            <div class="row">
                <!-- Modal Desa Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Modal <span>| Desa</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($modals->sum('mdl_desa')) }}</h6>


                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Modal Desa Card -->

                <!-- Modal MasyarakatCard -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Modal <span>| Masyarakat</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($modals->sum('mdl_masyarakat')) }}</h6>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Modal MasyarakatCard -->
            </div>
            <div class="card overflow-auto">
                <div class="card-body">

                    <h5 class="card-title">Data Modal</h5>
                    <div class="row cols-2 cols-lg-2">
                        <div class="col">
                            <a href="/modal/create" class="btn btn-sm btn-primary"> Tambah Modal</a>
                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/modal" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
                        </div>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Sumber</th>
                                @can('referral')
                                    <th scope="col">Simpanan Pokok</th>
                                    <th scope="col">Simpanan Wajib</th>
                                    <th scope="col">Simpanan Sukarela</th>
                                @else
                                    <th scope="col">Modal Desa</th>
                                    <th scope="col">Modal Masyarakat</th>
                                @endcan
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($modals as $modal)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $modal->tahun }}</td>
                                    <td>{{ $modal->sumber }}</td>
                                    <td>{{ formatRupiah($modal->mdl_desa) }}</td>
                                    <td>{{ formatRupiah($modal->mdl_masyarakat) }}</td>
                                    @can('referral')
                                        <td>{{ formatRupiah($modal->mdl_bersama) }}</td>
                                    @endcan
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/modal/{{ $modal->id }}/edit" class="btn btn-sm btn-success"> <i
                                                    class="bi bi-pencil-square"></i></a>
                                            <form action="/modal/{{ $modal->id }}" class="ms-2" method="POST">
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
