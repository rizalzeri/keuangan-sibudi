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
        <!-- Urutan baru: No | Nama Kerjasama | Nomor Dokumen | Pihak | Bentuk Kerjasama | Deskripsi | Durasi | Aksi -->
        <table id="tblPerjanjian" class="table datatable">
          <thead class="table-light">
            <tr>
              <th style="width:60px">No</th>
              <th>Nama Kerjasama</th>
              <th>Nomor Dokumen</th>
              <th>Pihak</th>
              <th>Bentuk Kerjasama</th>
              <th>Deskripsi</th>
              <th>Durasi Kerjasama</th>
              <th style="width:180px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $i => $it)
              <tr data-id="{{ $it->id }}">
                <td>{{ $i + 1 }}</td>
                <td class="pk-nama">{{ $it->nama_kerjasama ?? '-' }}</td>
                <td class="pk-nomor">{{ $it->nomor_dokumen }}</td>
                <td class="pk-pihak">{{ $it->pihak ?? '-' }}</td>
                <td class="pk-bentuk">{{ $it->bentuk_kerja_sama ?? '-' }}</td>
                <td class="pk-deskripsi">{!! nl2br(e(Str::limit($it->deskripsi, 200))) !!}</td>
                <td class="pk-durasi">
                  @if(isset($it->durasi) && strtolower($it->durasi) === 'selesai')
                    <span class="badge bg-success">Selesai</span>
                  @else
                    <span class="badge bg-info text-dark">Berjalan</span>
                  @endif
                </td>
                <td class="">
                  <span class="pk-gdrive d-none">{{ $it->link_gdrive }}</span>

                  <!-- tombol-tombol aksi: view, edit, delete, dan (jika Berjalan) tombol complete -->
                  <button class="btn btn-sm btn-info btn-view" data-id="{{ $it->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $it->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $it->id }}" title="Hapus"><i class="bi bi-trash"></i></button>

                  @if(!isset($it->durasi) || strtolower($it->durasi) !== 'selesai')
                    <button class="btn btn-sm btn-success btn-complete" data-id="{{ $it->id }}" title="Tandai Selesai"><i class="bi bi-check-lg"></i></button>
                  @endif
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
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

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

        // hide nomor & durasi wrapper on close (create)
        $('#perjNomorWrapper').addClass('d-none');
        $('#perjNomor').val('');
        $('#perjDurasiWrapper').addClass('d-none');
        $('#perjDurasi').val('Berjalan'); // default
    });


    // Open Add
    $('#btn-add-perj').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndexPerj').val('');
        $('#formModalLabelPerj').text('Tambah Perjanjian Kerja');
        $('#perjNomorWrapper').addClass('d-none');
        $('#perjNomor').val('');
        $('#perjDurasiWrapper').addClass('d-none');
        $('#perjDurasi').val('Berjalan');
    });

    // Submit via AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            nama_kerjasama: $('#perjNama').val().trim(),
            pihak: $('#perjPihak').val().trim(),
            bentuk_kerja_sama: $('#perjBentuk').val().trim(),
            deskripsi: $('#perjDeskripsi').val().trim(),
            link_gdrive: $('#perjGdrive').val().trim(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        // include nomor only when editing (PUT)
        if (httpMethod !== 'POST') {
            payload.nomor_dokumen = $('#perjNomor').val().trim();
            payload.durasi = $('#perjDurasi').val();
        }

        // simple client-side validation
        if (!payload.nama_kerjasama) {
            Swal.fire('Kesalahan', 'Nama Kerjasama wajib diisi', 'error');
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

                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || (httpMethod === 'POST' ? 'Data tersimpan' : 'Data diperbarui'), timer: 900, showConfirmButton: false })
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

        const nama = tds.eq(1).text().trim() || '-';
        const nomor = tds.eq(2).text().trim() || '-';
        const pihak = tds.eq(3).text().trim() || '-';
        const bentuk = tds.eq(4).text().trim() || '-';
        const deskripsiHtml = tds.eq(5).html().trim() || '-';
        const durasiBadgeHtml = tds.eq(6).html().trim() || '-';
        const gdrive = tds.eq(7).find('.pk-gdrive').text().trim() || '';

        // set modal
        $('#viewPerjNama').text(nama);
        $('#viewPerjNomor').text(nomor);
        $('#viewPerjPihak').text(pihak);
        $('#viewPerjBentuk').text(bentuk);
        $('#viewPerjDeskripsi').html(deskripsiHtml === '' ? '-' : deskripsiHtml);
        $('#viewPerjDurasi').html(durasiBadgeHtml === '' ? '-' : durasiBadgeHtml);

        if (gdrive) {
            $('#viewPerjGdrive').attr('href', gdrive).text('Buka GDrive').removeClass('text-muted');
        } else {
            $('#viewPerjGdrive').attr('href', '#').text('Tidak ada link').addClass('text-muted');
        }

        new bootstrap.Modal(document.getElementById('viewModalPerj')).show();
    });

    // EDIT
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const tds = $tr.find('td');
        const id = $(this).data('id');
        if (!id) { Swal.fire('Error', 'ID tidak ditemukan untuk edit', 'error'); return; }

        // set form action
        $form.attr('action', '/spj/arsip_perjanjian_kerja/' + id);
        $form.find('input[name="_method"]').val('PUT');

        // ambil data dari baris tabel
        const nama = tds.eq(1).text().trim();
        const nomor = tds.eq(2).text().trim();
        const pihak = tds.eq(3).text().trim();
        const bentuk = tds.eq(4).text().trim();
        const deskripsi = tds.eq(5).text().trim();

        // durasi: td index 6 kemungkinan berisi badge -> ambil textnya
        let durasiText = tds.eq(6).text().trim();
        // normalisasi: jika kosong atau bukan 'Selesai' -> treat as 'Berjalan'
        durasiText = (durasiText && durasiText.toLowerCase() === 'selesai') ? 'Selesai' : 'Berjalan';

        const gdrive = tds.eq(7).find('.pk-gdrive').text().trim();

        // set ke dalam form
        $('#perjNama').val(nama || '');
        $('#perjNomor').val(nomor || '');
        $('#perjPihak').val(pihak || '');
        $('#perjBentuk').val(bentuk || '');
        $('#perjDeskripsi').val(deskripsi || '');
        $('#perjGdrive').val(gdrive || '');

        // show nomor & durasi field during edit
        $('#perjNomorWrapper').removeClass('d-none');

        // set durasi dropdown and show wrapper
        $('#perjDurasi').val(durasiText);
        $('#perjDurasiWrapper').removeClass('d-none');

        $('#formModalLabelPerj').text('Edit Perjanjian Kerja');
        new bootstrap.Modal(document.getElementById('formModalPerj')).show();
    });


    // COMPLETE (tombol checklist)
    $tbody.on('click', '.btn-complete', function () {
        const $btn = $(this);
        const id = $btn.data('id');
        if (!id) { Swal.fire('Error', 'ID tidak ditemukan', 'error'); return; }

        Swal.fire({
            title: 'Tandai sebagai Selesai?',
            text: 'Data akan diubah menjadi Selesai.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, selesaikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '/spj/arsip_perjanjian_kerja/' + id + '/complete',
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.success) {
                        // update row via updateRow that expects full data
                        updateRow(res.data);
                        Swal.fire({ icon:'success', title:'Selesai', text: res.message || 'Durasi diubah menjadi Selesai', timer:900, showConfirmButton:false })
                          .then(() => location.reload());
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        Swal.fire('Error', res.message || 'Gagal mengubah status', 'error');
                    }
                },
                error: function (xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan pada server';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });

    // DELETE
    $tbody.on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        if (!id) { Swal.fire('Error', 'ID tidak ditemukan untuk menghapus', 'error'); return; }

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
                            $tr.remove(); reindexRows();
                            Swal.fire({ icon:'success', title:'Terhapus', text: res.message || 'Data dihapus', timer:800, showConfirmButton:false })
                              .then(() => location.reload());
                            setTimeout(() => location.reload(), 1200);
                        } else Swal.fire('Error', res.message || 'Gagal menghapus', 'error');
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
        const durasiHtml = (d.durasi && d.durasi.toLowerCase() === 'selesai')
            ? '<span class="badge bg-success">Selesai</span>'
            : '<span class="badge bg-info text-dark">Berjalan</span>';

        const $tr = $('<tr>').attr('data-id', d.id);
        $tr.append(`<td class="">${newIndex}</td>`);
        $tr.append(`<td class="pk-nama">${escapeHtml(d.nama_kerjasama || '-')}</td>`);
        $tr.append(`<td class="pk-nomor">${escapeHtml(d.nomor_dokumen || '')}</td>`);
        $tr.append(`<td class="pk-pihak">${escapeHtml(d.pihak || '-')}</td>`);
        $tr.append(`<td class="pk-bentuk">${escapeHtml(d.bentuk_kerja_sama || '-')}</td>`);
        $tr.append(`<td class="pk-deskripsi">${escapeHtml(d.deskripsi ? d.deskripsi.substring(0,200) : '-')}</td>`);
        $tr.append(`<td class="pk-durasi">${durasiHtml}</td>`);

        // build actions: show complete button only if belum selesai
        let actionHtml = `<span class="pk-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-warning btn-edit" data-id="${d.id}" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${d.id}" title="Hapus"><i class="bi bi-trash"></i></button>`;

        if (!d.durasi || d.durasi.toLowerCase() !== 'selesai') {
            actionHtml += ` <button class="btn btn-sm btn-success btn-complete" data-id="${d.id}" title="Tandai Selesai"><i class="bi bi-check-lg"></i></button>`;
        }

        $tr.append(`<td class="">${actionHtml}</td>`);
        $tbody.append($tr);
    }

    function updateRow(d) {
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        if (!$tr.length) return;

        // update cells according to column order
        $tr.find('td').eq(1).text(d.nama_kerjasama || '-');
        $tr.find('td').eq(2).text(d.nomor_dokumen || '');
        $tr.find('td').eq(3).text(d.pihak || '-');
        $tr.find('td').eq(4).text(d.bentuk_kerja_sama || '-');
        $tr.find('td').eq(5).text(d.deskripsi ? d.deskripsi.substring(0,200) : '-');

        // durasi -> badge
        const durasiHtml = (d.durasi && d.durasi.toLowerCase() === 'selesai')
            ? '<span class="badge bg-success">Selesai</span>'
            : '<span class="badge bg-info text-dark">Berjalan</span>';
        $tr.find('td').eq(6).html(durasiHtml);

        // update gdrive hidden
        $tr.find('.pk-gdrive').text(d.link_gdrive || '');

        // update action buttons: replace last td
        let actionHtml = `<span class="pk-gdrive d-none">${escapeHtml(d.link_gdrive || '')}</span>
            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-warning btn-edit" data-id="${d.id}" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${d.id}" title="Hapus"><i class="bi bi-trash"></i></button>`;

        if (!d.durasi || d.durasi.toLowerCase() !== 'selesai') {
            actionHtml += ` <button class="btn btn-sm btn-success btn-complete" data-id="${d.id}" title="Tandai Selesai"><i class="bi bi-check-lg"></i></button>`;
        }

        $tr.find('td').eq(7).html(actionHtml);
    }

    function reindexRows() {
        $tbody.find('tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    }

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
    }

    // optional: init DataTable
    if ($.fn.DataTable) {
        try { $('#tblPerjanjian').DataTable(); } catch (e) { console.warn('DataTable init failed:', e); }
    }
});
</script>
@endsection
