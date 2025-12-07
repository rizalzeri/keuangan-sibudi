@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Berkas</h4>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
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
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $berkas = [
                                ['tahun'=>2023,'nama'=>'Dokumen Kegiatan A','gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['tahun'=>2024,'nama'=>'Dokumen Laporan Tahunan','gdrive'=>''],
                                ['tahun'=>2022,'nama'=>'Dokumen Penting B','gdrive'=>'https://drive.google.com/file/d/bbb'],
                            ];
                        @endphp

                        @foreach ($berkas as $i => $b)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="berkas-tahun">{{ $b['tahun'] }}</td>
                                <td class="berkas-nama">{{ $b['nama'] }}</td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                                </td>

                                <td class="berkas-gdrive d-none">{{ $b['gdrive'] }}</td>
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

<script>
$(function () {

    const $tbody = $('#tblBerkas tbody');

    function loadYears() {
        const yearSelect = $("#tahun");
        const current = new Date().getFullYear();
        yearSelect.empty();
        for (let y = current; y >= 2020; y--) {
            yearSelect.append(`<option value="${y}">${y}</option>`);
        }
    }

    loadYears();

    $('#formModal').on('hidden.bs.modal', function () {
        $('#berkasForm')[0].reset();
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Dokumentasi Berkas');
        loadYears();
    });

    // Submit Add/Edit
    $('#berkasForm').on('submit', function(e){
        e.preventDefault();

        const tahun = $('#tahun').val();
        const nama = $('#nama').val().trim();
        const gdrive = $('#gdrive').val().trim();

        if(!tahun || !nama){
            alert('Tahun dan Nama Dokumen wajib diisi');
            return;
        }

        const idx = $('#rowIndex').val();

        if(idx === '') {
            // tambah
            const newIndex = $tbody.find('tr').length + 1;

            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="berkas-tahun">${tahun}</td>`);
            $tr.append(`<td class="berkas-nama">${nama}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tr.append(`<td class="berkas-gdrive d-none">${gdrive}</td>`);
            $tbody.append($tr);

        } else {
            // edit
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.berkas-tahun').text(tahun);
            $tr.find('.berkas-nama').text(nama);
            $tr.find('.berkas-gdrive').text(gdrive);
        }

        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    });

    // View
    $tbody.on('click','.btn-view', function(){
        const $tr = $(this).closest('tr');
        $('#viewTahun').text($tr.find('.berkas-tahun').text());
        $('#viewNama').text($tr.find('.berkas-nama').text());

        new bootstrap.Modal(document.getElementById('viewModal')).show();
    });

    // Edit
    $tbody.on('click','.btn-edit', function(){
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndex').val(idx);

        $('#tahun').val($tr.find('.berkas-tahun').text());
        $('#nama').val($tr.find('.berkas-nama').text());
        $('#gdrive').val($tr.find('.berkas-gdrive').text());

        $('#formModalLabel').text('Edit Dokumentasi Berkas');

        new bootstrap.Modal(document.getElementById('formModal')).show();
    });

    // Delete
    $tbody.on('click','.btn-delete', function(){
        if(!confirm('Hapus data ini?')) return;

        $(this).closest('tr').remove();

        $tbody.find('tr').each(function(i){
            $(this).find('td:first').text(i+1);
        });
    });
});
</script>

@endsection
