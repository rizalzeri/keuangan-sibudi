@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Kelembagaan</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblArsip" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nama Dokumen</th>
                            <th style="width:200px">Nomor</th>
                            <th style="width:150px">Status</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_arsip_lembaga as $i => $d)
                            <tr>
                                <td>
                                    {{ $i+1 }}
                                    <span class="row-id d-none">{{ $d->id }}</span>
                                    <span class="row-link d-none">{{ $d->link_gdrive }}</span>
                                    <span class="row-nama d-none">{{ $d->nama_dokumen }}</span>
                                    <span class="row-nomor d-none">{{ $d->nomor }}</span>
                                    <span class="row-status d-none">{{ $d->status }}</span>
                                    <span class="row-link d-none">{{ $d->link_gdrive }}</span>

                                </td>
                                <td class="doc-nama">{{ $d->nama_dokumen }}</td>
                                <td class="doc-nomor">{{ $d->nomor }}</td>
                                <td class="doc-status">
                                    @if($d->status == 'Berlaku')
                                        <span class="badge bg-success">Berlaku</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Berlaku</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== Modal View (Detail) ===== -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Arsip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama Dokumen</dt>
                    <dd class="col-sm-8" id="viewNama">-</dd>

                    <dt class="col-sm-4">Nomor</dt>
                    <dd class="col-sm-8" id="viewNomor">-</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8" id="viewStatus">-</dd>

                    <dt class="col-sm-4">Link GDrive</dt>
                    <dd class="col-sm-8">
                        <a href="#" target="_blank" id="viewLink">Tidak ada link</a>
                    </dd>
                </dl>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== Modal Form (Tambah/Edit) ===== -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="arsipForm" action="/spj/arsip_kelembagaan" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Tambah Arsip</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
              <input type="hidden" name="_method" value="POST">
              <input type="hidden" id="rowIndex" name="id" value="">
              <div class="mb-3">
                  <label for="namaDokumen" class="form-label">Nama Dokumen</label>
                  <input type="text" id="namaDokumen" name="nama_dokumen" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="nomorDokumen" class="form-label">Nomor Dokumen</label>
                  <input type="text" id="nomorDokumen" name="nomor" class="form-control">
              </div>
              <div class="mb-3">
                  <label for="statusDokumen" class="form-label">Status</label>
                  <select id="statusDokumen" name="status" class="form-select">
                      <option value="Berlaku">Berlaku</option>
                      <option value="Tidak Berlaku">Tidak Berlaku</option>
                  </select>
              </div>
              <div class="mb-3">
                <label for="linkGdrive" class="form-label">Link GDrive</label>
                <input type="text" id="linkGdrive" name="link_gdrive" class="form-control">
              </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // pastikan meta csrf ada di layout
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const $tbody = $('#tblArsip tbody');
    const $form = $('#arsipForm');
    const createAction = $form.attr('action');

    // Reset modal form when hidden
    $('#formModal').on('hidden.bs.modal', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Arsip');
    });

    // Open Add
    $('#btn-add').on('click', function () {
        $form[0].reset();
        $form.find('input[name="_method"]').val('POST');
        $form.attr('action', createAction);
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Arsip');
    });

    // Submit (AJAX) - include _token and _method
    $form.on('submit', function (e) {
        e.preventDefault();
        const url = $form.attr('action');
        const methodOverride = ($form.find('input[name="_method"]').val() || 'POST').toUpperCase();
        const httpMethod = methodOverride === 'POST' ? 'POST' : methodOverride;

        const payload = {
            nama_dokumen: $('#namaDokumen').val().trim(),
            nomor: $('#nomorDokumen').val().trim() || '-',
            status: $('#statusDokumen').val(),
            link_gdrive: $('#linkGdrive').val().trim() || null,
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: methodOverride
        };

        if (!payload.nama_dokumen) {
            Swal.fire('Kesalahan', 'Nama dokumen wajib diisi', 'error');
            return;
        }

        $.ajax({
            url: url,
            method: httpMethod,
            data: payload,
            success: function (res) {
                if (httpMethod === 'POST') appendRow(res.data);
                else updateRow(res.data);

                const modal = bootstrap.Modal.getInstance(document.getElementById('formModal'));
                modal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message || (httpMethod === 'POST' ? 'Data tersimpan' : 'Data diperbarui'),
                    timer: 800,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(() => location.reload(), 1200);
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

    // EDIT: set form action and fill fields
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');

        const id     = $tr.find('.row-id').text().trim();
        const nama   = $tr.find('.row-nama').text().trim();
        const nomor  = $tr.find('.row-nomor').text().trim();
        const status = $tr.find('.row-status').text().trim();
        const link   = $tr.find('.row-link').text().trim();

        console.log("ID:", id);

        // set form
        $form.attr('action', '/spj/arsip_kelembagaan/' + id);
        $form.find('input[name="_method"]').val('PUT');

        $('#namaDokumen').val(nama);
        $('#nomorDokumen').val(nomor);
        $('#statusDokumen').val(status);
        $('#linkGdrive').val(link);

        new bootstrap.Modal(document.getElementById('formModal')).show();
    });


    // VIEW: isi modal view dengan data dari row (pastikan modal punya #viewLink etc)
    // VIEW
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');

        // Ambil link dari data attribute (dipakai saat appendRow men-set data-link)
        // atau fallback ke span.row-link jika ada di markup awal
        let link = ($tr.attr('data-link') || '').trim();
        if (!link) {
            link = $tr.find('.row-link').first().text().trim();
        }

        // normalize beberapa nilai yang menandakan "tidak ada"
        if (!link || link === 'null' || link === '-' ) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada Link GDrive',
                text: 'Tidak ada link GDrive yang diupload untuk dokumen ini.',
            });
            return;
        }

        // pastikan URL memiliki schema, jika user menyimpan tanpa http(s) tambahkan https://
        if (!/^https?:\/\//i.test(link)) {
            link = 'https://' + link;
        }

        // buka di tab baru
        window.open(link, '_blank');
    });




    // DELETE
    $tbody.on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');
        const id     = $tr.find('.row-id').text().trim();

        Swal.fire({
            title: 'Hapus data?',
            text: 'Data akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/spj/arsip_kelembagaan/' + id,
                    method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        $tr.remove();
                        reindexRows();
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: res.message || 'Data telah dihapus',
                            timer: 800,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                        setTimeout(() => location.reload(), 1200);
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
        const $tr = $('<tr>')
            .attr('data-index', newIndex - 1)
            .attr('data-id', d.id)
            .attr('data-link', d.link_gdrive || '')
            .attr('data-nama', d.nama_dokumen || '')
            .attr('data-nomor', d.nomor || '')
            .attr('data-status', d.status || '')
            .append(`<td>${newIndex}</td>`)
            .append(`<td class="doc-nama">${escapeHtml(d.nama_dokumen)}</td>`)
            .append(`<td class="doc-nomor">${escapeHtml(d.nomor || '-')}</td>`)
            .append(`<td class="doc-status">${d.status === 'Berlaku' ? '<span class="badge bg-success">Berlaku</span>' : '<span class="badge bg-secondary">Tidak Berlaku</span>'}</td>`)
            .append(`<td>
                        <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                    </td>`);
        $tbody.append($tr);
    }

    function updateRow(d) {
        const $tr = $tbody.find('tr[data-id="' + d.id + '"]');
        $tr.attr('data-link', d.link_gdrive || '');
        $tr.attr('data-nama', d.nama_dokumen || '');
        $tr.attr('data-nomor', d.nomor || '');
        $tr.attr('data-status', d.status || '');
        $tr.find('.doc-nama').text(d.nama_dokumen);
        $tr.find('.doc-nomor').text(d.nomor || '-');
        $tr.find('.doc-status').html(d.status === 'Berlaku' ? '<span class="badge bg-success">Berlaku</span>' : '<span class="badge bg-secondary">Tidak Berlaku</span>');
    }

    function reindexRows() {
        $tbody.find('tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
            $(this).attr('data-index', i);
        });
    }

    function escapeHtml(unsafe) {
        return (unsafe || '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
</script>
@endsection
