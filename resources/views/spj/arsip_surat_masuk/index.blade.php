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
                        @php
                            $surats = [
                                ['pengirim'=>'Kantor Desa','judul'=>'Undangan Rapat','isi'=>'Undangan rapat pembahasan ...','gdrive'=>'https://drive.google.com/file/d/xxx'],
                                ['pengirim'=>'Dinas Sosial','judul'=>'Laporan','isi'=>'Laporan kegiatan ...','gdrive'=>''],
                                ['pengirim'=>'Warga','judul'=>'Pengaduan','isi'=>'Pengaduan mengenai ...','gdrive'=>'https://drive.google.com/file/d/yyy'],
                            ];
                        @endphp
                        @foreach ($surats as $i => $s)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="surat-pengirim">{{ $s['pengirim'] }}</td>
                                <td class="surat-judul">{{ $s['judul'] }}</td>
                                <td class="surat-isi">{{ $s['isi'] }}</td>
                                <td class="text-center">
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

@include('spj.arsip_surat_masuk.components.modal_form')
@include('spj.arsip_surat_masuk.components.modal_view')

<script>
$(function () {
    // Simpan referensi table body
    const $tbody = $('#tblSurat tbody');

    // Reset modal ketika ditutup
    $('#formModal').on('hidden.bs.modal', function () {
        $('#suratForm')[0].reset();
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Masuk');
    });

    // Menangani submit form (Tambah / Edit) — client-side demo
    $('#suratForm').on('submit', function (e) {
        e.preventDefault();

        const pengirim = $('#pengirim').val().trim();
        const judul = $('#judul').val().trim();
        const isi = $('#isi').val().trim() || '-';
        const gdrive = $('#gdrive').val().trim();

        if (!pengirim || !judul) {
            alert('Pengirim dan Judul wajib diisi');
            return;
        }

        const idx = $('#rowIndex').val();
        if (idx === '') {
            // Tambah baru
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="surat-pengirim">${escapeHtml(pengirim)}</td>`);
            $tr.append(`<td class="surat-judulisi"><strong>${escapeHtml(judul)}</strong><br><small class="text-muted">${escapeHtml(isi)}</small></td>`);
            $tr.append(`<td class="surat-gdrive">${gdrive ? '<a href="'+escapeHtml(gdrive)+'" target="_blank" class="link-gdrive">Lihat di GDrive</a>' : '<span class="text-muted">-</span>'}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // Edit baris
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.surat-pengirim').text(pengirim);
            $tr.find('.surat-judulisi').html(`<strong>${escapeHtml(judul)}</strong><br><small class="text-muted">${escapeHtml(isi)}</small>`);
            $tr.find('.surat-gdrive').html(gdrive ? `<a href="${escapeHtml(gdrive)}" target="_blank" class="link-gdrive">Lihat di GDrive</a>` : '<span class="text-muted">-</span>');
        }

        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModal'));
        modal.hide();
    });

    // Event delegation: view
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewPengirim').text($tr.find('.surat-pengirim').text());
        // judul & isi
        const htmlJudulIsi = $tr.find('.surat-judulisi').html();
        $('#viewJudulIsi').html(htmlJudulIsi);
        $('#viewGDrive').html($tr.find('.surat-gdrive').html() || '<span class="text-muted">-</span>');

        const vmodal = new bootstrap.Modal(document.getElementById('viewModal'));
        vmodal.show();
    });

    // Event delegation: edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndex').val(idx);

        // ambil data
        $('#pengirim').val($tr.find('.surat-pengirim').text().trim());
        const judul = $tr.find('.surat-judulisi strong').text().trim();
        const isi = $tr.find('.surat-judulisi small').text().trim();
        $('#judul').val(judul);
        $('#isi').val(isi);

        const gdriveLink = $tr.find('.surat-gdrive a').attr('href') || '';
        $('#gdrive').val(gdriveLink);

        $('#formModalLabel').text('Edit Surat Masuk');
        const modal = new bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    });

    // Event delegation: delete
    $tbody.on('click', '.btn-delete', function () {
        if (!confirm('Hapus data ini?')) return;
        $(this).closest('tr').remove();

        // perbaiki nomor urut setelah hapus
        $tbody.find('tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    });

    // utility: escape html
    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
});
</script>
@endsection
