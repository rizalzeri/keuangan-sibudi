@extends('layouts.main')

@section('container')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">


            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Tambah Unit Untuk Persedian
                    </div>
                    <form action="/aset/persediaan/unit/tambah" method="POST">
                        @csrf <!-- Token CSRF untuk keamanan -->

                        <!-- Input for Nama Unit -->
                        <div class="mb-3">
                            <label for="nm_unit" class="form-label">Nama Unit</label>
                            <input type="text" class="form-control @error('nm_unit') is-invalid @enderror" id="nm_unit"
                                name="nm_unit" placeholder="Masukkan nama unit" value="{{ old('nm_unit') }}">
                            @error('nm_unit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Input for Kepala Unit -->
                        <div class="mb-3">
                            <label for="kepala_unit" class="form-label">Kepala Unit</label>
                            <input type="text" class="form-control @error('kepala_unit') is-invalid @enderror"
                                id="kepala_unit" name="kepala_unit" placeholder="Masukkan nama kepala unit"
                                value="{{ old('kepala_unit') }}">
                            @error('kepala_unit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="hidden" name="kode" value="pd9876">
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Tambah Unit</button>
                        <a href="{{ route('unit.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
