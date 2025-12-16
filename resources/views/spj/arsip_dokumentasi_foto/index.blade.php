@extends('layouts.spj.main')

@section('container')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Foto</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formFotoModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Dokumentasi
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblFoto" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Hari, Tanggal</th>
                            <th>Kegiatan</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $f)
                            @php
                                // aman: jika tanggal disimpan sebagai string atau Carbon
                                $rawDate = $f->tanggal_foto ? (string) $f->tanggal_foto : '';
                                try {
                                    $dt = $rawDate ? Carbon::parse($rawDate) : null;
                                    $dayName = $dt ? $dt->translatedFormat('l') : '';
                                    $visible = $dt ? $dayName . ', ' . $dt->format('d-m-Y') : '-';
                                    $rawIso = $dt ? $dt->format('Y-m-d') : '';
                                } catch (\Exception $e) {
                                    $visible = $rawDate;
                                    $rawIso = $rawDate;
                                }
                            @endphp
                            <tr data-id="{{ $f->id }}">
                                <td class="">{{ $i + 1 }}</td>
                                <td class="foto-tanggal" data-date="{{ $f->tanggal_foto }}">{{ $visible }}</td>
                                <td class="foto-kegiatan">{{ $f->kegiatan ?? '-' }}</td>
                                <td class="">
                                    <span class="foto-gdrive d-none">{{ $f->link_gdrive }}</span>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $f->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $f->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $f->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('spj.arsip_dokumentasi_foto.components.modal_form')
@include('spj.arsip_dokumentasi_foto.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const $tbody = $('#tblFoto tbody');
    const $form = $('#fotoForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formFotoModal').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formFotoLabel').text('Tambah Dokumentasi');
        // default date today
        $('#tanggal').val(new Date().toISOString().split('T')[0]);
    });

    // Open Add
    $('#btn-add').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formFotoLabel').text('Tambah Dokumentasi');
        $('#tanggal').val(new Date().toISOString().split('T')[0]);
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            tanggal_foto: $('#tanggal').val().trim(),
            kegiatan: $('#kegiatan').val().trim(),
            link_gdrive: $('#gdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.tanggal_foto) {
            Swal.fire('Kesalahan', 'Tanggal wajib diisi', 'error');
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formFotoModal'));
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

    // VIEW (td.eq indexing)
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');

        const tanggalVisible = tds.eq(1).text().trim() || '-';
        const tanggalRaw = tds.eq(1).data('date') || '';
        console.log('tanggalRaw',tanggalRaw)
        console.log('tanggalVisible',tanggalVisible)
        const kegiatanHtml = tds.eq(2).html().trim() || '-';
        const gdrive = tds.eq(3) ? tds.eq(3).find('.foto-gdrive').text().trim() : $tr.find('.foto-gdrive').text().trim() || '';

        $('#viewFotoTanggal').text(tanggalVisible);
        $('#viewFotoKegiatan').html(kegiatanHtml === '' ? '-' : kegiatanHtml);

        if (gdrive) {
            $('#viewFotoGdrive').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewFotoGdrive').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewFotoModal')).show();
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

        $form.attr('action', '/spj/arsip_dokumentasi_foto/' + id);
        $form.find('input[name="_method"]').val('PUT');

        let tanggalVisible = tds.eq(1).text().trim() || '';
        let tanggalRaw = '';

        if (tanggalVisible.includes(',')) {
            // ambil bagian setelah koma: "15-12-2025"
            let tgl = tanggalVisible.split(',')[1].trim();
            // convert ke YYYY-MM-DD
            let parts = tgl.split('-'); // [DD, MM, YYYY]
            tanggalRaw = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        const kegiatan = tds.eq(2).text().trim() || '';
        const gdrive = $tr.find('.foto-gdrive').text().trim() || '';

        $('#tanggal').val(tanggalRaw);
        $('#kegiatan').val(kegiatan);
        $('#gdrive').val(gdrive || '');

        $('#formFotoLabel').text('Edit Dokumentasi');
        new bootstrap.Modal(document.getElementById('formFotoModal')).show();
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
                    url: '/spj/arsip_dokumentasi_foto/' + id,
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
        // format tanggal client-side: show Day, dd-mm-YYYY
        const dt = d.tanggal_foto ? new Date(d.tanggal_foto) : null;
        let visible = '-';
        let raw = '';
        if (dt) {
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const dayName = days[dt.getDay()];
            const dd = String(dt.getDate()).padStart(2,'0');
            const mm = String(dt.getMonth()+1).padStart(2,'0');
            const yyyy = dt.getFullYear();
            visible = `${dayName}, ${dd}-${mm}-${yyyy}`;
            raw = `${yyyy}-${mm}-${dd}`;
        }

        const $tr = $('<tr>').attr('data-id', d.id);
        $tr.append(`<td class="">${newIndex}</td>`);
        $tr.append(`<td class="foto-tanggal" data-date="${raw}">${visible}</td>`);
        $tr.append(`<td class="foto-kegiatan">${escapeHtml(d.kegiatan || '-')}</td>`);
        $tr.append(`
            <td class="">
                <span class="foto-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
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

        // update tanggal visible + data-date
        let visible = '-';
        let raw = '';
        if (d.tanggal_foto) {
            const dt = new Date(d.tanggal_foto);
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const dayName = days[dt.getDay()];
            const dd = String(dt.getDate()).padStart(2,'0');
            const mm = String(dt.getMonth()+1).padStart(2,'0');
            const yyyy = dt.getFullYear();
            visible = `${dayName}, ${dd}-${mm}-${yyyy}`;
            raw = `${yyyy}-${mm}-${dd}`;
        }

        $tr.find('td').eq(1).text(visible).attr('data-date', raw);
        $tr.find('td').eq(2).text(d.kegiatan || '-');
        $tr.find('.foto-gdrive').text(d.link_gdrive || '');
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
        try { $('#tblFoto').DataTable(); } catch (e) { console.warn('DataTable init failed', e); }
    }
});

flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "l, d F Y",
    locale: "id", // Bahasa Indonesia
    allowInput: true
});
</script>
@endsection
