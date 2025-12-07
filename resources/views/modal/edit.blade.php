@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Modal</h5>
                    <form action="/modal/{{ $modal->id }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Use PUT or PATCH for update -->

                        <!-- Tahun Field -->
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun"
                                name="tahun" value="{{ old('tahun', $modal->tahun) }}">
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sumber Field -->
                        <div class="mb-3">
                            <label for="sumber" class="form-label">Sumber</label>
                            <input type="text" class="form-control @error('sumber') is-invalid @enderror" id="sumber"
                                name="sumber" value="{{ old('sumber', $modal->sumber) }}">
                            @error('sumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('referral')
                            <!-- MDL Desa Field -->
                            <div class="mb-3">
                                <label for="mdl_desa" class="form-label">Simpanan Pokok</label>
                                <input type="text" class="form-control @error('mdl_desa') is-invalid @enderror"
                                    onkeyup="onlyNumberAmount(this)" id="mdl_desa" name="mdl_desa"
                                    value="{{ old('mdl_desa', formatNomor($modal->mdl_desa)) }}">
                                @error('mdl_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MDL Masyarakat Field -->
                            <div class="mb-3">
                                <label for="mdl_masyarakat" class="form-label">Simpanan Wajib</label>
                                <input type="text" class="form-control @error('mdl_masyarakat') is-invalid @enderror"
                                    id="mdl_masyarakat" name="mdl_masyarakat" onkeyup="onlyNumberAmount(this)"
                                    value="{{ old('mdl_masyarakat', formatNomor($modal->mdl_masyarakat)) }}">
                                @error('mdl_masyarakat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- MDL bersama Field -->

                            <div class="mb-3">
                                <label for="mdl_bersama" class="form-label">Simpanan Sukarela</label>
                                <input type="text" class="form-control @error('mdl_bersama') is-invalid @enderror"
                                    id="mdl_bersama" name="mdl_bersama" onkeyup="onlyNumberAmount(this)"
                                    value="{{ old('mdl_bersama', formatNomor($modal->mdl_bersama)) }}">
                                @error('mdl_bersama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <!-- MDL Desa Field -->
                            <div class="mb-3">
                                <label for="mdl_desa" class="form-label">Modal Desa</label>
                                <input type="text" class="form-control @error('mdl_desa') is-invalid @enderror"
                                    onkeyup="onlyNumberAmount(this)" id="mdl_desa" name="mdl_desa"
                                    value="{{ old('mdl_desa', formatNomor($modal->mdl_desa)) }}">
                                @error('mdl_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MDL Masyarakat Field -->
                            <div class="mb-3">
                                <label for="mdl_masyarakat" class="form-label">Modal Masyarakat</label>
                                <input type="text" class="form-control @error('mdl_masyarakat') is-invalid @enderror"
                                    id="mdl_masyarakat" name="mdl_masyarakat" onkeyup="onlyNumberAmount(this)"
                                    value="{{ old('mdl_masyarakat', formatNomor($modal->mdl_masyarakat)) }}">
                                @error('mdl_masyarakat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endcan

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
