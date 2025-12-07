@extends('layouts.main')

@section('container')
    <style>
        .input-underline {
            border: none;
            border-bottom: 2px solid gray;
            /* Border bawah tetap ada */
            outline: none;
            /* Hilangkan outline default */
        }

        .input-underline:focus {
            border-bottom: 2px solid rgb(0, 68, 255);
            /* Tetap pakai warna yang sama agar tidak berubah */
            box-shadow: none;
            /* Hilangkan efek shadow */
        }
    </style>


    <div class="pagetitle">
        <h1>PENGISIAN FORMULIR ANALISA KETAHANAN PANGAN</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item">Formulir analisa ketahanan pangan</li>

            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5>Kuesioner</h5>
            </div>
            <form action="/akp/{{ $akp->id }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Status Desa -->
                <label for="status" class="form-label mt-3">1. Status Desa</label>
                <input type="text" id="status"
                    class="input-underline form-control @error('status') is-invalid @enderror" name="status"
                    value="{{ old('status', $akp->status) }}">
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Pagu Dana Desa -->
                <label for="dana" class="form-label mt-3">2. Pagu Dana Desa</label>
                <input type="number" id="dana"
                    class="input-underline form-control @error('dana') is-invalid @enderror" name="dana"
                    value="{{ old('dana', $akp->dana) }}">
                @error('dana')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Alokasi DD utk Ketahanan Pangan -->
                <label for="alokasi" class="form-label mt-3">3. Alokasi DD utk ketahanan pangan</label>
                <input type="number" id="alokasi"
                    class="input-underline form-control @error('alokasi') is-invalid @enderror" name="alokasi"
                    value="{{ old('alokasi', $akp->alokasi) }}">
                @error('alokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Tematik Ketahanan Pangan -->
                <label for="tematik" class="form-label mt-3">4. Tematik Ketahanan Pangan</label>
                <input type="text" id="tematik"
                    class="input-underline form-control @error('tematik') is-invalid @enderror" name="tematik"
                    value="{{ old('tematik', $akp->tematik) }}">
                @error('tematik')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <!-- Berapa Persen proyeksi pendapatan tahun berikutnya -->
                <label for="pendapatan" class="form-label mt-3">5. Berapa Persen proyeksi pendapatan tahun
                    berikutnya (%)</label>
                <input type="number" id="pendapatan"
                    class="input-underline form-control @error('pendapatan') is-invalid @enderror" name="pendapatan"
                    value="{{ old('pendapatan', $akp->pendapatan) }}">
                @error('pendapatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <!-- Berapa Persen proyeksi Pembiayaan tahun berikutnya -->
                <label for="pembiayaan" class="form-label mt-3">6. Berapa Persen proyeksi Pembiayaan tahun
                    berikutnya (%)</label>
                <input type="number" id="pembiayaan"
                    class="input-underline form-control @error('pembiayaan') is-invalid @enderror" name="pembiayaan"
                    value="{{ old('pembiayaan', $akp->pembiayaan) }}">
                @error('pembiayaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-sm btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>

    <div class="card  table-responsive">
        <div class="card-body">
            <div class="card-title">
                <h5>Penjualan Produk</h5>
            </div>
            @include('akps.penjualan_produk')
        </div>
    </div>

    @include('akps.kebutuhan')


    <style>
        .selanjutnya {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            position: sticky;
            bottom: 0;

        }

        .back-to-top {
            display: none !important;
        }
    </style>




    <div class="selanjutnya text-end">
        <a href="/akp/pdf" class="btn  btn-danger shadow"><i class="bi bi-filetype-pdf"></i> Cetak</a>
    </div>
@endsection
