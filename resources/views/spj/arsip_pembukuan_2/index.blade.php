@extends('layouts.spj.main')

@section('container')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip SPJ Pembukuan 2</h4>
    </div>

    <div class="card mb-3">
        <br>
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label">Tahun Anggaran :</label>
                </div>
                <div class="col-auto">
                    <select id="filterYear" class="form-select">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto ms-4">
                    <label class="form-label">Filter :</label>
                </div>
                <div class="col-auto">
                    <select id="filterType" class="form-select">
                        @foreach($types as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <br>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Transaksi</th>
                            <th style="width:150px">Nomor Dokumen</th>
                            <th style="width:200px">Jenis SPJ</th>
                            <th>Bukti Dukung</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $r)
                            <tr data-jenis="{{ $r['jenis'] }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $r['transaksi'] }}</td>
                                <td>{{ $r['nomor'] }}</td>
                                <td>{{ $r['jenis'] }}</td>
                                <td>{{ $r['bukti'] }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button id="btnRekapBottom" class="btn btn-primary">Cetak Rekap</button>
            </div>

        </div>
    </div>
</div>

<!-- DataTables & jQuery (CDN), asumsi layout belum memuat mereka -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = $('#arsipTable').DataTable({
        paging: true,
        ordering: true,
        info: true,
        searching: true,
        lengthChange: true
    });

    // custom filtering: filter by jenis column using dropdown
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        // hanya aktif untuk tabel kita
        if (settings.nTable.id !== 'arsipTable') return true;

        const selectedType = $('#filterType').val();
        const rowJenis = $('tr', settings.nTable.tBodies[0]).eq(dataIndex).attr('data-jenis') || data[3];

        if (!selectedType || selectedType === 'Semua') return true;
        return rowJenis === selectedType;
    });

    // ketika filter berubah -> redraw
    $('#filterType').on('change', function () {
        table.draw();
    });

    // tahun filter — demo: tidak memfilter server-side karena sample, tapi kita keep it for query to rekap
    $('#filterYear').on('change', function () {
        // if you want year filter client-side, implement here using a data attribute in rows (not present in demo)
    });

    // action buttons (demo)
    $('#arsipTable tbody').on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        alert('Lihat: ' + $tr.find('td').eq(1).text());
    });

    $('#arsipTable tbody').on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        alert('Edit: ' + $tr.find('td').eq(1).text());
    });

    $('#arsipTable tbody').on('click', '.btn-delete', function () {
        if (!confirm('Hapus baris ini?')) return;
        const row = table.row($(this).closest('tr'));
        row.remove().draw();
    });

    // Cetak Rekap: ambil filter dan tahun, buka tab baru ke route rekap
    function openRekap() {
        const year = $('#filterYear').val();
        const filter = $('#filterType').val();

        window.open('_blank');
    }

    $('#btnRekap, #btnRekapBottom').on('click', openRekap);
});
</script>
@endsection
