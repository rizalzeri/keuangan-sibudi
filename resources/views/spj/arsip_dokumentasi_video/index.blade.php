@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Video</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
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
                        @php
                            $videos = [
                                ['tanggal'=>'2025-01-11','kegiatan'=>'Upacara', 'gdrive'=>'https://drive.google.com/file/d/aaa'],
                                ['tanggal'=>'2025-01-13','kegiatan'=>'Rapat Koordinasi', 'gdrive'=>''],
                                ['tanggal'=>'2025-01-14','kegiatan'=>'Apel Pagi', 'gdrive'=>'https://drive.google.com/file/d/bbb'],
                            ];

                            function hariIndo($date) {
                                $hari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
                                return $hari[date('l', strtotime($date))];
                            }
                        @endphp

                        @foreach ($videos as $i => $v)
                            <tr data-index="{{ $i }}">
                                <td class="text-center">{{ $i + 1 }}</td>

                                <td class="video-tanggal">
                                    {{ hariIndo($v['tanggal']) }}, {{ date('d-m-Y', strtotime($v['tanggal'])) }}
                                </td>

                                <td class="video-kegiatan">{{ $v['kegiatan'] }}</td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning btn-edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>

                                <td class="video-gdrive d-none">{{ $v['gdrive'] }}</td>
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


<script>
$(function () {

    const $tbody = $('#tblVideo tbody');

    // Reset modal
    $('#formModal').on('hidden.bs.modal', function () {
        $('#videoForm')[0].reset();
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Dokumentasi Video');
    });

    // Submit form
    $('#videoForm').on('submit', function(e){
        e.preventDefault();

        const tanggal = $('#tanggal').val();
        const kegiatan = $('#kegiatan').val().trim();
        const gdrive = $('#gdrive').val().trim();

        if(!tanggal || !kegiatan){
            alert('Tanggal dan Kegiatan wajib diisi');
            return;
        }

        const hariMap = {
            'Sunday':'Minggu','Monday':'Senin','Tuesday':'Selasa',
            'Wednesday':'Rabu','Thursday':'Kamis',
            'Friday':'Jumat','Saturday':'Sabtu'
        };

        const dateObj = new Date(tanggal+"T00:00");
        const namaHari = hariMap[dateObj.toLocaleDateString('en-US',{weekday:'long'})];

        const idx = $('#rowIndex').val();

        if(idx === '') {
            // tambah
            const newIndex = $tbody.find('tr').length + 1;

            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="video-tanggal">${namaHari}, ${tanggal.split('-').reverse().join('-')}</td>`);
            $tr.append(`<td class="video-kegiatan">${kegiatan}</td>`);
            $tr.append(`<td class="text-center">
                            <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                        </td>`);
            $tr.append(`<td class="video-gdrive d-none">${gdrive}</td>`);
            $tbody.append($tr);

        } else {
            // edit
            const $tr = $tbody.find('tr').eq(parseInt(idx));
            $tr.find('.video-tanggal').text(`${namaHari}, ${tanggal.split('-').reverse().join('-')}`);
            $tr.find('.video-kegiatan').text(kegiatan);
            $tr.find('.video-gdrive').text(gdrive);
        }

        // close modal
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    });

    // View
    $tbody.on('click','.btn-view', function(){
        const $tr = $(this).closest('tr');

        $('#viewTanggal').text($tr.find('.video-tanggal').text());
        $('#viewKegiatan').text($tr.find('.video-kegiatan').text());

        new bootstrap.Modal(document.getElementById('viewModal')).show();
    });

    // Edit
    $tbody.on('click','.btn-edit', function(){
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndex').val(idx);

        const fullTanggal = $tr.find('.video-tanggal').text().split(',')[1].trim(); // format dd-mm-yyyy
        const tglISO = fullTanggal.split('-').reverse().join('-');

        $('#tanggal').val(tglISO);
        $('#kegiatan').val($tr.find('.video-kegiatan').text());
        $('#gdrive').val($tr.find('.video-gdrive').text());

        $('#formModalLabel').text('Edit Dokumentasi Video');

        new bootstrap.Modal(document.getElementById('formModal')).show();
    });

    // Delete
    $tbody.on('click','.btn-delete', function(){
        if(!confirm('Hapus data ini?')) return;
        $(this).closest('tr').remove();

        // perbaiki nomor urut
        $tbody.find('tr').each(function(i){
            $(this).find('td:first').text(i+1);
        });
    });
});
</script>

@endsection
