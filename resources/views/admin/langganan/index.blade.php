@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data langganan</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body ">

                    <a href="/admin/langganan/{{ Request::is('admin/langganan/bumdesa') ? 'bumdesa' : 'bumdes-bersama' }}/create"
                        class="btn btn-primary mt-3">Tambah Langganan</a>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Jumlah Bulan</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Nama Waktu</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;

                            @endphp
                            @foreach ($langganans as $langganan)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $langganan->jumlah_bulan }}</td>
                                    <td>{{ formatRupiah($langganan->harga) }}</td>
                                    <td>{{ $langganan->waktu }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/admin/langganan/{{ Request::is('admin/langganan/bumdesa') ? 'bumdesa' : 'bumdes-bersama' }}/{{ $langganan->id }}/edit"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i></a>
                                            <form
                                                action="/admin/langganan/{{ Request::is('admin/langganan/bumdesa') ? 'bumdesa' : 'bumdes-bersama' }}/{{ $langganan->id }}"
                                                class="ms-2" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin dihapus?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
