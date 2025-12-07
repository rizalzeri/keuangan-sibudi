@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- Nilai Hutang Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Akumulasi</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($total) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Hutang Card -->


            </div>
            <div class="card overflow-auto">
                <div class="card-body">
                    <div class="card-title">
                        Data laba ditahan
                    </div>


                    <div class="row cols-2 cols-lg-2">
                        <div class="col">

                            <a href="/dithn/create" class="btn btn-sm btn-primary mb-3">Tambah Data</a>
                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/dithn" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
                        </div>
                    </div>


                    <!-- Table with striped rows -->
                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Hasil</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">PADES</th>
                                <th scope="col">Lainya</th>
                                <th scope="col">Akumulasi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dithns as $dithn)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $dithn->tahun }}</td>
                                    <td>{{ $dithn->hasil }}</td>
                                    <td>{{ formatRupiah($dithn->nilai) }}</td>
                                    <td>{{ formatRupiah($dithn->pades) }}</td>
                                    <td>{{ formatRupiah($dithn->lainya) }}</td>
                                    <td>{{ formatRupiah($dithn->akumulasi) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/dithn/{{ $dithn->id }}/edit" class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i></a>
                                            <form action="/dithn/{{ $dithn->id }}" class="ms-2" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin dihapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
