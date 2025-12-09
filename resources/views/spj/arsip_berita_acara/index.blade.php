@extends('layouts.spj.main')

@section('container')
@php use Illuminate\Support\Str; @endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Berita Acara</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalBA" id="btn-add-ba">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblBa" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $it)
                            <tr data-id="{{ $it->id }}">
                                <td>{{ $i + 1 }}</td>
                                <td class="ba-judul">{{ $it->judul_berita_acara }}</td>
                                <td class="ba-tanggal">{{ $it->tanggal_peristiwa ? $it->tanggal_peristiwa->format('Y-m-d') : '-' }}</td>
                                <td class="ba-deskripsi">{!! nl2br(e(Str::limit($it->deskripsi, 200))) !!}</td>
                                <td class="">
                                    <span class="ba-gdrive d-none">{{ $it->link_gdrive }}</span>
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

@include('spj.arsip_berita_acara.components.modal_form')
@include('spj.arsip_berita_acara.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // pastikan meta csrf ada di layout
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblBa tbody');
    const $form = $('#baForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formModalBA').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexBa').val('');
        $('#formModalLabelBA').text('Tambah Berita Acara');
    });

    // Open Add
    $('#btn-add-ba').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexBa').val('');
        $('#formModalLabelBA').text('Tambah Berita Acara');
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            judul_berita_acara: $('#baJudul').val().trim(),
            tanggal_peristiwa: $('#baTanggal').val().trim(),
            deskripsi: $('#baDeskripsi').val().trim(),
            link_gdrive: $('#baGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.judul_berita_acara || !payload.tanggal_peristiwa) {
            Swal.fire('Kesalahan', 'Judul dan Tanggal wajib diisi', 'error');
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModalBA'));
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

        const judul = tds.eq(1).text().trim() || '-';
        const tanggal = tds.eq(2).text().trim() || '-';
        const deskripsiHtml = tds.eq(3).html().trim() || '-';
        const gdrive = tds.eq(4).find('.ba-gdrive').text().trim() || '';

        $('#viewJudulBA').text(judul);
        $('#viewTanggalBA').text(tanggal);
        $('#viewDeskripsiBA').html(deskripsiHtml === '' ? '-' : deskripsiHtml);

        if (gdrive) {
            $('#viewGdriveBA').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewGdriveBA').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewModalBA')).show();
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

        $form.attr('action', '/spj/arsip_berita_acara/' + id);
        $form.find('input[name="_method"]').val('PUT');

        const judul = tds.eq(1).text().trim();
        const tanggal = tds.eq(2).text().trim();
        // deskripsi in td might contain <br>, so use .text()
        const deskripsi = tds.eq(3).text().trim();
        const gdrive = tds.eq(4).find('.ba-gdrive').text().trim();

        $('#baJudul').val(judul);
        $('#baTanggal').val(tanggal);
        $('#baDeskripsi').val(deskripsi);
        $('#baGdrive').val(gdrive || '');

        $('#formModalLabelBA').text('Edit Berita Acara');
        new bootstrap.Modal(document.getElementById('formModalBA')).show();
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
                    url: '/spj/arsip_berita_acara/' + id,
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
        $tr.append(`<td class="ba-judul">${escapeHtml(d.judul_berita_acara || '')}</td>`);
        $tr.append(`<td class="ba-tanggal">${escapeHtml(d.tanggal_peristiwa || '')}</td>`);
        $tr.append(`<td class="ba-deskripsi">${escapeHtml(d.deskripsi ? d.deskripsi.substring(0,200) : '-')}</td>`);
        $tr.append(`
            <td class="">
                <span class="ba-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
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

        $tr.find('td').eq(1).text(d.judul_berita_acara || '');
        $tr.find('td').eq(2).text(d.tanggal_peristiwa || '');
        $tr.find('td').eq(3).text(d.deskripsi ? d.deskripsi.substring(0,200) : '-');
        $tr.find('.ba-gdrive').text(d.link_gdrive || '');
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

    // optional: init DataTable if plugin present (safe guard)
    if ($.fn.DataTable) {
        try {
            $('#tblBa').DataTable();
        } catch (e) {
            console.warn('DataTable init failed:', e);
        }
    }
});
</script>
@endsection
