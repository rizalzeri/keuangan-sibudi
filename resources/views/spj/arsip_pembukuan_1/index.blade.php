@extends('layouts.spj.main')

@section('container')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip SPJ Pembukuan 1</h4>
    </div>

    <div class="card mb-3">
        <br>
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label">Tahun Anggaran :</label>
                </div>
                <div class="col-auto">
                    <select id="filterYear" class="form-select">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto ms-4">
                    <label class="form-label">Filter :</label>
                </div>
                <div class="col-auto">
                    <select id="filterType" class="form-select">
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ $t == ($selectedType ?? 'Semua') ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <br>
        <div class="card-body">

            <div class="table-responsive">
                <table id="arsipTable" class="table datatable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Transaksi</th>
                            <th style="width:150px">Nomor Dokumen</th>
                            <th style="width:200px">Jenis SPJ</th>
                            <th>Bukti Dukung</th>
                            <th style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $r)
                            <tr data-jenis="{{ $r['jenis'] }}">
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $r['transaksi'] }}</td>
                                <td>{{ $r['nomor'] }}</td>
                                <td>{{ $r['jenis'] }}</td>
                                <td>{{ $r['bukti'] }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view" title="Lihat" data-id="{{ $r['id'] }}"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" title="Edit" data-id="{{ $r['id'] }}"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" title="Hapus" data-id="{{ $r['id'] }}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button id="btnRekapBottom" class="btn btn-primary">Cetak Rekap</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="modalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalViewLabel">Detail Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
              <tbody>
                  <tr><th>Transaksi</th><td id="viewTransaksi"></td></tr>
                  <tr><th>Nomor Dokumen</th><td id="viewNomor"></td></tr>
                  <tr><th>Jenis SPJ</th><td id="viewJenis"></td></tr>
                  <tr><th>Bukti Dukung</th><td id="viewBukti"></td></tr>
              </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>
<!-- DataTables & jQuery (CDN), asumsi layout belum memuat mereka -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // inisialisasi DataTable


    // ketika filter berubah -> reload page server-side dengan query params (untuk nomor urut per tahun yang benar)
    $('#filterType, #filterYear').on('change', function () {
        const qYear = $('#filterYear').val();
        const qType = $('#filterType').val();
        const url = new URL(window.location.href);
        url.searchParams.set('year', qYear);
        url.searchParams.set('type', qType);
        window.location.href = url.toString();
    });

    // action buttons (placeholder — Anda bisa sambungkan ke route edit/delete sesuai jenis)
    $('#arsipTable tbody').on('click', '.btn-view', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');

        const transaksi = $tr.find('td').eq(1).text();
        const nomor = $tr.find('td').eq(2).text();
        const jenis = $tr.find('td').eq(3).text();
        const bukti = $tr.find('td').eq(4).text();

        $('#viewTransaksi').text(transaksi);
        $('#viewNomor').text(nomor);
        $('#viewJenis').text(jenis);
        $('#viewBukti').text(bukti);
        $('#modalView').modal('show');
    });

    $('#arsipTable tbody').on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        alert('Edit: ' + $tr.find('td').eq(1).text() + '\nID: ' + id);
    });

    $('#arsipTable tbody').on('click', '.btn-delete', function () {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');
        const jenis = $tr.find('td').eq(3).text();

        Swal.fire({
            title: 'Hapus Data?',
            text: "Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // panggil route delete via AJAX
                $.ajax({
                    url: "{{ url('/spj/arsip_pembukuan_1/delete') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        jenis: jenis
                    },
                    success: function(res) {
                        if(res.success){
                            Swal.fire({
                                title: 'Terhapus!',
                                text: res.message,
                                icon: 'success',
                                timer: 3000,       // reload otomatis setelah 3 detik
                                timerProgressBar: true,
                                showConfirmButton: true
                            }).then((result) => {
                                // reload saat user klik OK
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan server', 'error');
                    }
                });
            }
        });
    });

    // Cetak Rekap (buka tab baru — implement sesuai route rekap di server)
    $('#btnRekap, #btnRekapBottom').on('click', function () {
        const year = $('#filterYear').val();
        const type = $('#filterType').val();
        const url = "{{ url('/spj/arsip_pembukuan_1/rekap') }}?year=" + encodeURIComponent(year) + "&type=" + encodeURIComponent(type);
        window.open(url, '_blank');
    });
});
</script>
@endsection
