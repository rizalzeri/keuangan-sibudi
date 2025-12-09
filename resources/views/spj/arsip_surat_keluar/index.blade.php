@extends('layouts.spj.main')

@section('container')
@php use Illuminate\Support\Str; @endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Surat Keluar</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblSuratKeluar" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tujuan</th>
                            <th>Judul Surat</th>
                            <th>Isi</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surats as $i => $s)
                            <tr data-id="{{ $s->id }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="sk-nomor">{{ $s->nomor_dokumen }}</td>
                                <td class="sk-tujuan">{{ $s->tujuan }}</td>
                                <td class="sk-judul">{{ $s->judul_surat }}</td>
                                <td class="sk-isi">{!! nl2br(e(Str::limit($s->isi, 200))) !!}</td>
                                <td class="text-center">
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

@include('spj.arsip_surat_keluar.components.modal_form')
@include('spj.arsip_surat_keluar.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // pastikan meta csrf ada di layout
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblSuratKeluar tbody');
    const $form = $('#suratKeluarForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formModal').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Keluar');
    });

    // Open Add
    $('#btn-add').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Keluar');
    });
    function lockRawInput(id) {
        const el = document.getElementById(id);
        el.addEventListener('input', function () {
            el.dataset.raw = el.value; // simpan nilai asli
        });
    }
    ['nomorDokumen','tujuan','judul','isi','gdrive'].forEach(lockRawInput);
    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            nomor_dokumen: $('#nomorDokumen').data('raw') || $('#nomorDokumen').val(),
            tujuan: $('#tujuan').data('raw') || $('#tujuan').val(),
            judul_surat: $('#judul').data('raw') || $('#judul').val(),
            isi: $('#isi').data('raw') || $('#isi').val(),
            link_gdrive: $('#gdrive').data('raw') || $('#gdrive').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.nomor_dokumen || !payload.tujuan || !payload.judul_surat) {
            Swal.fire('Kesalahan', 'Nomor Dokumen, Tujuan dan Judul wajib diisi', 'error');
            return;
        }

        if (httpMethod === 'PUT' && !url.match(/\/\d+$/)) {
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

    // VIEW — ambil kolom dengan td.eq
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        // td index: 0=no, 1=nomor, 2=tujuan, 3=judul, 4=isi, 5=gdrive(hidden), 6=aksi
        const nomor = $tr.find('td').eq(1).text().trim() || '-';
        const tujuan = $tr.find('td').eq(2).text().trim() || '-';
        const judul = $tr.find('td').eq(3).text().trim() || '-';
        const isiHtml = $tr.find('td').eq(4).html().trim() || '-';
        const link = $tr.find('td').eq(5).text().trim() || '';
        console.log('link',link)
        $('#viewNomor').text(nomor);
        $('#viewTujuan').text(tujuan);
        $('#viewJudul').text(judul);
        $('#viewIsi').html(isiHtml === '' ? '-' : isiHtml);

        if (link) {
            $('#viewGDrive').attr('href', link).text('Buka Link').removeClass('text-muted');
        } else {
            $('#viewGDrive').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewModal')).show();
    });

    // EDIT — ambil kolom dengan td.eq
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');

        const id = $(this).data('id');
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk edit', 'error');
            return;
        }

        $form.attr('action', '/spj/arsip_surat_keluar/' + id);
        $form.find('input[name="_method"]').val('PUT');

        $('#nomorDokumen').val($tr.find('td').eq(1).text().trim());
        $('#tujuan').val($tr.find('td').eq(2).text().trim());
        $('#judul').val($tr.find('td').eq(3).text().trim());
        const isiText = $tr.find('td').eq(4).text().trim();
        $('#isi').val(isiText === '-' ? '' : isiText);
        $('#gdrive').val($tr.find('td').eq(5).text().trim());

        $('#formModalLabel').text('Edit Surat Keluar');
        new bootstrap.Modal(document.getElementById('formModal')).show();
    });

    // DELETE — ambil kolom dengan td.eq (untuk konfirmasi) & data-id untuk request
    $tbody.on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk menghapus', 'error');
            return;
        }

        const nomor = $tr.find('td').eq(1).text().trim();
        const tujuan = $tr.find('td').eq(2).text().trim();

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
                    url: '/spj/arsip_surat_keluar/' + id,
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
        $tr.append(`<td class="text-center">${newIndex}</td>`);
        $tr.append(`<td class="sk-nomor">${escapeHtml(d.nomor_dokumen)}</td>`);
        $tr.append(`<td class="sk-tujuan">${escapeHtml(d.tujuan)}</td>`);
        $tr.append(`<td class="sk-judul">${escapeHtml(d.judul_surat || '')}</td>`);
        $tr.append(`<td class="sk-isi">${escapeHtml(d.isi ? d.isi.substring(0,200) : '-')}</td>`);
        $tr.append(`<td class="sk-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</td>`);
        $tr.append(`<td class="text-center">
                        <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                    </td>`);
        $tbody.append($tr);
    }

    function updateRow(d) {
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        if (!$tr.length) return;
        $tr.find('td').eq(1).text(d.nomor_dokumen);
        $tr.find('td').eq(2).text(d.tujuan);
        $tr.find('td').eq(3).text(d.judul_surat || '');
        $tr.find('td').eq(4).text(d.isi ? d.isi.substring(0,200) : '-');
        $tr.find('td').eq(5).text(d.link_gdrive || '');
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
