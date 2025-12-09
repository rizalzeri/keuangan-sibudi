@extends('layouts.spj.main')

@section('container')
@php use Illuminate\Support\Str; @endphp

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
                        @foreach($items as $i => $it)
                            <tr data-id="{{ $it->id }}">
                                <td class="">{{ $i + 1 }}</td>
                                <td class="pk-nomor">{{ $it->nomor_dokumen }}</td>
                                <td class="pk-pihak">{{ $it->pihak ?? '-' }}</td>
                                <td class="pk-bentuk">{{ $it->bentuk_kerja_sama ?? '-' }}</td>
                                <td class="pk-deskripsi">{!! nl2br(e(Str::limit($it->deskripsi, 200))) !!}</td>
                                <td class="pk-durasi">{{ $it->durasi ?? '-' }}</td>
                                <td class="">
                                    <span class="pk-gdrive d-none">{{ $it->link_gdrive }}</span>
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

@include('spj.arsip_perjanjian_kerja.components.modal_form')
@include('spj.arsip_perjanjian_kerja.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // pastikan meta csrf ada di layout
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblPerjanjian tbody');
    const $form = $('#perjForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formModalPerj').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexPerj').val('');
        $('#formModalLabelPerj').text('Tambah Perjanjian Kerja');
    });

    // Open Add
    $('#btn-add-perj').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexPerj').val('');
        $('#formModalLabelPerj').text('Tambah Perjanjian Kerja');
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            nomor_dokumen: $('#perjNomor').val().trim(),
            pihak: $('#perjPihak').val().trim(),
            bentuk_kerja_sama: $('#perjBentuk').val().trim(),
            deskripsi: $('#perjDeskripsi').val().trim(),
            durasi: $('#perjDurasi').val().trim(),
            link_gdrive: $('#perjGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.nomor_dokumen || !payload.pihak || !payload.bentuk_kerja_sama) {
            Swal.fire('Kesalahan', 'Nomor Dokumen, Pihak, dan Bentuk Kerjasama wajib diisi', 'error');
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModalPerj'));
                    if (modal) modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || (httpMethod === 'POST' ? 'Data tersimpan' : 'Data diperbarui'),
                        timer: 900,
                        showConfirmButton: false
                    }).then(() => location.reload());
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

    // VIEW (td.eq indexing)
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');

        const nomor = tds.eq(1).text().trim() || '-';
        const pihak = tds.eq(2).text().trim() || '-';
        const bentuk = tds.eq(3).text().trim() || '-';
        const deskripsiHtml = tds.eq(4).html().trim() || '-';
        const durasi = tds.eq(5).text().trim() || '-';
        const gdrive = tds.eq(6).find('.pk-gdrive').text().trim() || '';

        $('#viewPerjNomor').text(nomor);
        $('#viewPerjPihak').text(pihak);
        $('#viewPerjBentuk').text(bentuk);
        $('#viewPerjDeskripsi').html(deskripsiHtml === '' ? '-' : deskripsiHtml);
        $('#viewPerjDurasi').text(durasi);

        if (gdrive) {
            $('#viewPerjGdrive').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewPerjGdrive').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewModalPerj')).show();
    });

    // EDIT (td.eq indexing)
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');
        const id = $(this).data('id');

        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk edit', 'error');
            return;
        }

        $form.attr('action', '/spj/arsip_perjanjian_kerja/' + id);
        $form.find('input[name="_method"]').val('PUT');

        const nomor = tds.eq(1).text().trim();
        const pihak = tds.eq(2).text().trim();
        const bentuk = tds.eq(3).text().trim();
        const deskripsi = tds.eq(4).text().trim();
        const durasi = tds.eq(5).text().trim();
        const gdrive = tds.eq(6).find('.pk-gdrive').text().trim();

        $('#perjNomor').val(nomor);
        $('#perjPihak').val(pihak);
        $('#perjBentuk').val(bentuk);
        $('#perjDeskripsi').val(deskripsi);
        $('#perjDurasi').val(durasi);
        $('#perjGdrive').val(gdrive || '');

        $('#formModalLabelPerj').text('Edit Perjanjian Kerja');
        new bootstrap.Modal(document.getElementById('formModalPerj')).show();
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
                    url: '/spj/arsip_perjanjian_kerja/' + id,
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

    // helpers to update DOM if needed
    function appendRow(d) {
        const newIndex = $tbody.find('tr').length + 1;
        const $tr = $('<tr>').attr('data-id', d.id);

        $tr.append(`<td class="">${newIndex}</td>`);
        $tr.append(`<td class="pk-nomor">${escapeHtml(d.nomor_dokumen || '')}</td>`);
        $tr.append(`<td class="pk-pihak">${escapeHtml(d.pihak || '-')}</td>`);
        $tr.append(`<td class="pk-bentuk">${escapeHtml(d.bentuk_kerja_sama || '-')}</td>`);
        $tr.append(`<td class="pk-deskripsi">${escapeHtml(d.deskripsi ? d.deskripsi.substring(0,200) : '-')}</td>`);
        $tr.append(`<td class="pk-durasi">${escapeHtml(d.durasi || '-')}</td>`);
        $tr.append(`
            <td class="">
                <span class="pk-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
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
        $tr.find('td').eq(2).text(d.pihak || '-');
        $tr.find('td').eq(3).text(d.bentuk_kerja_sama || '-');
        $tr.find('td').eq(4).text(d.deskripsi ? d.deskripsi.substring(0,200) : '-');
        $tr.find('td').eq(5).text(d.durasi || '-');
        $tr.find('.pk-gdrive').text(d.link_gdrive || '');
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

    // optional: init DataTable if plugin present
    if ($.fn.DataTable) {
        try {
            $('#tblPerjanjian').DataTable();
        } catch (e) {
            console.warn('DataTable init failed:', e);
        }
    }
});
</script>
@endsection
