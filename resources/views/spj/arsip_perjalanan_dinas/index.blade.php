@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Perjalanan Dinas</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblPD" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Tempat</th>
                            <th>Transport</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $data = [
                                ['nomor'=>'PD-001','tanggal'=>'2025-01-01','kegiatan'=>'Survei Lapangan','tempat'=>'Bandung','transport'=>'Mobil','gdrive'=>'https://drive.google.com/XX'],
                                ['nomor'=>'PD-002','tanggal'=>'2025-01-02','kegiatan'=>'Meeting Proyek','tempat'=>'Jakarta','transport'=>'Kereta','gdrive'=>''],
                            ];
                        @endphp

                        @foreach ($data as $i => $d)
                        <tr data-index="{{ $i }}">
                            <td class="text-center">{{ $i+1 }}</td>
                            <td class="pd-nomor">{{ $d['nomor'] }}</td>
                            <td class="pd-tanggal">{{ $d['tanggal'] }}</td>
                            <td class="pd-kegiatan">{{ $d['kegiatan'] }}</td>
                            <td class="pd-tempat">{{ $d['tempat'] }}</td>
                            <td class="pd-transport">{{ $d['transport'] }}</td>
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

@include('spj.arsip_perjalanan_dinas.components.modal_form')
@include('spj.arsip_perjalanan_dinas.components.modal_view')

<script>
$(function(){

    const $tbody = $('#tblPD tbody');

    $('#formModal').on('hidden.bs.modal', function(){
        $('#pdForm')[0].reset();
        $('#rowIndex').val('');
        $('#formModalLabel').text('Tambah Perjalanan Dinas');
        $('#tanggal').val(new Date().toISOString().split('T')[0]); // default hari ini
    });

    $('#pdForm').on('submit',function(e){
        e.preventDefault();

        const nomor = $('#nomor').val().trim();
        const tanggal = $('#tanggal').val();
        const kegiatan = $('#kegiatan').val().trim();
        const tempat = $('#tempat').val().trim();
        const transport = $('#transport').val().trim();
        const gdrive = $('#gdrive').val().trim();

        if(!nomor || !kegiatan){
            alert('Nomor Dokumen dan Kegiatan wajib diisi');
            return;
        }

        const idx = $('#rowIndex').val();

        if(idx === ''){
            const newIndex = $tbody.find('tr').length + 1;

            const $tr = $('<tr>');
            $tr.append(`<td class="text-center">${newIndex}</td>`);
            $tr.append(`<td class="pd-nomor">${nomor}</td>`);
            $tr.append(`<td class="pd-tanggal">${tanggal}</td>`);
            $tr.append(`<td class="pd-kegiatan">${kegiatan}</td>`);
            $tr.append(`<td class="pd-tempat">${tempat}</td>`);
            $tr.append(`<td class="pd-transport">${transport}</td>`);
            $tr.append(`
                <td class="text-center">
                    <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                </td>`);

            $tr.data('gdrive', gdrive);
            $tbody.append($tr);
        } else {
            const $tr = $tbody.find('tr').eq(parseInt(idx));

            $tr.find('.pd-nomor').text(nomor);
            $tr.find('.pd-tanggal').text(tanggal);
            $tr.find('.pd-kegiatan').text(kegiatan);
            $tr.find('.pd-tempat').text(tempat);
            $tr.find('.pd-transport').text(transport);
            $tr.data('gdrive', gdrive);
        }

        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    });

    $tbody.on('click','.btn-view',function(){
        const $tr = $(this).closest('tr');

        $('#vNomor').text($tr.find('.pd-nomor').text());
        $('#vTanggal').text($tr.find('.pd-tanggal').text());
        $('#vKegiatan').text($tr.find('.pd-kegiatan').text());
        $('#vTempat').text($tr.find('.pd-tempat').text());
        $('#vTransport').text($tr.find('.pd-transport').text());
        // tidak menampilkan GDrive (sesuai permintaan)

        new bootstrap.Modal(document.getElementById('viewModal')).show();
    });

    $tbody.on('click','.btn-edit',function(){
        const $tr = $(this).closest('tr');
        const idx = $tr.index();
        $('#rowIndex').val(idx);

        $('#formModalLabel').text('Edit Perjalanan Dinas');

        $('#nomor').val($tr.find('.pd-nomor').text());
        $('#tanggal').val($tr.find('.pd-tanggal').text());
        $('#kegiatan').val($tr.find('.pd-kegiatan').text());
        $('#tempat').val($tr.find('.pd-tempat').text());
        $('#transport').val($tr.find('.pd-transport').text());

        $('#gdrive').val($tr.data('gdrive') || '');

        new bootstrap.Modal(document.getElementById('formModal')).show();
    });

    $tbody.on('click','.btn-delete',function(){
        if(!confirm('Hapus data ini?')) return;

        $(this).closest('tr').remove();

        $tbody.find('tr').each(function(i){
            $(this).find('td:first').text(i+1);
        });
    });

});
</script>

@endsection
