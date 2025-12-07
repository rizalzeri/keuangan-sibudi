@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Perjanjian Kerja</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalPerj" id="btn-add-perj">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblPerjanjian" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nomor Dokumen</th>
                            <th>Pihak</th>
                            <th>Bentuk Kerjasama</th>
                            <th>Deskripsi</th>
                            <th>Durasi Kerjasama</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $items = [
                                ['nomor'=>'PK-001/2025','pihak'=>'PT. A','bentuk'=>'MoU','deskripsi'=>'Kerjasama pelatihan','durasi'=>'1 Tahun','gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['nomor'=>'PK-002/2025','pihak'=>'Yayasan B','bentuk'=>'Kontrak','deskripsi'=>'Pengadaan barang','durasi'=>'6 Bulan','gdrive'=>''],
                            ];
                        @endphp

                        @foreach ($items as $i => $it)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="pk-nomor">{{ $it['nomor'] }}</td>
                                <td class="pk-pihak">{{ $it['pihak'] }}</td>
                                <td class="pk-bentuk">{{ $it['bentuk'] }}</td>
                                <td class="pk-deskripsi">{{ $it['deskripsi'] }}</td>
                                <td class="pk-durasi">{{ $it['durasi'] }}</td>

                                {{-- gdrive tersimpan di sel tersembunyi agar tidak tampil di view --}}
                                <td class="pk-gdrive d-none">{{ $it['gdrive'] }}</td>

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

@include('spj.arsip_perjanjian_kerja.components.modal_form')
@include('spj.arsip_perjanjian_kerja.components.modal_view')

<script>
$(function () {
    const $tbody = $('#tblPerjanjian tbody');

    // Reset modal ketika ditutup
    $('#formModalPerj').on('hidden.bs.modal', function () {
        $('#perjForm')[0].reset();
        $('#rowIndexPerj').val('');
        $('#formModalLabelPerj').text('Tambah Perjanjian Kerja');
    });

    // Submit (Tambah / Edit) — client-side demo
    $('#perjForm').on('submit', function (e) {
        e.preventDefault();

        const nomor = $('#perjNomor').val().trim();
        const pihak = $('#perjPihak').val().trim();
        const bentuk = $('#perjBentuk').val().trim();
        const deskripsi = $('#perjDeskripsi').val().trim() || '-';
        const durasi = $('#perjDurasi').val().trim() || '-';
        const gdrive = $('#perjGdrive').val().trim();

        if (!nomor || !pihak || !bentuk) {
            alert('Nomor Dokumen, Pihak, dan Bentuk Kerjasama wajib diisi');
            return;
        }

        const idx = $('#rowIndexPerj').val();

        if (idx === '') {
            // Tambah
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="pk-nomor">${escapeHtml(nomor)}</td>`);
            $tr.append(`<td class="pk-pihak">${escapeHtml(pihak)}</td>`);
            $tr.append(`<td class="pk-bentuk">${escapeHtml(bentuk)}</td>`);
            $tr.append(`<td class="pk-deskripsi">${escapeHtml(deskripsi)}</td>`);
            $tr.append(`<td class="pk-durasi">${escapeHtml(durasi)}</td>`);
            $tr.append(`<td class="pk-gdrive d-none">${escapeHtml(gdrive)}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // Edit
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.pk-nomor').text(nomor);
            $tr.find('.pk-pihak').text(pihak);
            $tr.find('.pk-bentuk').text(bentuk);
            $tr.find('.pk-deskripsi').text(deskripsi);
            $tr.find('.pk-durasi').text(durasi);
            $tr.find('.pk-gdrive').text(gdrive);
        }

        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModalPerj'));
        modal.hide();
    });

    // View
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewPerjNomor').text($tr.find('.pk-nomor').text());
        $('#viewPerjPihak').text($tr.find('.pk-pihak').text());
        $('#viewPerjBentuk').text($tr.find('.pk-bentuk').text());
        $('#viewPerjDeskripsi').text($tr.find('.pk-deskripsi').text());
        $('#viewPerjDurasi').text($tr.find('.pk-durasi').text());
        // NOTE: link GDrive tidak ditampilkan di view

        const vmodal = new bootstrap.Modal(document.getElementById('viewModalPerj'));
        vmodal.show();
    });

    // Edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndexPerj').val(idx);

        $('#perjNomor').val($tr.find('.pk-nomor').text().trim());
        $('#perjPihak').val($tr.find('.pk-pihak').text().trim());
        $('#perjBentuk').val($tr.find('.pk-bentuk').text().trim());
        $('#perjDeskripsi').val($tr.find('.pk-deskripsi').text().trim());
        $('#perjDurasi').val($tr.find('.pk-durasi').text().trim());

        const gdriveLink = $tr.find('.pk-gdrive').text().trim() || '';
        $('#perjGdrive').val(gdriveLink);

        $('#formModalLabelPerj').text('Edit Perjanjian Kerja');
        const modal = new bootstrap.Modal(document.getElementById('formModalPerj'));
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
