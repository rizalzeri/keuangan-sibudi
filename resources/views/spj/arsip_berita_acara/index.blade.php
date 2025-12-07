@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Berita Acara</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalBerita" id="btn-add-berita">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblBerita" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Judul Berita Acara</th>
                            <th>Tanggal Peristiwa</th>
                            <th>Deskripsi</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $beritas = [
                                ['judul'=>'Berita Acara Rapat RW','tanggal'=>date('Y-m-d'),'deskripsi'=>'Rapat membahas ...','gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['judul'=>'Berita Serah Terima','tanggal'=>date('Y-m-d', strtotime('-3 days')),'deskripsi'=>'Serah terima barang ...','gdrive'=>''],
                            ];
                        @endphp

                        @foreach ($beritas as $i => $b)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="ba-judul">{{ $b['judul'] }}</td>
                                <td class="ba-tanggal">{{ $b['tanggal'] }}</td>
                                <td class="ba-deskripsi">{{ $b['deskripsi'] }}</td>

                                {{-- simpan gdrive di cell tersembunyi agar bisa diedit tanpa tampil di view --}}
                                <td class="ba-gdrive d-none">{{ $b['gdrive'] }}</td>

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

@include('spj.arsip_berita_acara.components.modal_form')
@include('spj.arsip_berita_acara.components.modal_view')

<script>
$(function () {
    const $tbody = $('#tblBerita tbody');

    // Reset modal ketika ditutup
    $('#formModalBerita').on('hidden.bs.modal', function () {
        $('#beritaForm')[0].reset();
        $('#rowIndexBerita').val('');
        $('#formModalLabelBerita').text('Tambah Berita Acara');

        // set default tanggal hari ini
        $('#tanggalPeristiwa').val('{{ date("Y-m-d") }}');
    });

    // Submit (Tambah / Edit) — client-side demo
    $('#beritaForm').on('submit', function (e) {
        e.preventDefault();

        const judul = $('#judulBerita').val().trim();
        const tanggal = $('#tanggalPeristiwa').val();
        const deskripsi = $('#deskripsiBerita').val().trim() || '-';
        const gdrive = $('#gdriveBerita').val().trim();

        if (!judul) {
            alert('Judul Berita Acara wajib diisi');
            return;
        }

        const idx = $('#rowIndexBerita').val();

        if (idx === '') {
            // Tambah baru
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="ba-judul">${escapeHtml(judul)}</td>`);
            $tr.append(`<td class="ba-tanggal">${escapeHtml(tanggal)}</td>`);
            $tr.append(`<td class="ba-deskripsi">${escapeHtml(deskripsi)}</td>`);
            $tr.append(`<td class="ba-gdrive d-none">${escapeHtml(gdrive)}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // Edit baris
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.ba-judul').text(judul);
            $tr.find('.ba-tanggal').text(tanggal);
            $tr.find('.ba-deskripsi').text(deskripsi);
            $tr.find('.ba-gdrive').text(gdrive);
        }

        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModalBerita'));
        modal.hide();
    });

    // View
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewJudulBerita').text($tr.find('.ba-judul').text());
        $('#viewTanggalBerita').text($tr.find('.ba-tanggal').text());
        $('#viewDeskripsiBerita').text($tr.find('.ba-deskripsi').text());
        // NOTE: link GDrive tidak ditampilkan di modal view

        const vmodal = new bootstrap.Modal(document.getElementById('viewModalBerita'));
        vmodal.show();
    });

    // Edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndexBerita').val(idx);

        $('#judulBerita').val($tr.find('.ba-judul').text().trim());
        $('#tanggalPeristiwa').val($tr.find('.ba-tanggal').text().trim() || '{{ date("Y-m-d") }}');
        $('#deskripsiBerita').val($tr.find('.ba-deskripsi').text().trim());

        // ambil gdrive dari cell tersembunyi
        const gdriveLink = $tr.find('.ba-gdrive').text().trim() || '';
        $('#gdriveBerita').val(gdriveLink);

        $('#formModalLabelBerita').text('Edit Berita Acara');
        const modal = new bootstrap.Modal(document.getElementById('formModalBerita'));
        modal.show();
    });

    // Delete
    $tbody.on('click', '.btn-delete', function () {
        if (!confirm('Hapus data ini?')) return;
        $(this).closest('tr').remove();

        // perbaiki nomor urut setelah hapus
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
