@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="card-title">Menghitung Akumulasi Penyusutan Tahunan</div>

                    <table class="table  table-bordered">
                        <tr>
                            <td class="p-2">Penyusutan Investasi</td>
                            <td class="p-2"><input type="text" class="form-control" disabled
                                    value="{{ formatRupiah($investasi) }}"></td>
                        </tr>
                        <tr>
                            <td class="p-2">Biaya dibayar dimuka</td>
                            <td class="p-2"><input type="text" class="form-control" disabled
                                    value="{{ formatRupiah($bdmuk) }}"></td>
                        </tr>
                        <tr>
                            <td class="p-2">Bangunan</td>
                            <td class="p-2"><input type="text" class="form-control" disabled
                                    value="{{ formatRupiah($bangunan) }}"></td>
                        </tr>
                        <tr>
                            <td class="p-2">Aktiva Lain</td>
                            <td class="p-2"><input type="text" class="form-control" disabled
                                    value="{{ formatRupiah($aktiva) }}"></td>
                        </tr>
                        <tr class="bg-info fw-bold">
                            <td class="p-2">Total Biaya Akumulasi Penyusutan
                            </td>
                            <td class="p-2">
                                <span class="ms-3">{{ formatRupiah($total) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
