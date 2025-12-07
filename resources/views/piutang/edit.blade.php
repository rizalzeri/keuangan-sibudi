@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h1>Edit piutang</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit piutang</h5>

                    <form action="{{ route('piutang.update', $piutang->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- For updating -->
                        <!-- Tangal Field -->
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at"
                                value="{{ old('created_at', tanggal($piutang->created_at)) }}">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Kreditur Field -->
                        <div class="mb-3">
                            <label for="kreditur" class="form-label">Kreditur</label>
                            <input type="text" class="form-control @error('kreditur') is-invalid @enderror"
                                id="kreditur" name="kreditur" value="{{ old('kreditur', $piutang->kreditur) }}">
                            @error('kreditur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan Field -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan', $piutang->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nilai Field -->
                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control @error('nilai') is-invalid @enderror" id="nilai"
                                onkeyup="onlyNumberAmount(this)" name="nilai"
                                value="{{ old('nilai', formatNomor($piutang->nilai)) }}">
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
