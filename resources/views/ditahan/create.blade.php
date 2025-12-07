@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Tambah Data Pendapatan
                    </div>

                    <form action="/dithn" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" required
                                value="{{ old('tahun') }}">
                            @error('tahun')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hasil" class="form-label">Hasil</label>
                            <select name="hasil" id="hasil" class="form-control">
                                <option value="untung" {{ old('hasil') == 'untung' ? 'selected' : '' }}>Untung
                                </option>
                                <option value="rugi" {{ old('hasil') == 'rugi' ? 'selected' : '' }}>Rugi</option>
                            </select>
                            @error('hasil')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control" id="nilai" name="nilai"
                                onkeyup="onlyNumberAmount(this)" required
                                value="{{ old('nilai', formatNomor(labaRugi(session('selected_year', date('Y')))['totalLabaRugi'])) }}">
                            @error('nilai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pades" class="form-label">PADES</label>
                            <input type="text" class="form-control" id="pades" name="pades"
                                onkeyup="onlyNumberAmount(this)" required value="{{ old('pades') }}">
                            @error('pades')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lainya" class="form-label">Lainya</label>
                            <input type="text" class="form-control" id="lainya" name="lainya"
                                onkeyup="onlyNumberAmount(this)" required value="{{ old('lainya') }}">
                            @error('lainya')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="akumulasi" class="form-label">Akumulasi</label>
                            <input type="text" class="form-control" id="akumulasi" name="akumulasi"
                                onkeyup="onlyNumberAmount(this)" required value="{{ old('akumulasi') }}">
                            @error('akumulasi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/ditahan" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
