@extends('layouts.spj.main')

@section('container')
@php use Illuminate\Support\Str; @endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Perjalanan Dinas</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalPD" id="btn-add-pd">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblPD" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Tempat</th>
                            <th>Transport</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $it)
                            <tr data-id="{{ $it->id }}">
                                <td class="">{{ $i + 1 }}</td>
                                <td class="pd-nomor">{{ $it->nomor_dokumen }}</td>
                                <td class="pd-tanggal">{{ $it->tanggal_perjalanan_dinas ? $it->tanggal_perjalanan_dinas->format('Y-m-d') : '-' }}</td>
                                <td class="pd-kegiatan">{{ $it->kegiatan ?? '-' }}</td>
                                <td class="pd-tempat">{{ $it->tempat ?? '-' }}</td>
                                <td class="pd-transport">{{ $it->transport ?? '-' }}</td>
                                <td class="">
                                    <span class="pd-gdrive d-none">{{ $it->link_gdrive }}</span>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $it->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $it->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $it->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('spj.arsip_perjalanan_dinas.components.modal_form')
@include('spj.arsip_perjalanan_dinas.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const $tbody = $('#tblPD tbody');
    const $form = $('#pdForm');
    const createAction = $form.attr('action');

    // Reset modal
    $('#formModalPD').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexPD').val('');
        $('#formModalLabelPD').text('Tambah Perjalanan Dinas');
    });

    $('#btn-add-pd').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexPD').val('');
        $('#formModalLabelPD').text('Tambah Perjalanan Dinas');
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            nomor_dokumen: $('#pdNomor').val().trim(),
            tanggal_perjalanan_dinas: $('#pdTanggal').val().trim(),
            kegiatan: $('#pdKegiatan').val().trim(),
            tempat: $('#pdTempat').val().trim(),
            transport: $('#pdTransport').val().trim(),
            link_gdrive: $('#pdGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.nomor_dokumen) {
            Swal.fire('Kesalahan', 'Nomor Dokumen wajib diisi', 'error');
            return;
        }

        $.ajax({
            url: url,
            method: httpMethod,
            data: payload,
            success: function (res) {
                if (res.success) {
                    if (httpMethod === 'POST') appendRow(res.data);
                    else updateRow(res.data);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModalPD'));
                    if (modal) modal.hide();

                    Swal.fire({ icon:'success', title:'Berhasil', text: res.message || 'Sukses', timer:900, showConfirmButton:false })
                        .then(() => location.reload());
                    setTimeout(() => location.reload(), 1200);
                } else {
                    Swal.fire('Error', res.message || 'Terjadi kesalahan', 'error');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    const messages = Object.values(errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Validasi', html: messages });
                } else {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan pada server';
                    Swal.fire('Error', msg, 'error');
                }
            }
        });
    });

    // VIEW
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');

        const nomor = tds.eq(1).text().trim() || '-';
        const tanggal = tds.eq(2).text().trim() || '-';
        const kegiatanHtml = tds.eq(3).html().trim() || '-';
        const tempat = tds.eq(4).text().trim() || '-';
        const transport = tds.eq(5).text().trim() || '-';
        const gdrive = tds.eq(6).find('.pd-gdrive').text().trim() || '';

        $('#viewPdNomor').text(nomor);
        $('#viewPdTanggal').text(tanggal);
        $('#viewPdKegiatan').html(kegiatanHtml === '' ? '-' : kegiatanHtml);
        $('#viewPdTempat').text(tempat);
        $('#viewPdTransport').text(transport);

        if (gdrive) {
            $('#viewPdGdrive').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewPdGdrive').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewModalPD')).show();
    });

    // EDIT
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');
        const id = $(this).data('id');

        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk edit', 'error');
            return;
        }

        $form.attr('action', '/spj/arsip_perjalanan_dinas/' + id);
        $form.find('input[name="_method"]').val('PUT');

        const nomor = tds.eq(1).text().trim();
        const tanggal = tds.eq(2).text().trim();
        const kegiatan = tds.eq(3).text().trim();
        const tempat = tds.eq(4).text().trim();
        const transport = tds.eq(5).text().trim();
        const gdrive = tds.eq(6).find('.pd-gdrive').text().trim();

        $('#pdNomor').val(nomor);
        $('#pdTanggal').val(tanggal);
        $('#pdKegiatan').val(kegiatan);
        $('#pdTempat').val(tempat);
        $('#pdTransport').val(transport);
        $('#pdGdrive').val(gdrive || '');

        $('#formModalLabelPD').text('Edit Perjalanan Dinas');
        new bootstrap.Modal(document.getElementById('formModalPD')).show();
    });

    // DELETE
    $tbody.on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk menghapus', 'error');
            return;
        }

        Swal.fire({
            title: 'Hapus data?',
            html: `<small>Data akan dihapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/spj/arsip_perjalanan_dinas/' + id,
                    method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        if (res.success) {
                            $tr.remove();
                            reindexRows();
                            Swal.fire({ icon:'success', title:'Terhapus', text: res.message || 'Data dihapus', timer:800, showConfirmButton:false })
                                .then(() => location.reload());
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('Error', res.message || 'Gagal menghapus', 'error');
                        }
                    },
                    error: function (xhr) {
                        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan pada server';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    // helpers
    function appendRow(d) {
        const newIndex = $tbody.find('tr').length + 1;
        const $tr = $('<tr>').attr('data-id', d.id);

        $tr.append(`<td class="">${newIndex}</td>`);
        $tr.append(`<td class="pd-nomor">${escapeHtml(d.nomor_dokumen || '')}</td>`);
        $tr.append(`<td class="pd-tanggal">${escapeHtml(d.tanggal_perjalanan_dinas || '')}</td>`);
        $tr.append(`<td class="pd-kegiatan">${escapeHtml(d.kegiatan || '-')}</td>`);
        $tr.append(`<td class="pd-tempat">${escapeHtml(d.tempat || '-')}</td>`);
        $tr.append(`<td class="pd-transport">${escapeHtml(d.transport || '-')}</td>`);
        $tr.append(`
            <td class="">
                <span class="pd-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
                <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning btn-edit" data-id="${d.id}" title="Edit"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger btn-delete" data-id="${d.id}" title="Hapus"><i class="bi bi-trash"></i></button>
            </td>
        `);

        $tbody.append($tr);
    }

    function updateRow(d) {
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        if (!$tr.length) return;

        $tr.find('td').eq(1).text(d.nomor_dokumen || '');
        $tr.find('td').eq(2).text(d.tanggal_perjalanan_dinas || '');
        $tr.find('td').eq(3).text(d.kegiatan || '-');
        $tr.find('td').eq(4).text(d.tempat || '-');
        $tr.find('td').eq(5).text(d.transport || '-');
        $tr.find('.pd-gdrive').text(d.link_gdrive || '');
    }

    function reindexRows() {
        $tbody.find('tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    }

    function escapeHtml(unsafe) {
        return (unsafe || '')
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // optional DataTable init
    if ($.fn.DataTable) {
        try {
            $('#tblPD').DataTable();
        } catch (e) {
            console.warn('DataTable init failed:', e);
        }
    }
});
</script>
@endsection
