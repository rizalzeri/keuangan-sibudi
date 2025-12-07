@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Edit Aset
                    </div>

                    <form action="/aset/bangunan/{{ $aset->id }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Untuk mengindikasikan bahwa ini adalah permintaan update -->


                        <!-- Tangal Field -->
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at"
                                value="{{ old('created_at', tanggal($aset->created_at)) }}">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">jenis Bangunan</label>
                            <input type="text" class="form-control" id="jenis" name="jenis" required
                                value="{{ old('jenis', $aset->jenis) }}">
                            @error('jenis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control rupiah" id="nilai" name="nilai"
                                onkeyup="onlyNumberAmount(this)" required
                                value="{{ old('nilai', formatNomor($aset->nilai)) }}">
                            @error('nilai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="wkt_ekonomis" class="form-label">Waktu Ekonomis (Tahun)</label>
                            <input type="number" class="form-control" id="wkt_ekonomis" name="wkt_ekonomis" required
                                value="{{ old('wkt_ekonomis', $aset->wkt_ekonomis) }}">
                            @error('wkt_ekonomis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/aset/bangunan" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
