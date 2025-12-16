@extends('layouts.spj.main')

@section('container')
@php use Carbon\Carbon; Carbon::setLocale('id'); @endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Berkas Dokumen</h4>
        <button class="btn btn-primary" id="btnAddBerkas" data-bs-toggle="modal" data-bs-target="#formBerkasModal">
            <i class="bi bi-plus-lg"></i> Tambah Berkas
        </button>

    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblBerkas" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Tahun</th>
                            <th>Nama Dokumen</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $it)
                            @php
                                $raw = $it->tanggal_berkas_dokumen ? $it->tanggal_berkas_dokumen->format('Y-m-d') : '';
                                $year = $it->tanggal_berkas_dokumen ? $it->tanggal_berkas_dokumen->format('Y') : '-';
                            @endphp
                            <tr data-id="{{ $it->id }}" data-year="{{ $year }}">
                                <td>{{ $i + 1 }}</td>
                                <td class="berkas-year" data-date="{{ $raw }}">{{ $year }}</td>
                                <td class="berkas-nama">{{ $it->nama_dokumen ?? '-' }}</td>
                                <td class="">
                                    <span class="berkas-gdrive d-none">{{ $it->link_gdrive }}</span>
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

@include('spj.arsip_dokumentasi_berkas.components.modal_form')
@include('spj.arsip_dokumentasi_berkas.components.modal_view')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function(){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const $tbody = $('#tblBerkas tbody');
    const $form = $('#berkasForm');
    const createAction = $form.attr('action');

    // filter tahun
    $('#filterYear').on('change', function(){
        const val = $(this).val();
        if (!val) {
            $tbody.find('tr').show();
        } else {
            $tbody.find('tr').each(function(){
                $(this).toggle($(this).data('year').toString() === val.toString());
            });
        }
    });

    // reset modal
    $('#formBerkasModal').on('hidden.bs.modal', function(){
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexBerkas').val('');
        $('#formBerkasLabel').text('Tambah Berkas Dokumen');
        $('#berkasTanggal').val(new Date().toISOString().split('T')[0]);
    });

    // Tombol Tambah Berkas
    $('#btnAddBerkas').on('click', function () {
        $form[0].reset();
        $form.attr('action', createAction);
        $form.find('input[name="_method"]').val('POST');

        $('#formBerkasLabel').text('Tambah Berkas Dokumen');
        $('#rowIndexBerkas').val('');
        $('#berkasTanggal').val(new Date().toISOString().split('T')[0]);

        new bootstrap.Modal(document.getElementById('formBerkasModal')).show();
    });


    // submit AJAX
    $form.on('submit', function(e){
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            tanggal_berkas_dokumen: $('#berkasTanggal').val().trim(),
            nama_dokumen: $('#berkasNama').val().trim(),
            link_gdrive: $('#berkasGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.tanggal_berkas_dokumen) {
            Swal.fire('Kesalahan','Tanggal wajib diisi','error'); return;
        }

        $.ajax({
            url: url,
            method: httpMethod,
            data: payload,
            success: function(res){
                if (res.success) {
                    if (httpMethod === 'POST') appendRow(res.data);
                    else updateRow(res.data);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('formBerkasModal'));
                    if (modal) modal.hide();

                    Swal.fire({icon:'success',title:'Berhasil',text:res.message||'Sukses',timer:9000,showConfirmButton:false})
                        .then(()=>location.reload());
                    setTimeout(()=>location.reload(),12000);
                } else {
                    Swal.fire('Error', res.message||'Terjadi kesalahan','error');
                }
            },
            error: function(xhr){
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    const messages = Object.values(errors).flat().join('<br>');
                    Swal.fire({ icon:'error', title:'Validasi', html: messages });
                } else {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan pada server';
                    Swal.fire('Error', msg, 'error');
                }
            }
        });
    });

    // DOWNLOAD button (jika link_gdrive berisi URL file) — membuka di tab baru / memaksa download jika server menyediakan
    $tbody.on('click', '.btn-download', function(){
        const file = $(this).data('file') || '';
        if (!file) {
            Swal.fire('Info','Tidak ada file untuk diunduh','info'); return;
        }
        // jika file adalah link GDrive langsung buka
        window.open(file, '_blank');
    });
    const $tr = $(this).closest('tr');

    // VIEW
    $tbody.on('click', '.btn-view', function(){
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');
        const year = $tr.data('year') || '-';
        const rawDate = tds.eq(1).data('date') || '';
        const nama = tds.eq(2).text().trim() || '-';
        const gdrive = $tr.find('.berkas-gdrive').text().trim() || '';

        $('#viewBerkasYear').text(year);
        $('#viewBerkasTanggalFull').text(rawDate || '-');
        $('#viewBerkasNama').text(nama);

        if (gdrive) {
            $('#viewBerkasGdrive').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewBerkasGdrive').attr('href','#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewBerkasModal')).show();
    });

    // EDIT
    $tbody.on('click', '.btn-edit', function(){
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        const tds = $tr.find('td');

        if (!id) { Swal.fire('Error','ID tidak ditemukan untuk edit','error'); return; }

        $form.attr('action', '/spj/arsip_dokumentasi_berkas_dokumen/' + id);
        $form.find('input[name="_method"]').val('PUT');

        const rawDate = tds.eq(1).data('date') || '';
        const nama = tds.eq(2).text().trim() || '';
        const gdrive = $tr.find('.berkas-gdrive').text().trim() || '';

        $('#berkasTanggal').val(rawDate);
        $('#berkasNama').val(nama);
        $('#berkasGdrive').val(gdrive || '');

        $('#formBerkasLabel').text('Edit Berkas Dokumen');
        new bootstrap.Modal(document.getElementById('formBerkasModal')).show();
    });

    // DELETE
    $tbody.on('click', '.btn-delete', function(){
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        if (!id) { Swal.fire('Error','ID tidak ditemukan untuk menghapus','error'); return; }

        Swal.fire({
            title: 'Hapus data?',
            html: `<small>Data akan dihapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result)=>{
            if (result.isConfirmed) {
                $.ajax({
                    url: '/spj/arsip_dokumentasi_berkas_dokumen/' + id,
                    method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(res) {
                        if (res.success) {
                            $tr.remove();
                            reindexRows();
                            Swal.fire({ icon:'success', title:'Terhapus', text: res.message || 'Data dihapus', timer:800, showConfirmButton:false })
                                .then(()=> location.reload());
                            setTimeout(()=> location.reload(),1200);
                        } else {
                            Swal.fire('Error', res.message || 'Gagal menghapus', 'error');
                        }
                    },
                    error: function(xhr){
                        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan pada server';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    // helpers for DOM updates (optional)
    function appendRow(d){
        const newIndex = $tbody.find('tr').length + 1;
        const dt = d.tanggal_berkas_dokumen ? new Date(d.tanggal_berkas_dokumen) : null;
        const year = dt ? dt.getFullYear() : '-';
        const raw = dt ? `${dt.getFullYear()}-${String(dt.getMonth()+1).padStart(2,'0')}-${String(dt.getDate()).padStart(2,'0')}` : '';

        const $tr = $('<tr>').attr('data-id', d.id).attr('data-year', year);
        $tr.append(`<td>${newIndex}</td>`);
        $tr.append(`<td class="berkas-year" data-date="${raw}">${year}</td>`);
        $tr.append(`<td class="berkas-nama">${escapeHtml(d.nama_dokumen || '-')}</td>`);
        $tr.append(`<td class="">
            <span class="berkas-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
            <button class="btn btn-sm btn-info btn-view" data-id="${d.id}"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-warning btn-edit" data-id="${d.id}"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${d.id}"><i class="bi bi-trash"></i></button>
        </td>`);
        $tbody.append($tr);
    }

    function updateRow(d){
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        if (!$tr.length) return;
        const dt = d.tanggal_berkas_dokumen ? new Date(d.tanggal_berkas_dokumen) : null;
        const year = dt ? dt.getFullYear() : '-';
        const raw = dt ? `${dt.getFullYear()}-${String(dt.getMonth()+1).padStart(2,'0')}-${String(dt.getDate()).padStart(2,'0')}` : '';

        $tr.attr('data-year', year);
        $tr.find('td').eq(1).text(year).attr('data-date', raw);
        $tr.find('td').eq(2).text(d.nama_dokumen || '-');
        $tr.find('.berkas-gdrive').text(d.link_gdrive || '');
    }

    function reindexRows(){
        $tbody.find('tr').each(function(i){
            $(this).find('td:first').text(i+1);
        });
    }

    function escapeHtml(str){
        return (str || '').toString()
            .replace(/&/g,"&amp;")
            .replace(/</g,"&lt;")
            .replace(/>/g,"&gt;")
            .replace(/"/g,"&quot;")
            .replace(/'/g,"&#039;");
    }

    if ($.fn.DataTable) {
        try { $('#tblBerkas').DataTable(); } catch (e) { console.warn('DataTable init failed', e); }
    }
});
flatpickr("#berkasTanggal", {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "l, d F Y",
    locale: "id", // Bahasa Indonesia
    allowInput: true
});
</script>
@endsection
