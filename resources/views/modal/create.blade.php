@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Tambah Modal</div>
                    <form action="/modal" method="POST">
                        @csrf

                        <!-- Tahun Field -->
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun"
                                name="tahun" value="{{ old('tahun') }}">
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sumber Field -->
                        <div class="mb-3">
                            <label for="sumber" class="form-label">Sumber</label>
                            <input type="text" class="form-control @error('sumber') is-invalid @enderror" id="sumber"
                                name="sumber" value="{{ old('sumber') }}">
                            @error('sumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('referral')
                            <!-- MDL Desa Field -->
                            <div class="mb-3">
                                <label for="mdl_desa" class="form-label">Simpanan Pokok</label>
                                <input type="text" onkeyup="onlyNumberAmount(this)"
                                    class="form-control @error('mdl_desa') is-invalid @enderror" id="mdl_desa" name="mdl_desa"
                                    value="{{ old('mdl_desa') }}">
                                @error('mdl_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MDL Masyarakat Field -->
                            <div class="mb-3">
                                <label for="mdl_masyarakat" class="form-label"> Simpanan Wajib</label>
                                <input type="text" onkeyup="onlyNumberAmount(this)"
                                    class="form-control @error('mdl_masyarakat') is-invalid @enderror" id="mdl_masyarakat"
                                    name="mdl_masyarakat" value="{{ old('mdl_masyarakat') }}">
                                @error('mdl_masyarakat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- MDL bersama Field -->
                            <div class="mb-3">
                                <label for="mdl_bersama" class="form-label">Simpanan Sukarela</label>
                                <input type="text" onkeyup="onlyNumberAmount(this)"
                                    class="form-control @error('mdl_bersama') is-invalid @enderror" id="mdl_bersama"
                                    name="mdl_bersama" value="{{ old('mdl_bersama') }}">
                                @error('mdl_bersama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <!-- MDL Desa Field -->
                            <div class="mb-3">
                                <label for="mdl_desa" class="form-label">Modal Desa</label>
                                <input type="text" onkeyup="onlyNumberAmount(this)"
                                    class="form-control @error('mdl_desa') is-invalid @enderror" id="mdl_desa" name="mdl_desa"
                                    value="{{ old('mdl_desa') }}">
                                @error('mdl_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MDL Masyarakat Field -->
                            <div class="mb-3">
                                <label for="mdl_masyarakat" class="form-label">Modal Masyarakat</label>
                                <input type="text" onkeyup="onlyNumberAmount(this)"
                                    class="form-control @error('mdl_masyarakat') is-invalid @enderror" id="mdl_masyarakat"
                                    name="mdl_masyarakat" value="{{ old('mdl_masyarakat') }}">
                                @error('mdl_masyarakat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endcan

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ture" name="no_kas"
                                id="flexCheckChecked" checked>
                            <label class="form-check-label" for="flexCheckChecked">
                                Masuk ke kas
                            </label>
                        </div>



                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
