@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Dokumentasi Foto</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formFotoModal" id="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah Dokumentasi
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblFoto" class="table datatable">
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
                            $items = [
                                ['tanggal'=>'Senin, 01-12-2025','kegiatan'=>'Rapat Koordinasi','gdrive'=>'https://drive.google.com/file/d/abc'],
                                ['tanggal'=>'Selasa, 02-12-2025','kegiatan'=>'Kunjungan Lapangan','gdrive'=>''],
                            ];
                        @endphp

                        @foreach ($items as $i => $f)
                        <tr data-index="{{ $i }}">
                            <td class="text-center">{{ $i+1 }}</td>
                            <td class="foto-tanggal">{{ $f['tanggal'] }}</td>
                            <td class="foto-kegiatan">{{ $f['kegiatan'] }}</td>

                            {{-- gdrive disimpan tapi tidak ditampilkan --}}
                            <td class="foto-gdrive d-none">{{ $f['gdrive'] }}</td>

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

@include('spj.arsip_dokumentasi_foto.components.modal_form')
@include('spj.arsip_dokumentasi_foto.components.modal_view')

{{-- ============================ --}}
{{-- SCRIPT CRUD CLIENT-SIDE      --}}
{{-- ============================ --}}
<script>

function getDayName(dateStr) {
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const d = new Date(dateStr);
    return days[d.getDay()];
}

function formatDateDMY(dateStr) {
    const d = new Date(dateStr);
    let day = String(d.getDate()).padStart(2,'0');
    let month = String(d.getMonth()+1).padStart(2,'0');
    let year = d.getFullYear();
    return `${day}-${month}-${year}`;
}

$(function () {
    const $tbody = $('#tblFoto tbody');

    $('#formFotoModal').on('hidden.bs.modal', function () {
        $('#fotoForm')[0].reset();
        $('#rowIndex').val('');
        $('#formFotoLabel').text('Tambah Dokumentasi');
    });

    $('#fotoForm').on('submit', function(e){
    e.preventDefault();

    const tanggalRaw = $('#tanggal').val().trim(); // YYYY-MM-DD
    const kegiatan = $('#kegiatan').val().trim();
    const gdrive = $('#gdrive').val().trim();

    if(!tanggalRaw || !kegiatan){
        alert('Tanggal dan Kegiatan wajib diisi.');
        return;
    }

    const hari = getDayName(tanggalRaw); // Senin, Selasa, ...
    const tglFormatted = hari + ", " + formatDateDMY(tanggalRaw);

    const idx = $('#rowIndex').val();

    if(idx === ''){
        const urut = $tbody.find('tr').length + 1;

        const $tr = $('<tr>');
        $tr.append(`<td class="text-center">${urut}</td>`);
        $tr.append(`<td class="foto-tanggal" data-date="${tanggalRaw}">${tglFormatted}</td>`);
        $tr.append(`<td class="foto-kegiatan">${escapeHtml(kegiatan)}</td>`);
        $tr.append(`<td class="foto-gdrive d-none">${escapeHtml(gdrive)}</td>`);
        $tr.append(`
            <td class="text-center">
                <button class="btn btn-sm btn-info btn-view"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
            </td>
        `);

        $tbody.append($tr);
    }
    else {
        const $tr = $tbody.find('tr').eq(parseInt(idx));

        $tr.find('.foto-tanggal')
            .text(tglFormatted)
            .attr('data-date', tanggalRaw);

        $tr.find('.foto-kegiatan').text(kegiatan);
        $tr.find('.foto-gdrive').text(gdrive);
    }

    bootstrap.Modal.getInstance(document.getElementById('formFotoModal')).hide();
    });


    // ========== EDIT ==========
    $tbody.on('click','.btn-edit',function(){
        const $tr = $(this).closest('tr');
        const idx = $tr.index();

        $('#rowIndex').val(idx);

        // ambil ulang tanggal asli dari data-date (YYYY-MM-DD)
        $('#tanggal').val($tr.find('.foto-tanggal').attr('data-date'));
        $('#kegiatan').val($tr.find('.foto-kegiatan').text());
        $('#gdrive').val($tr.find('.foto-gdrive').text());

        $('#formFotoLabel').text('Edit Dokumentasi');
        new bootstrap.Modal(document.getElementById('formFotoModal')).show();
    });


    // DELETE
    $tbody.on('click','.btn-delete',function(){
        if(!confirm('Hapus data ini?')) return;

        $(this).closest('tr').remove();

        $tbody.find('tr').each(function(i){
            $(this).find('td:first').text(i+1);
        });
    });

    function escapeHtml(unsafe){
        return unsafe
            .replace(/&/g,"&amp;")
            .replace(/</g,"&lt;")
            .replace(/>/g,"&gt;")
            .replace(/"/g,"&quot;")
            .replace(/'/g,"&#039;");
    }
});
</script>

@endsection
