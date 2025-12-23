@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Surat Masuk</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblSurat" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Pengirim</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surats as $i => $s)
                            <tr data-id="{{ $s->id }}">
                                <td class="">{{ $i + 1 }}</td>
                                <td class="surat-pengirim">{{ $s->pengirim }}</td>
                                <td class="surat-judul">{{ $s->judul_surat }}</td>
                                <td class="surat-isi">{!! nl2br(e(Str::limit($s->isi, 200))) !!}</td>
                                <td class="">
                                    <span class="row-link d-none">{{ $s->link_gdrive }}</span>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $s->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $s->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $s->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('spj.arsip_surat_masuk.components.modal_form')
@include('spj.arsip_surat_masuk.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblSurat tbody');
    const $form = $('#suratForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formModal').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Masuk');
    });

    // Open Add
    $('#btn-add').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Masuk');
    });
    function lockRawInput(id) {
        const el = document.getElementById(id);
        el.addEventListener('input', function () {
            el.dataset.raw = el.value; // simpan nilai asli
        });
    }
    ['pengirim','judul','isi','gdrive'].forEach(lockRawInput);
    // Submit form via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;
        const payload = {
            pengirim: $('#pengirim').data('raw') || $('#pengirim').val(),
            judul_surat: $('#judul').data('raw') || $('#judul').val(),
            isi: $('#isi').data('raw') || $('#isi').val(),
            link_gdrive: $('#gdrive').data('raw') || $('#gdrive').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.pengirim) {
            Swal.fire('Kesalahan', 'Nama pengirim wajib diisi', 'error');
            return;
        }

        if (httpMethod === 'PUT' && !url.match(/\/\d+$/)) {
            // fallback: if form action not set with id, try read rowIndex
            const idx = $('#rowIndex').val();
            if (idx !== '') {
                const rowId = $tbody.find('tr').eq(parseInt(idx)).data('id');
                if (rowId) $form.attr('action', createAction + '/' + rowId);
            }
        }

        $.ajax({
            url: url,
            method: httpMethod,
            data: payload,
            success: function (res) {
                if (res.success) {
                    if (httpMethod === 'POST') appendRow(res.data);
                    else updateRow(res.data);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModal'));
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

    // VIEW (pakai td.eq)
    // GANTI handler .btn-view yang lama dengan yang ini
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');

        // Ambil link dari <span class="row-link d-none"> yang ada di kolom aksi
        // (kode Anda sebelumnya menyimpan row-link di td terakhir, jadi ini cocok)
        let link = $tr.find('.row-link').first().text().trim() || '';

        // Normalisasi dan cek "tidak ada"
        if (!link || link === 'null' || link === '-') {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada Link GDrive',
                text: 'Tidak ada link GDrive yang diupload untuk dokumen ini.',
            });
            return;
        }

        // Jika user menyimpan link tanpa schema, tambahkan https:// sebagai fallback
        if (!/^https?:\/\//i.test(link)) {
            link = 'https://' + link;
        }

        // Buka di tab baru
        window.open(link, '_blank');
    });


    // EDIT (pakai td.eq)
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');

        // ambil id dari data-id (route butuh id). kalau tidak ada, coba cari .row-id di dalam td pertama
        const id = $(this).data('id');
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk diproses edit', 'error');
            return;
        }

        // set form action untuk update
        $form.attr('action', '/spj/arsip_surat_masuk/' + id);
        $form.find('input[name="_method"]').val('PUT');

        // ambil field dari kolom via eq
        const pengirim = $tr.find('td').eq(1).text().trim();
        const judul = $tr.find('td').eq(2).text().trim();
        // ambil isi sebagai teks (hilangkan <br>)
        const isiText = $tr.find('td').eq(3).text().trim();
        const link = $tr.find('td').eq(4).find('.row-link').text().trim() || '';

        // isi form
        $('#pengirim').val(pengirim);
        $('#judul').val(judul);
        $('#isi').val(isiText === '-' ? '' : isiText);
        $('#gdrive').val(link);

        $('#formModalLabel').text('Edit Surat Masuk');
        new bootstrap.Modal(document.getElementById('formModal')).show();
    });

    // DELETE (pakai td.eq untuk info, data-id untuk request)
    $tbody.on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');

        // ambil id untuk endpoint hapus
        const id = $(this).data('id');
        console.log('id',id)
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk menghapus', 'error');
            return;
        }

        // ambil beberapa info (opsional) dengan td.eq untuk tampil di konfirmasi
        const pengirim = $tr.find('td').eq(1).text().trim();
        const judul = $tr.find('td').eq(2).text().trim();

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
                    url: '/spj/arsip_surat_masuk/' + id,
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
        $tr.append(`<td class="surat-pengirim">${escapeHtml(d.pengirim)}</td>`);
        $tr.append(`<td class="surat-judul">${escapeHtml(d.judul_surat || '')}</td>`);
        $tr.append(`<td class="surat-isi">${escapeHtml(d.isi ? d.isi.substring(0,200) : '-')}</td>`);
        $tr.append(`<td class="">
                        <span class="row-link d-none">${escapeHtml(d.link_gdrive || '')}</span>
                        <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                    </td>`);
        $tbody.append($tr);
    }

    function updateRow(d) {
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        if (!$tr.length) return;
        $tr.find('td').eq(1).text(d.pengirim);
        $tr.find('td').eq(2).text(d.judul_surat || '');
        $tr.find('td').eq(3).text(d.isi ? d.isi.substring(0,200) : '-');
        $tr.find('td').eq(4).find('.row-link').text(d.link_gdrive || '');
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
});
</script>
@endsection
