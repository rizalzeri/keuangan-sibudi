@extends('layouts_proker.main')

@section('container')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#programModal">
        Tambah Program
    </button>

    <!-- Modal -->
    <div class="modal fade" id="programModal" tabindex="-1" aria-labelledby="programModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="programModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/proker/rencana/kegiatan/store" method="POST">
                        @csrf
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>

                                    <th>
                                        Kegiatan/Program
                                    </th>
                                    <th>
                                        Alokasi
                                    </th>
                                    <th>
                                        Sumber Pembiayaan
                                    </th>
                                    <th>
                                        Aksi
                                    </th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr>

                                    <td>
                                        <input type="text" class="form-control" name="input[0][kegiatan]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[0][alokasi]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[0][sumber]">
                                    </td>
                                    <td><Button type="button" id="tambah" class="btn btn-sm btn-success">Tambah</Button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <script>
                            var i = 0;

                            $('#tambah').click(function() {
                                i++;

                                $('#table').append(
                                    `<tr>

                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][kegiatan]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][alokasi]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][sumber]">
                                    </td>
                                    <td><Button class="btn btn-sm btn-danger hapus-table" id="tambah">hapus</Button></td>
                                </tr>`
                                );

                            });

                            $(document).on('click', '.hapus-table', function() {
                                $(this).parents('tr').remove()
                            });
                        </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>
                    Kegiatan/Program
                </th>
                <th>
                    Alokasi
                </th>
                <th>
                    Sumber Pembiayaan
                </th>
                <th>aksi</th>
            </tr>

        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($programs as $program)
                <tr>
                    <td>
                        {{ $i++ }}
                    </td>
                    <td>
                        {{ $program->kegiatan }}
                    </td>
                    <td>
                        {{ $program->alokasi }}
                    </td>
                    <td>
                        {{ $program->sumber }}
                    </td>
                    <td>
                        <form action="/proker/rencana/kegiatan/{{ $program->id }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Apakah yakin dihapus?')"
                                class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('proker.rencana_kerja')
@endsection
