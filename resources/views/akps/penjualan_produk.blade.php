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

<form action="/akp/penjualan" method="POST">
    @csrf

    <table class="table table-bordered mt-3" id="penjualan_produk">
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Target Panen</th>
                <th>Jumlah Panen dalam setahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (count($penjualans) <= 0)
                <tr>
                    <td>1</td>
                    <td>
                        <input type="text" name="data[0][kegiatan]" class="input-table"
                            value="{{ old('data.0.kegiatan') }}">
                        @error('data.0.kegiatan')
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
                    <td>
                        <button type="button" id="tambah" class="btn btn-sm btn-success">Tambah</button>
                    </td>
                </tr>
            @endif

            @foreach ($penjualans as $index => $penjualan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <input type="text" name="data[{{ $index }}][kegiatan]" class="input-table"
                            value="{{ old('data.' . $index . '.kegiatan', $penjualan->kegiatan) }}">
                        @error("data.{$index}.kegiatan")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" name="data[{{ $index }}][satuan]" class="input-table"
                            value="{{ old('data.' . $index . '.satuan', $penjualan->satuan) }}">
                        @error("data.{$index}.satuan")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" name="data[{{ $index }}][harga]" class="input-table"
                            value="{{ old('data.' . $index . '.harga', $penjualan->harga) }}">
                        @error("data.{$index}.harga")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" name="data[{{ $index }}][volume]" class="input-table"
                            value="{{ old('data.' . $index . '.volume', $penjualan->volume) }}">
                        @error("data.{$index}.volume")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="hidden" value="{{ $penjualan->id }}" name="data[{{ $index }}][id]">
                        <input type="number" name="data[{{ $index }}][jumlah]" class="input-table"
                            value="{{ old('data.' . $index . '.jumlah', $penjualan->jumlah) }}">
                        @error("data.{$index}.jumlah")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    @if ($index <= 0)
                        <td>
                            <button type="button" id="tambah" class="btn btn-sm btn-success">Tambah</button>
                        </td>
                    @else
                        <td>
                            <button type="button" class="btn btn-sm btn-danger hapus-table">Hapus</button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>

</form>

<script>
    var i = {{ count($penjualans) }}; // Mulai dari jumlah data yang sudah ada

    $('#tambah').click(function() {
        i++;

        $('#penjualan_produk tbody').append(
            `<tr>
                <td> ${i} </td>
                <td>
                    <input type="text" name="data[${i}][kegiatan]" class="input-table">
                </td>
                <td>
                    <input type="text" name="data[${i}][satuan]" class="input-table">
                </td>
                <td>
                    <input type="number" name="data[${i}][harga]" class="input-table">
                </td>
                <td>
                    <input type="number" name="data[${i}][volume]" class="input-table">
                </td>
                <td>
                    <input type="number" name="data[${i}][jumlah]" class="input-table">
                </td>
                <td>
                    <button class="btn btn-sm btn-danger hapus-table">Hapus</button>
                </td>
            </tr>`
        );
    });

    $(document).on('click', '.hapus-table', function() {
        $(this).closest('tr').remove();
    });
</script>
