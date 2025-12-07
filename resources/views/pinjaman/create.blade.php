@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="card-title">
                        Tambah Pinjaman
                    </div>

                    <form action="/aset/pinjaman" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nasabah" class="form-label">Nasabah</label>
                            <input type="text" name="nasabah" id="nasabah"
                                class="form-control @error('nasabah') is-invalid @enderror" value="{{ old('nasabah') }}">
                            @error('nasabah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam"
                                class="form-control @error('tgl_pinjam') is-invalid @enderror"
                                value="{{ old('tgl_pinjam') }}">
                            @error('tgl_pinjam')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alokasi" class="form-label">Alokasi</label>
                            <input type="text" name="alokasi" id="alokasi" onkeyup="onlyNumberAmount(this)"
                                class="form-control @error('alokasi') is-invalid @enderror" value="{{ old('alokasi') }}">
                            @error('alokasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="bunga" class="form-label">Bunga (%)</label>
                            <input type="text" name="bunga" id="bunga"
                                class="form-control @error('bunga') is-invalid @enderror" value="{{ old('bunga') }}">
                            @error('bunga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ture" name="no_kas"
                                id="flexCheckChecked" checked>
                            <label class="form-check-label" for="flexCheckChecked">
                                Masuk ke kas
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
