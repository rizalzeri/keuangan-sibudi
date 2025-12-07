@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="pagetitle text-center">
            <h1>Visi Misi</h1>
        </div>
        <div class="col-lg-12">

            <form action="/{{ $profil->id }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quill Editor - Visi</h5>

                        <!-- Input Hidden untuk Trix Editor Visi -->
                        <input id="visi" type="hidden" name="visi" value="{{ old('visi', $profil->visi ?? '') }}">
                        <trix-editor input="visi" class="@error('visi') is-invalid @enderror"></trix-editor>
                        @error('visi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <h5 class="card-title mt-4">Quill Editor - Misi</h5>

                        <!-- Input Hidden untuk Trix Editor Misi -->
                        <input id="misi" type="hidden" name="misi" value="{{ old('misi', $profil->misi ?? '') }}">
                        <trix-editor input="misi" class="@error('misi') is-invalid @enderror"></trix-editor>
                        @error('misi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary mt-5">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
