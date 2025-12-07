@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Edit Aset
                    </div>

                    <form action="/aset/investasi/{{ $aset->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" class="form-control" id="item" name="item" required
                                value="{{ old('item', $aset->item) }}">
                            @error('item')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_beli" class="form-label">Tanggal Beli</label>
                            <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" required
                                value="{{ old('tgl_beli', $aset->tgl_beli) }}">
                            @error('tgl_beli')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required
                                value="{{ old('jumlah', $aset->jumlah) }}">
                            @error('jumlah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control" id="nilai" name="nilai"
                                onkeyup="onlyNumberAmount(this)" required
                                value="{{ old('nilai', number_format($aset->nilai, 0, ',', '.')) }}">
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
                        <a href="/aset" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
