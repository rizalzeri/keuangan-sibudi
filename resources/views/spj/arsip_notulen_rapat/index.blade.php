@extends('layouts.spj.main')

@section('container')
@php use Illuminate\Support\Str; use Carbon\Carbon; @endphp

@php
// helper kecil untuk format tanggal ke format Indonesia with day name (Senin, dd/mm/YYYY)
function format_date_id($iso){
    if (!$iso) return '-';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    try {
        $c = \Carbon\Carbon::parse($iso);
        $day = $days[(int)$c->format('w')];
        return $day . ',' . $c->format('d/m/Y');
    } catch (\Exception $e) {
        return $iso;
    }
}
@endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Notulen Rapat</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModalNotulen" id="btn-add-notulen">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblNotulen" class="table table-sm datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Hari, Tanggal</th>
                            <th>Waktu</th>
                            <th>Tempat</th>
                            <th>Agenda</th>
                            <th>Penyelenggara</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $n)
                            <tr data-id="{{ $n->id }}">
                                <td class="text-center">{{ $i + 1 }}</td>

                                <td class="nr-date">
                                    <span class="nr-date-visible">{{ format_date_id($n->tanggal_notulen_rapat) }}</span>
                                    <span class="nr-date-raw d-none">{{ $n->tanggal_notulen_rapat ? $n->tanggal_notulen_rapat->format('Y-m-d') : '' }}</span>
                                </td>

                                <td class="nr-waktu">{{ $n->waktu ?? '-' }}</td>
                                <td class="nr-tempat">{{ $n->tempat ?? '-' }}</td>
                                <td class="nr-agenda">{{ $n->agenda ?? '-' }}</td>
                                <td class="nr-penyelenggara">{{ $n->penyelenggara ?? '-' }}</td>

                                <td class="text-center">
                                    <span class="nr-gdrive d-none">{{ $n->link_gdrive }}</span>
                                    <a href="/spj/arsip_notulen_rapat/{{ $n->id }}/cetak"
                                        class="btn btn-sm btn-success btn-cetak"
                                        data-id="{{ $n->id }}"
                                        title="Cetak PDF"
                                        target="_blank">
                                         <i class="bi bi-printer"></i>
                                     </a>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $n->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $n->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $n->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('spj.arsip_notulen_rapat.components.modal_form')
@include('spj.arsip_notulen_rapat.components.modal_view')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Timepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"/>

