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
                        @php
                            $sops = [
                                ['nama'=>'SOP Penerimaan', 'nomor'=>'SOP-001/2025','ruang_lingkup'=>'Pelayanan Publik','status'=>'Berlaku','gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['nama'=>'SOP Pengadaan', 'nomor'=>'SOP-002/2025','ruang_lingkup'=>'Pengadaan Barang','status'=>'Tidak','gdrive'=>''],
                                ['nama'=>'SOP Kepegawaian', 'nomor'=>'SOP-003/2025','ruang_lingkup'=>'Internal','status'=>'Berlaku','gdrive'=>'https://drive.google.com/file/d/bbb'],
                            ];
                        @endphp

                        @foreach ($sops as $i => $s)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="sop-nama">{{ $s['nama'] }}</td>
                                <td class="sop-nomor">{{ $s['nomor'] }}</td>
                                <td class="sop-ruang">{{ $s['ruang_lingkup'] }}</td>
                                <td class="sop-status text-center">
                                    @if(strtolower($s['status']) == 'berlaku')
                                        <span class="badge bg-success">Berlaku</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>

                                {{-- simpan gdrive di cell tersembunyi agar bisa diedit, tapi tidak tampil di view --}}
                                <td class="sop-gdrive d-none">{{ $s['gdrive'] }}</td>

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

@include('spj.arsip_sop.components.modal_form')
@include('spj.arsip_sop.components.modal_view')

<script>
$(function () {
    const $tbody = $('#tblSop tbody');

    // Reset modal ketika ditutup
    $('#formModalSOP').on('hidden.bs.modal', function () {
        $('#sopForm')[0].reset();
        $('#rowIndexSop').val('');
        $('#formModalLabelSOP').text('Tambah SOP');
    });

    // Submit (Tambah / Edit) — client-side demo
    $('#sopForm').on('submit', function (e) {
        e.preventDefault();

        const nama = $('#sopNama').val().trim();
        const nomor = $('#sopNomor').val().trim() || '-';
        const ruang = $('#sopRuang').val().trim() || '-';
        const status = $('#sopStatus').val();
        const gdrive = $('#sopGdrive').val().trim();

        if (!nama) {
            alert('Nama SOP wajib diisi');
            return;
        }

        const idx = $('#rowIndexSop').val();
        const statusHtml = status === 'Berlaku' ? '<span class="badge bg-success">Berlaku</span>' : '<span class="badge bg-secondary">Tidak</span>';

        if (idx === '') {
            // Tambah baru
            const newIndex = $tbody.find('tr').length + 1;
            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="sop-nama">${escapeHtml(nama)}</td>`);
            $tr.append(`<td class="sop-nomor">${escapeHtml(nomor)}</td>`);
            $tr.append(`<td class="sop-ruang">${escapeHtml(ruang)}</td>`);
            $tr.append(`<td class="sop-status text-center">${statusHtml}</td>`);
            $tr.append(`<td class="sop-gdrive d-none">${escapeHtml(gdrive)}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tbody.append($tr);
        } else {
            // Edit baris
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.sop-nama').text(nama);
            $tr.find('.sop-nomor').text(nomor);
            $tr.find('.sop-ruang').text(ruang);
            $tr.find('.sop-status').html(statusHtml);
            $tr.find('.sop-gdrive').text(gdrive);
        }

        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModalSOP'));
        modal.hide();
    });

    // View
    $tbody.on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        $('#viewNamaSop').text($tr.find('.sop-nama').text());
        $('#viewNomorSop').text($tr.find('.sop-nomor').text());
        $('#viewRuangSop').text($tr.find('.sop-ruang').text());

        // status: tampilkan badge dari cell
        $('#viewStatusSop').html($tr.find('.sop-status').html());

        // NOTE: link GDrive tidak ditampilkan di view

        const vmodal = new bootstrap.Modal(document.getElementById('viewModalSOP'));
        vmodal.show();
    });

    // Edit
    $tbody.on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndexSop').val(idx);

        $('#sopNama').val($tr.find('.sop-nama').text().trim());
        $('#sopNomor').val($tr.find('.sop-nomor').text().trim());
        $('#sopRuang').val($tr.find('.sop-ruang').text().trim());

        // ambil status text dari badge
        const statusText = $tr.find('.sop-status .badge').text().trim();
        $('#sopStatus').val(statusText);

        // ambil gdrive dari cell tersembunyi
        const gdriveLink = $tr.find('.sop-gdrive').text().trim() || '';
        $('#sopGdrive').val(gdriveLink);

        $('#formModalLabelSOP').text('Edit SOP');
        const modal = new bootstrap.Modal(document.getElementById('formModalSOP'));
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
