@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="card-title">
                        Edit Pinjaman
                    </div>

                    <form action="/aset/pinjaman/{{ $pinjaman->id }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Digunakan untuk metode HTTP PUT dalam update -->

                        <div class="mb-3">
                            <label for="nasabah" class="form-label">Nasabah</label>
                            <input type="text" name="nasabah" id="nasabah"
                                class="form-control @error('nasabah') is-invalid @enderror"
                                value="{{ old('nasabah', $pinjaman->nasabah) }}">
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
                                value="{{ old('tgl_pinjam', $pinjaman->tgl_pinjam) }}">
                            @error('tgl_pinjam')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alokasi" class="form-label">Alokasi</label>
                            <input type="text" name="alokasi" id="alokasi"
                                class="form-control @error('alokasi') is-invalid @enderror" onkeyup="onlyNumberAmount(this)"
                                value="{{ old('alokasi', formatNomor($pinjaman->alokasi)) }}">
                            @error('alokasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bunga" class="form-label">Bunga (%)</label>
                            <input type="text" name="bunga" id="bunga"
                                class="form-control @error('bunga') is-invalid @enderror"
                                value="{{ old('bunga', number_format($pinjaman->bunga, 1, ',', '')) }}">
                            @error('bunga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
