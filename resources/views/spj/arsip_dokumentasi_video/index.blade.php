@extends('layouts.spj.main')

@section('container')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Video</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formVideoModal" id="btn-add-video">
            <i class="bi bi-plus-lg"></i> Tambah Dokumentasi
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblVideo" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Hari, Tanggal</th>
                            <th>Kegiatan</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $v)
                            @php
                                $rawDate = $v->tanggal_video ? (string) $v->tanggal_video : '';
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
                            <tr data-id="{{ $v->id }}">
                                <td class="">{{ $i + 1 }}</td>
                                <td class="video-tanggal" data-date="{{ $rawIso }}">{{ $visible }}</td>
                                <td class="video-kegiatan">{{ $v->kegiatan ?? '-' }}</td>
                                <td class="">
                                    <span class="video-gdrive d-none">{{ $v->link_gdrive }}</span>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $v->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $v->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $v->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('spj.arsip_dokumentasi_video.components.modal_form')
@include('spj.arsip_dokumentasi_video.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const $tbody = $('#tblVideo tbody');
    const $form = $('#videoForm');
    const createAction = $form.attr('action');

    // Reset modal when closed
    $('#formVideoModal').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexVideo').val('');
        $('#formVideoLabel').text('Tambah Dokumentasi Video');
        $('#videoTanggal').val(new Date().toISOString().split('T')[0]);
    });

    // Open Add
    $('#btn-add-video').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexVideo').val('');
        $('#formVideoLabel').text('Tambah Dokumentasi Video');
        $('#videoTanggal').val(new Date().toISOString().split('T')[0]);
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            tanggal_video: $('#videoTanggal').val().trim(),
            kegiatan: $('#videoKegiatan').val().trim(),
            link_gdrive: $('#videoGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.tanggal_video) {
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formVideoModal'));
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

        // ambil link dari span tersembunyi
        let link = $tr.find('.video-gdrive').text().trim() || '';

        // 🔥 KONDISI SESUAI PERMINTAAN
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

        // buka di tab baru
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

        $form.attr('action', '/spj/arsip_dokumentasi_video/' + id);
        $form.find('input[name="_method"]').val('PUT');

        // tanggalVisible example: "Senin, 15-12-2025"
        let tanggalVisible = tds.eq(1).text().trim() || '';
        let tanggalRaw = '';
        if (tanggalVisible.includes(',')) {
            let tgl = tanggalVisible.split(',')[1].trim(); // "15-12-2025"
            const parts = tgl.split('-'); // [DD,MM,YYYY]
            if (parts.length === 3) {
                tanggalRaw = `${parts[2]}-${parts[1]}-${parts[0]}`; // YYYY-MM-DD
            }
        } else {
            // fallback: read data-date attribute if present
            tanggalRaw = tds.eq(1).data('date') || '';
        }

        const kegiatan = tds.eq(2).text().trim() || '';
        const gdrive = $tr.find('.video-gdrive').text().trim() || '';

        $('#videoTanggal').val(tanggalRaw);
        $('#videoKegiatan').val(kegiatan);
        $('#videoGdrive').val(gdrive || '');

        $('#formVideoLabel').text('Edit Dokumentasi Video');
        new bootstrap.Modal(document.getElementById('formVideoModal')).show();
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
                    url: '/spj/arsip_dokumentasi_video/' + id,
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
        const dt = d.tanggal_video ? new Date(d.tanggal_video) : null;
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
        $tr.append(`<td class="video-tanggal" data-date="${raw}">${visible}</td>`);
        $tr.append(`<td class="video-kegiatan">${escapeHtml(d.kegiatan || '-')}</td>`);
        $tr.append(`
            <td class="">
                <span class="video-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
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

        let visible = '-';
        let raw = '';
        if (d.tanggal_video) {
            const dt = new Date(d.tanggal_video);
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
        $tr.find('.video-gdrive').text(d.link_gdrive || '');
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

    if ($.fn.DataTable) {
        try { $('#tblVideo').DataTable(); } catch (e) { console.warn('DataTable init failed', e); }
    }
});

flatpickr("#videoTanggal", {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "l, d F Y",
    locale: "id", // Bahasa Indonesia
    allowInput: true
});
</script>
@endsection
