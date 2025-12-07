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

        </tr>

    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($programs as $program)
            <tr>
                <td class="text-center">
                    {{ $i++ }}
                </td>
                <td>
                    {{ $program->kegiatan }}
                </td>
                <td>
                    {{ formatRupiah(intval($program->alokasi)) }}
                </td>
                <td>
                    {{ $program->sumber }}
                </td>

            </tr>
        @endforeach

        @if (count($programs) <= 0)
            <tr class="text-center">
                <td colspan="4">Data program kosong</td>
            </tr>
        @endif
    </tbody>
</table>
