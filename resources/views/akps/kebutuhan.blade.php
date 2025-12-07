<style>
    .input-table {
        width: 100%;
        border: none;
        border-bottom: 1px solid black;
        outline: none;
        box-shadow: none !important;
        background: transparent;
    }

    .input-table:focus {
        border-bottom: 2px solid rgb(0, 68, 255);
        box-shadow: none !important;
        outline: none;
    }

    .text-danger {
        font-size: 12px;
    }
</style>

@foreach ($kebutuhans as $key => $kebutuhan)
    @php
        $kategori = str_replace([' ', '/'], '', strtolower($key));
    @endphp

    <div class="card  table-responsive">
        <div class="card-body">
            <div class="card-title">
                <h5>Kebutuhan {{ $key }}</h5>
            </div>
            <form action="/akp/kebutuhan" method="POST">
                @csrf

                <input type="hidden" value="{{ $key }}" name="kategori">

                <table class="table table-bordered mt-3 kebutuhan-table" id="table-{{ $kategori }}">

                    <thead>
                        <tr>

                            <th>No</th>
                            <th>Uraian</th>
                            @if (
                                $kategori == 'sewatanahbangunan' ||
                                    $kategori == 'sewaalat' ||
                                    $kategori == 'pengadaanalat' ||
                                    $kategori == 'saranaprasarana')
                                <th>Satuan</th>
                            @else
                                <th>Satuan&nbsp;Penjualan</th>
                            @endif

                            <th>Harga</th>
                            <th>Kebutuhan</th>
                            <th>Berapa&nbsp;kali&nbsp;dalam&nbsp;setahun</th>
                            <th>Jumlah</th>
                            @if ($key != 'Sewa Tanah/Bangunan')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($kebutuhan->isEmpty())
                            <tr>
                                <td>1</td>
                                <td>
                                    <input type="text" name="data[0][uraian]" class="input-table"
                                        value="{{ old('data.0.uraian') }}">
                                    @error('data.0.uraian')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" name="data[0][satuan]" class="input-table"
                                        value="{{ old('data.0.satuan') }}">
                                    @error('data.0.satuan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="data[0][harga]" class="input-table"
                                        value="{{ old('data.0.harga') }}">
                                    @error('data.0.harga')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="data[0][volume]" class="input-table"
                                        value="{{ old('data.0.volume') }}">
                                    @error('data.0.volume')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="data[0][jumlah]" class="input-table"
                                        value="{{ old('data.0.jumlah') }}">
                                    @error('data.0.jumlah')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td></td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-success tambah-row"
                                        data-key="{{ $kategori }}">Tambah </button>
                                </td>

                            </tr>
                        @endif

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($kebutuhan as $index => $butuhan)
                            @php
                                $total += $butuhan->harga * $butuhan->jumlah;
                                $total_satuan = $butuhan->harga * $butuhan->jumlah * $butuhan->volume;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <input type="text" name="data[{{ $index }}][uraian]" class="input-table"
                                        value="{{ old('data.' . $index . '.uraian', $butuhan->uraian) }}">
                                    @error("data.{$index}.uraian")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" name="data[{{ $index }}][satuan]" class="input-table"
                                        value="{{ old('data.' . $index . '.satuan', $butuhan->satuan) }}">
                                    @error("data.{$index}.satuan")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="data[{{ $index }}][harga]" class="input-table"
                                        value="{{ old('data.' . $index . '.harga', $butuhan->harga) }}">
                                    @error("data.{$index}.harga")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="data[{{ $index }}][volume]" class="input-table"
                                        value="{{ old('data.' . $index . '.volume', $butuhan->volume) }}">
                                    @error("data.{$index}.volume")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="hidden" value="{{ $butuhan->id }}"
                                        name="data[{{ $index }}][id]">
                                    <input type="number" name="data[{{ $index }}][jumlah]" class="input-table"
                                        value="{{ old('data.' . $index . '.jumlah', $butuhan->jumlah) }}">
                                    @error("data.{$index}.jumlah")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>{{ formatRupiah($total_satuan) }}</td>
                                @if ($key != 'Sewa Tanah/Bangunan')
                                    @if ($index <= 0)
                                        <td>
                                            <button type="button" id="tambah"
                                                class="btn btn-sm btn-success tambah-row"
                                                data-key="{{ $kategori }}">Tambah</button>
                                        </td>
                                    @else
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-danger hapus-table">Hapus</button>
                                        </td>
                                    @endif
                                @endif

                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <table class="table text-end">
                    <tr>


                        <td> Total {{ formatRupiah($total) }}</td>

                    </tr>
                </table>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>

            </form>
        </div>
    </div>
@endforeach

<script>
    $(document).ready(function() {
        $(".tambah-row").click(function() {
            var key = $(this).data("key");

            console.log(key);

            var tableBody = $("#table-" + key + " tbody");
            var rowCount = tableBody.find("tr").length;

            var newRow = `
            <tr>
                <td>${rowCount + 1}</td>
                <td><input type="text" name="data[${rowCount}][uraian]" class="input-table"></td>
                <td><input type="text" name="data[${rowCount}][satuan]" class="input-table"></td>
                <td><input type="number" name="data[${rowCount}][harga]" class="input-table"></td>
                <td><input type="number" name="data[${rowCount}][volume]" class="input-table"></td>
                <td><input type="number" name="data[${rowCount}][jumlah]" class="input-table"></td>
                 <td></td>
                <td><button type="button" class="btn btn-sm btn-danger hapus-row">Hapus</button></td>
            </tr>`;

            tableBody.append(newRow);
        });

        $(document).on('click', '.hapus-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
