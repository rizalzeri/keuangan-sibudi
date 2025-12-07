@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Notulen Rapat</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalNotulen" id="btn-add-notulen">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblNotulen" class="table table-sm datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Hari, Tanggal</th>
                            <th>Waktu</th>
                            <th>Tempat</th>
                            <th>Agenda</th>
                            <th>Penyelenggara</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $notulens = [
                                ['tanggal'=>date('Y-m-d'),'waktu'=>'10:00','tempat'=>'Balai Desa','agenda'=>'Rapat RW','penyelenggara'=>'Ketua RW','gdrive'=>'https://drive.google.com/file/d/xxx'],
                                ['tanggal'=>date('Y-m-d', strtotime('-2 days')),'waktu'=>'14:00','tempat'=>'Kantor Desa','agenda'=>'Koordinasi Program','penyelenggara'=>'Sekdes','gdrive'=>''],
                            ];

                            // fungsi bantu format tanggal php (dipakai hanya untuk initial dummy)
                            function format_date_id($d){
                                $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                                $ts = strtotime($d);
                                $day = $days[date('w', $ts)];
                                return $day . ',' . date('d/m/Y', $ts);
                            }
                        @endphp

                        @foreach ($notulens as $i => $n)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>

                                {{-- visible formatted date --}}
                                <td class="nr-date">{{ format_date_id($n['tanggal']) }}</td>

                                {{-- store raw date hidden for editing --}}
                                <td class="nr-date-raw d-none">{{ $n['tanggal'] }}</td>

                                <td class="nr-waktu">{{ $n['waktu'] }}</td>
                                <td class="nr-tempat">{{ $n['tempat'] }}</td>
                                <td class="nr-agenda">{{ $n['agenda'] }}</td>
                                <td class="nr-penyelenggara">{{ $n['penyelenggara'] }}</td>

                                {{-- gdrive tersembunyi --}}
                                <td class="nr-gdrive d-none">{{ $n['gdrive'] }}</td>

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
        </div>
    </div>
</div>

@include('spj.arsip_notulen_rapat.components.modal_form')
@include('spj.arsip_notulen_rapat.components.modal_view')

<script>
$(function () {
    const $tbody = $('#tblNotulen tbody');

    // helper: format YYYY-MM-DD -> "NamaHari,DD/MM/YYYY" (Indonesia)
    function formatDateID(iso) {
        if (!iso) return '';
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        // parse
        const parts = iso.split('-'); // YYYY-MM-DD
        if (parts.length !== 3) return iso;
        const y = parseInt(parts[0],10), m = parseInt(parts[1],10)-1, d = parseInt(parts[2],10);
        const dt = new Date(y,m,d);
        const dayName = days[dt.getDay()];
        const dd = String(d).padStart(2,'0');
        const mm = String(m+1).padStart(2,'0');
        return `${dayName},${dd}/${mm}/${y}`;
    }

    // reset modal tiap ditutup
    $('#formModalNotulen').on('hidden.bs.modal', function () {
        $('#notulenForm')[0].reset();
        $('#rowIndexNotulen').val('');
        $('#formModalLabelNotulen').text('Tambah Notulen Rapat');

        // set default date hari ini (YYYY-MM-DD)
        const today = new Date().toISOString().split('T')[0];
        $('#tanggalNotulen').val(today);
    });

    // set default date on open first time
    $('#tanggalNotulen').val(new Date().toISOString().split('T')[0]);

    // submit add/edit (client-side demo)
    $('#notulenForm').on('submit', function (e) {
        e.preventDefault();

        const tanggal = $('#tanggalNotulen').val(); // raw YYYY-MM-DD
        const waktu = $('#waktuNotulen').val().trim() || '-';
        const tempat = $('#tempatNotulen').val().trim() || '-';
        const agenda = $('#agendaNotulen').val().trim() || '-';
        const penyelenggara = $('#penyelenggaraNotulen').val().trim() || '-';
        const gdrive = $('#gdriveNotulen').val().trim();

        if (!tanggal) {
            alert('Tanggal wajib diisi');
            return;
        }

        const idx = $('#rowIndexNotulen').val();
        const formatted = formatDateID(tanggal);

        if (idx === '') {
            // tambah
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="nr-date">${formatted}</td>`);
            $tr.append(`<td class="nr-date-raw d-none">${tanggal}</td>`);
            $tr.append(`<td class="nr-waktu">${escapeHtml(waktu)}</td>`);
            $tr.append(`<td class="nr-tempat">${escapeHtml(tempat)}</td>`);
            $tr.append(`<td class="nr-agenda">${escapeHtml(agenda)}</td>`);
            $tr.append(`<td class="nr-penyelenggara">${escapeHtml(penyelenggara)}</td>`);
            $tr.append(`<td class="nr-gdrive d-none">${escapeHtml(gdrive)}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // edit
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.nr-date').text(formatted);
            $tr.find('.nr-date-raw').text(tanggal);
            $tr.find('.nr-waktu').text(waktu);
            $tr.find('.nr-tempat').text(tempat);
            $tr.find('.nr-agenda').text(agenda);
            $tr.find('.nr-penyelenggara').text(penyelenggara);
            $tr.find('.nr-gdrive').text(gdrive);
        }

        // tutup modal
        bootstrap.Modal.getInstance(document.getElementById('formModalNotulen')).hide();
    });

    // view
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewTanggalNotulen').text($tr.find('.nr-date').text());
        $('#viewWaktuNotulen').text($tr.find('.nr-waktu').text());
        $('#viewTempatNotulen').text($tr.find('.nr-tempat').text());
        $('#viewAgendaNotulen').text($tr.find('.nr-agenda').text());
        $('#viewPenyelenggaraNotulen').text($tr.find('.nr-penyelenggara').text());
        // NOTE: link GDrive tidak ditampilkan di view

        new bootstrap.Modal(document.getElementById('viewModalNotulen')).show();
    });

    // edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndexNotulen').val(idx);

        // isi form dari row; raw date ada di sel tersembunyi
        const rawDate = $tr.find('.nr-date-raw').text().trim() || new Date().toISOString().split('T')[0];
        $('#tanggalNotulen').val(rawDate);
        $('#waktuNotulen').val($tr.find('.nr-waktu').text().trim());
        $('#tempatNotulen').val($tr.find('.nr-tempat').text().trim());
        $('#agendaNotulen').val($tr.find('.nr-agenda').text().trim());
        $('#penyelenggaraNotulen').val($tr.find('.nr-penyelenggara').text().trim());

        // ambil gdrive dari sel tersembunyi (jika ada)
        $('#gdriveNotulen').val($tr.find('.nr-gdrive').text().trim() || '');

        $('#formModalLabelNotulen').text('Edit Notulen Rapat');
        new bootstrap.Modal(document.getElementById('formModalNotulen')).show();
    });

    // delete
    $tbody.on('click', '.btn-delete', function () {
        if (!confirm('Hapus data ini?')) return;
        $(this).closest('tr').remove();

        // perbaiki nomor urut
        $tbody.find('tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    });

    // utility: escape html
    function escapeHtml(unsafe) {
        return (unsafe || '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
</script>
@endsection
