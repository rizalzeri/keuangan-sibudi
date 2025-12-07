@extends('layouts_proker.main')

@section('container')
    <p>2. Kualititif</p>


    <form action="/proker/kualititif/{{ $proker->id }}" method="POST">
        @csrf
        @method('PUT')
        <input id="kualititif" type="hidden" name="kualititif" value="{{ old('hasil_kinerja', $lpj->hasil_kinerja ?? '') }}">
        {!! old('hasil_kinerja', $lpj->hasil_kinerja ?? '') !!}
    @endsection
</form>
