@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h1>Edit Hutang</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Hutang</h5>

                    <form action="{{ route('hutang.update', $hutang->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- For updating -->

                        <!-- Kreditur Field -->
                        <div class="mb-3">
                            <label for="kreditur" class="form-label">Kreditur</label>
                            <input type="text" class="form-control @error('kreditur') is-invalid @enderror"
                                id="kreditur" name="kreditur" value="{{ old('kreditur', $hutang->kreditur) }}">
                            @error('kreditur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan Field -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan', $hutang->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nilai Field -->
                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" onkeyup="onlyNumberAmount(this)" id="rupiah"
                                class="form-control @error('nilai') is-invalid @enderror" id="nilai" name="nilai"
                                value="{{ old('nilai', formatNomor($hutang->nilai)) }}">
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
