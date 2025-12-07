@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data Unit</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">
                    <a href="/unit/create" class="btn btn-primary mt-3">Tambah Unit</a>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Unit</th>
                                <th scope="col">Kepala Unit</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($units as $unit)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $unit->nm_unit }}</td>
                                    <td>{{ $unit->kepala_unit }}</td>

                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/unit/{{ $unit->id }}/edit" class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="/unit/{{ $unit->id }}" class="ms-2" method="POST">
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
