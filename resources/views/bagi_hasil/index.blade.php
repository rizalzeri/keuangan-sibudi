@extends('layouts.main')

@section('container')
    <style>
        .section-heading {
            font-weight: bold;
        }

        .ditahan {
            background-color: limegreen;
            color: white;
        }

        .dibagi {
            background-color: red;
            color: white;
        }

        .form-control-inline {
            width: 80px;
            display: inline-block;
            text-align: center;
        }
    </style>

    <div class="container mt-4">
        <div class="card overflow-auto">
            <div class="card-body m-5">


                <h3 class="text-center">MENGHITUNG ALOKASI BAGI HASIL (JIKA UNTUNG)</h3>

                @php

                    $tambah = $labaBerjalan * ($ditahan->akumulasi / 100);
                    $pades = $labaBerjalan * ($ditahan->pades / 100);
                    $lainya = $labaBerjalan * ($ditahan->lainya / 100);

                @endphp


                <div class="row mb-3">
                    <label for="laba_berjalan" class="col-sm-2 col-form-label section-heading">LABA BERJALAN</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control text-center" id="laba_berjalan"
                            value="{{ formatRupiah($labaBerjalan) }}" disabled>
                    </div>
                </div>
                <form action="/bagi-hasil/{{ $ditahan->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3 ditahan p-2">
                        <label class="col-sm-2 col-form-label">DITAHAN</label>
                    </div>

                    <div class="row mb-3">
                        <label for="tambah_modal" class="col-sm-2 col-form-label">Tambah Modal</label>
                        <div class="col-sm-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="...%" id="tambah_modal"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    value="{{ $ditahan->akumulasi }}" name="akumulasi">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>


                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control text-center" id="tambah_modal_value" disabled
                                value="{{ formatRupiah($tambah) }}">
                        </div>
                    </div>

                    <div class="row mb-3 dibagi p-2">
                        <label class="col-sm-2 col-form-label">DIBAGI</label>
                    </div>

                    <div class="row mb-3">
                        <label for="pades" class="col-sm-2 ">PADes</label>

                        <div class="col-sm-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="...%" id="tambah_modal"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    value="{{ $ditahan->pades }}" name="pades">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control text-center" id="pades_value"disabled
                                value="{{ formatRupiah($pades) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="lainya" class="col-sm-2 col-form-label">Lainnya</label>
                        <div class="col-sm-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="...%" id="tambah_modal"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    value="{{ $ditahan->lainya }}" name="lainya">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control text-center" id="lainya_value" disabled
                                value="{{ formatRupiah($lainya) }}">
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
