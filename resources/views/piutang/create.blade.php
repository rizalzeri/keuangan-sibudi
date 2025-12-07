@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="pagetitle">
                <h1>Tambah piutang</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Tambah piutang</h5>

                    <form action="{{ route('piutang.store') }}" method="POST">
                        @csrf
                        <!-- Tangal Field -->
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at" value="{{ old('created_at') }}">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kreditur Field -->
                        <div class="mb-3">
                            <label for="kreditur" class="form-label">Kreditur</label>
                            <input type="text" class="form-control @error('kreditur') is-invalid @enderror"
                                id="kreditur" name="kreditur" value="{{ old('kreditur') }}">
                            @error('kreditur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan Field -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nilai Field -->
                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control @error('nilai') is-invalid @enderror" id="nilai"
                                onkeyup="onlyNumberAmount(this)" name="nilai" value="{{ old('nilai') }}">
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