<!-- jQuery Timepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const $tbody = $('#tblNotulen tbody');
    const $form = $('#notulenForm');
    const createAction = $form.attr('action');

    // helper format tanggal JS same as earlier (optional)
    function formatDateID(iso) {
        if (!iso) return '';
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const parts = iso.split('-');
        if (parts.length !== 3) return iso;
        const y = parseInt(parts[0],10), m = parseInt(parts[1],10)-1, d = parseInt(parts[2],10);
        const dt = new Date(y,m,d);
        const dayName = days[dt.getDay()];
        const dd = String(d).padStart(2,'0');
        const mm = String(m+1).padStart(2,'0');
        return `${dayName},${dd}/${mm}/${y}`;
    }

    // reset modal ketika ditutup
    $('#formModalNotulen').on('hidden.bs.modal', function () {
        // reset method & action
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);

        // reset label
        $('#formModalLabelNotulen').text('Tambah Notulen Rapat');

        // reset form, tapi JANGAN override tanggal otomatis
        $form.trigger('reset');
    });


    // set default date once
    $('#tanggalNotulen').val(new Date().toISOString().split('T')[0]);

    // submit AJAX (create/update)
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            tanggal_notulen_rapat: $('#tanggalNotulen').val().trim(),
            waktu: $('#waktuNotulen').val().trim(),
            tempat: $('#tempatNotulen').val().trim(),
            agenda: $('#agendaNotulen').val().trim(),
            penyelenggara: $('#penyelenggaraNotulen').val().trim(),
            link_gdrive: $('#gdriveNotulen').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.tanggal_notulen_rapat) {
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formModalNotulen'));
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

    // VIEW (using td.eq)
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');

        // ambil link dari span tersembunyi
        let link = $tr.find('.nr-gdrive').text().trim() || '';

        // kondisi link kosong / tidak valid
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

    // EDIT (using td.eq; raw date stored inside date cell)
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');
        const id = $(this).data('id');

        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk edit', 'error');
            return;
        }

        $form.attr('action', '/spj/arsip_notulen_rapat/' + id);
        $form.find('input[name="_method"]').val('PUT');

        const rawDate = tds.eq(1).find('.nr-date-raw').text().trim() || new Date().toISOString().split('T')[0];
        const waktu = tds.eq(2).text().trim() || '';
        const tempat = tds.eq(3).text().trim() || '';
        const agenda = tds.eq(4).text().trim() || '';
        const penyelenggara = tds.eq(5).text().trim() || '';
        const gdrive = tds.eq(6).find('.nr-gdrive').text().trim() || '';

        $('#tanggalNotulen').val(rawDate);
        $('#waktuNotulen').val(waktu);
        $('#tempatNotulen').val(tempat);
        $('#agendaNotulen').val(agenda);
        $('#penyelenggaraNotulen').val(penyelenggara);
        $('#gdriveNotulen').val(gdrive);

        $('#formModalLabelNotulen').text('Edit Notulen Rapat');
        new bootstrap.Modal(document.getElementById('formModalNotulen')).show();
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
                    url: '/spj/arsip_notulen_rapat/' + id,
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

    const baseUrl = window.location.origin;

    // Event untuk cetak PDF
    $tbody.on('click', '.btn-cetak', function (e) {
        const id = $(this).data('id');
        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan untuk cetak', 'error');
            return;
        }

        // Buka PDF di tab baru
        window.open(`${baseUrl}/spj/arsip_notulen_rapat/${id}/cetak`, '_blank');
    });

    // Juga update fungsi appendRow dan updateRow untuk menambahkan button cetak:
    function appendRow(d) {
        const newIndex = $tbody.find('tr').length + 1;
        const formatted = (d.tanggal_notulen_rapat) ? formatDateID(d.tanggal_notulen_rapat) : '';
        const $tr = $('<tr>').attr('data-id', d.id);

        $tr.append(`<td class="text-center">${newIndex}</td>`);
        $tr.append(`<td class="nr-date"><span class="nr-date-visible">${escapeHtml(formatted)}</span><span class="nr-date-raw d-none">${escapeHtml(d.tanggal_notulen_rapat || '')}</span></td>`);
        $tr.append(`<td class="nr-waktu">${escapeHtml(d.waktu || '-')}</td>`);
        $tr.append(`<td class="nr-tempat">${escapeHtml(d.tempat || '-')}</td>`);
        $tr.append(`<td class="nr-agenda">${escapeHtml(d.agenda || '-')}</td>`);
        $tr.append(`<td class="nr-penyelenggara">${escapeHtml(d.penyelenggara || '-')}</td>`);
        $tr.append(`
            <td class="text-center">
                <span class="nr-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
                <a href="${baseUrl}/spj/arsip_notulen_rapat/${d.id}/cetak"
                class="btn btn-sm btn-success btn-cetak"
                data-id="${d.id}"
                title="Cetak PDF"
                target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
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

        $tr.find('td').eq(1).find('.nr-date-visible').text(d.tanggal_notulen_rapat ? formatDateID(d.tanggal_notulen_rapat) : '-');
        $tr.find('td').eq(1).find('.nr-date-raw').text(d.tanggal_notulen_rapat || '');
        $tr.find('td').eq(2).text(d.waktu || '-');
        $tr.find('td').eq(3).text(d.tempat || '-');
        $tr.find('td').eq(4).text(d.agenda || '-');
        $tr.find('td').eq(5).text(d.penyelenggara || '-');
        $tr.find('.nr-gdrive').text(d.link_gdrive || '');
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

    // optional: init datatable if available
    if ($.fn.DataTable) {
        try { $('#tblNotulen').DataTable(); } catch (e) { console.warn('DataTable init failed', e); }
    }
    flatpickr("#tanggalNotulen", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "l, d F Y",
        locale: "id",
        allowInput: true
    });
    // flatpickr("#waktuNotulen", {
    //     enableTime: true,
    //     noCalendar: true,
    //     dateFormat: "H:i",
    //     time_24hr: true
    // });

});


</script>
@endsection
