@extends('layouts.spj.main')

@section('container')
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
                        @php
                            $surats = [
                                ['nomor'=>'SK-001/2025','tujuan'=>'Kecamatan','judul'=>'Pemberitahuan','isi'=>'Menindaklanjuti ...','gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['nomor'=>'SK-002/2025','tujuan'=>'Dinas Pendidikan','judul'=>'Permohonan Data','isi'=>'Mohon kiranya ...','gdrive'=>''],
                                ['nomor'=>'SK-003/2025','tujuan'=>'Sekolah','judul'=>'Koordinasi','isi'=>'Terlampir jadwal ...','gdrive'=>'https://drive.google.com/file/d/bbb'],
                            ];
                        @endphp

                        @foreach ($surats as $i => $s)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="sk-nomor">{{ $s['nomor'] }}</td>
                                <td class="sk-tujuan">{{ $s['tujuan'] }}</td>
                                <td class="sk-judul">{{ $s['judul'] }}</td>
                                <td class="sk-isi">{{ $s['isi'] }}</td>

                                {{-- simpan gdrive di cell tersembunyi agar tidak tampil di table/view tapi dapat di-edit --}}
                                <td class="sk-gdrive d-none">{{ $s['gdrive'] }}</td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
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

<script>
$(function () {
    const $tbody = $('#tblSuratKeluar tbody');

    // Reset modal ketika ditutup
    $('#formModal').on('hidden.bs.modal', function () {
        $('#suratKeluarForm')[0].reset();
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Surat Keluar');
    });

    // Submit (Tambah / Edit) - client-side demo
    $('#suratKeluarForm').on('submit', function (e) {
        e.preventDefault();

        const nomor = $('#nomorDokumen').val().trim();
        const tujuan = $('#tujuan').val().trim();
        const judul = $('#judul').val().trim();
        const isi = $('#isi').val().trim() || '-';
        const gdrive = $('#gdrive').val().trim();

        if (!nomor || !tujuan || !judul) {
            alert('Nomor Dokumen, Tujuan dan Judul wajib diisi');
            return;
        }

        const idx = $('#rowIndex').val();
        if (idx === '') {
            // Tambah baru
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="sk-nomor">${escapeHtml(nomor)}</td>`);
            $tr.append(`<td class="sk-tujuan">${escapeHtml(tujuan)}</td>`);
            $tr.append(`<td class="sk-judul">${escapeHtml(judul)}</td>`);
            $tr.append(`<td class="sk-isi">${escapeHtml(isi)}</td>`);
            // hidden gdrive cell
            $tr.append(`<td class="sk-gdrive d-none">${escapeHtml(gdrive)}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // Edit baris
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.sk-nomor').text(nomor);
            $tr.find('.sk-tujuan').text(tujuan);
            $tr.find('.sk-judul').text(judul);
            $tr.find('.sk-isi').text(isi);
            $tr.find('.sk-gdrive').text(gdrive);
        }

        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModal'));
        modal.hide();
    });

    // View
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewNomor').text($tr.find('.sk-nomor').text());
        $('#viewTujuan').text($tr.find('.sk-tujuan').text());
        $('#viewJudul').text($tr.find('.sk-judul').text());
        $('#viewIsi').text($tr.find('.sk-isi').text());
        // NOTE: kita *tidak menampilkan* gdrive pada view modal

        const vmodal = new bootstrap.Modal(document.getElementById('viewModal'));
        vmodal.show();
    });

    // Edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndex').val(idx);

        // isi form dari row
        $('#nomorDokumen').val($tr.find('.sk-nomor').text().trim());
        $('#tujuan').val($tr.find('.sk-tujuan').text().trim());
        $('#judul').val($tr.find('.sk-judul').text().trim());
        $('#isi').val($tr.find('.sk-isi').text().trim());
        // ambil gdrive dari cell tersembunyi
        const gdriveLink = $tr.find('.sk-gdrive').text().trim() || '';
        $('#gdrive').val(gdriveLink);

        $('#formModalLabel').text('Edit Surat Keluar');
        const modal = new bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    });

    // Delete
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
