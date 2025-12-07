@extends('layouts.main')

@section('container')
    <div class="card col-lg-8">
        <div class="card-header">
            Form Input
        </div>
        <div class="card-body">
            <form action="/admin/langganan/{{ Request::is('admin/langganan/bumdesa*') ? 'bumdesa' : 'bumdes-bersama' }}"
                method="POST">
                @csrf <!-- Tambahkan token CSRF untuk keamanan -->

                <!-- Input for Jumlah Bulan -->
                <div class="mb-3">
                    <label for="jumlah_bulan" class="form-label">Jumlah Bulan</label>
                    <input type="number" class="form-control @error('jumlah_bulan') is-invalid @enderror" id="jumlah_bulan"
                        name="jumlah_bulan" placeholder="Masukkan jumlah bulan" value="{{ old('jumlah_bulan') }}">
                    @error('jumlah_bulan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Input for Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga"
                        name="harga" placeholder="Masukkan harga" value="{{ old('harga') }}"
                        onkeyup="onlyNumberAmount(this)">
                    @error('harga')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Input for Waktu -->
                <div class="mb-3">
                    <label for="waktu" class="form-label">Waktu</label>
                    <input type="text" class="form-control @error('waktu') is-invalid @enderror" id="waktu"
                        placeholder="Contoh : 1 bulan" name="waktu" value="{{ old('waktu') }}">
                    @error('waktu')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
