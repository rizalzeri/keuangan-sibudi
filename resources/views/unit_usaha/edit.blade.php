@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">



            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Edit Unit
                    </div>
                    <form action="{{ route('unit.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Menggunakan metode PUT untuk update data -->

                        <!-- Input for Nama Unit -->
                        <div class="mb-3">
                            <label for="nm_unit" class="form-label">Nama Unit</label>
                            <input type="text" class="form-control @error('nm_unit') is-invalid @enderror" id="nm_unit"
                                name="nm_unit" placeholder="Masukkan nama unit"
                                value="{{ old('nm_unit', $unit->nm_unit) }}">
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
                                value="{{ old('kepala_unit', $unit->kepala_unit) }}">
                            @error('kepala_unit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Perbarui Unit</button>
                        <a href="{{ route('unit.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
