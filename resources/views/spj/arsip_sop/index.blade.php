@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip SOP</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalSOP" id="btn-add-sop">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblSop" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nama SOP</th>
                            <th>Nomor Dokumen</th>
                            <th>Ruang Lingkup</th>
                            <th style="width:120px">Status</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sops as $i => $s)
                            <tr data-id="{{ $s->id }}">
                                <td>{{ $i + 1 }}</td>
                                <td class="sop-nama">{{ $s->nama_sop }}</td>
                                <td class="sop-nomor">{{ $s->nomor_dokumen }}</td>
                                <td class="sop-ruang">{{ $s->ruang_lingkup ?? '-' }}</td>
                                <td class="sop-status">
                                    @if(strtolower($s->status) === 'berlaku')
                                        <span class="badge bg-success">Berlaku</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Berlaku</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="sop-gdrive d-none">{{ $s->link_gdrive }}</span>
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
@include('spj.arsip_sop.components.modal_form')
@include('spj.arsip_sop.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblSop tbody');
    const $form = $('#sopForm');
    const createAction = $form.attr('action');

    // Reset modal ketika ditutup
    $('#formModalSOP').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexSop').val('');
        $('#formModalLabelSOP').text('Tambah SOP');
        // pastikan field nomor disembunyikan dan placeholder untuk create
        $('#sopNomorWrapper').addClass('d-none');
        $('#sopNomor').val('');
    });

    // Open Add
    $('#btn-add-sop').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexSop').val('');
        $('#formModalLabelSOP').text('Tambah SOP');
        // hide nomor input saat create (nomor akan di-generate di server)
        $('#sopNomorWrapper').addClass('d-none');
        $('#sopNomor').val('');
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        // Build payload. NOTE: nomor_dokumen tidak dikirim saat POST (server akan generate)
        const payload = {
            nama_sop: $('#sopNama').val().trim(),
            ruang_lingkup: $('#sopRuang').val().trim(),
            status: $('#sopStatus').val(),
            link_gdrive: $('#sopGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        // Jika update (PUT), sertakan nomor jika ada (editing)
        if (httpMethod !== 'POST') {
            payload.nomor_dokumen = $('#sopNomor').val().trim();
        }

        // Validasi client-side: nama_sop wajib
        if (!payload.nama_sop) {
            Swal.fire('Kesalahan', 'Nama SOP wajib diisi', 'error');
            return;
        }

        // Jika create, jangan kirim nomor (server akan generate)
        $.ajax({
            url: url,
            method: httpMethod,
            data: payload,
            success: function (res) {
                if (res.success) {
                    if (httpMethod === 'POST') appendRow(res.data);
                    else updateRow(res.data);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModalSOP'));
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

    // VIEW
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');

        // ambil link dari span tersembunyi
        let link = $tr.find('.sop-gdrive').text().trim() || '';

        // 🔥 KETENTUAN KHUSUS
        if (!link || link === 'null' || link === '-') {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada Link GDrive',
                text: 'Tidak ada link GDrive yang diupload untuk dokumen ini.',
            });
            return;
        }

        // fallback jika user input tanpa http/https
        if (!/^https?:\/\//i.test(link)) {
            link = 'https://' + link;
        }

        // buka link di tab baru
        window.open(link, '_blank');
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

        // set form action
        $form.attr('action', '/spj/arsip_sop/' + id);
        $form.find('input[name="_method"]').val('PUT');

        // ambil data berdasarkan index kolom
        const nama = tds.eq(1).text().trim();
        const nomor = tds.eq(2).text().trim();
        const ruang = tds.eq(3).text().trim();
        const statusText = tds.eq(4).find('.badge').text().trim();
        const gdrive = $tr.find('.sop-gdrive').text().trim();

        // set ke dalam form
        $('#sopNama').val(nama);
        $('#sopRuang').val(ruang);
        $('#sopStatus').val((statusText === 'Berlaku') ? 'Berlaku' : 'Tidak Berlaku');
        $('#sopGdrive').val(gdrive || '');

        // tampilkan nomor di form saat edit (tapi tetap hidden)
        $('#sopNomor').val(nomor || '');
        $('#sopNomorWrapper').removeClass('d-none');

        // tampilkan modal
        $('#formModalLabelSOP').text('Edit SOP');
        new bootstrap.Modal(document.getElementById('formModalSOP')).show();
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
                    url: '/spj/arsip_sop/' + id,
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
        const statusHtml = (d.status && d.status === 'Berlaku')
            ? '<span class="badge bg-success">Berlaku</span>'
            : '<span class="badge bg-secondary">Tidak Berlaku</span>';

        const $tr = $('<tr>').attr('data-id', d.id);

        $tr.append(`<td>${newIndex}</td>`);
        $tr.append(`<td class="sop-nama">${escapeHtml(d.nama_sop)}</td>`);
        $tr.append(`<td class="sop-nomor">${escapeHtml(d.nomor_dokumen || '')}</td>`);
        $tr.append(`<td class="sop-ruang">${escapeHtml(d.ruang_lingkup || '-')}</td>`);
        $tr.append(`<td class="sop-status">${statusHtml}</td>`);

        // gdrive dipindah ke dalam kolom aksi (bukan kolom sendiri)
        $tr.append(`
            <td class="text-center">
                <span class="sop-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
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

        $tr.find('.sop-nama').text(d.nama_sop);
        $tr.find('.sop-nomor').text(d.nomor_dokumen || '');
        $tr.find('.sop-ruang').text(d.ruang_lingkup || '-');

        const statusHtml =
            d.status === 'Berlaku'
                ? '<span class="badge bg-success">Berlaku</span>'
                : '<span class="badge bg-secondary">Tidak Berlaku</span>';

        $tr.find('.sop-status').html(statusHtml);

        // posisi baru gdrive
        $tr.find('.sop-gdrive').text(d.link_gdrive || '');
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