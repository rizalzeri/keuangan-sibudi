@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="card-title">
                        Tambah Barang
                    </div>

                    <form action="/aset/persediaan" method="POST" id="myForm">
                        @csrf
                        <!-- Tangal Field -->
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at" value="{{ old('created_at') }}">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" name="item" id="item"
                                class="form-control @error('item') is-invalid @enderror" value="{{ old('item') }}">
                            @error('item')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" id="satuan"
                                class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan') }}">
                            @error('satuan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hpp" class="form-label">HPP (Rp)</label>
                            <input type="text" onkeyup="onlyNumberAmount(this)" name="hpp" id="hpp"
                                class="form-control @error('hpp') is-invalid @enderror" value="{{ old('hpp') }}">
                            @error('hpp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="nilai_jual" class="form-label">Nilai Jual (Rp)</label>
                            <input type="text" name="nilai_jual" id="nilai_jual" onkeyup="onlyNumberAmount(this)"
                                class="form-control @error('nilai_jual') is-invalid @enderror"
                                value="{{ old('nilai_jual') }}">
                            @error('nilai_jual')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jml_awl" class="form-label">Jumlah Awal</label>
                            <input type="text" name="jml_awl" id="jml_awl" onkeyup="onlyNumberAmount(this)"
                                class="form-control @error('jml_awl') is-invalid @enderror" value="{{ old('jml_awl') }}">
                            @error('jml_awl')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>




                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
