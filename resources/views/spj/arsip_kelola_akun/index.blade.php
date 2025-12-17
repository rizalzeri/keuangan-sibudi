@extends('layouts.spj.main') {{-- sesuaikan layout --}}

@section('container')
<div class="container py-4">
    <h3>Kelola Akun</h3>
    <p class="text-muted">Lembaga Bumdes</p>

    <div class="row g-3">
        {{-- Personalisasi --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Personalisasi</strong>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalPersonalisasi" data-action="create">Tambah</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>#</th><th>Nama</th><th>Jabatan</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="table-personalisasi">
                            @foreach($personalisasi as $p)
                            <tr data-id="{{ $p->id }}">
                                <td>{{ $p->id }}</td>
                                <td class="nama">{{ $p->nama }}</td>
                                <td class="jabatan">{{ $p->jabatan }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-personalisasi" data-id="{{ $p->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-personalisasi" data-id="{{ $p->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if($personalisasi->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Otorisasi Mengetahui --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Otorisasi Mengetahui</strong>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalMengetahui" data-action="create">Tambah</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>#</th><th>Kategori</th><th>Personalisasi</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="table-mengetahui">
                            @foreach($mengetahui as $m)
                            <tr data-id="{{ $m->id }}">
                                <td>{{ $m->id }}</td>
                                <td class="kategori">{{ $m->kategori }}</td>
                                <td class="personalisasi_id">{{ optional($m->personalisasi)->nama }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-mengetahui" data-id="{{ $m->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-mengetahui" data-id="{{ $m->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if($mengetahui->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Otorisasi Persetujuan --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Otorisasi Persetujuan</strong>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalPersetujuan" data-action="create">Tambah</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>#</th><th>Kategori</th><th>Personalisasi</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="table-persetujuan">
                            @foreach($persetujuan as $p)
                            <tr data-id="{{ $p->id }}">
                                <td>{{ $p->id }}</td>
                                <td class="kategori">{{ $p->kategori }}</td>
                                <td class="personalisasi_id">{{ optional($p->personalisasi)->nama }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-persetujuan" data-id="{{ $p->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-persetujuan" data-id="{{ $p->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if($persetujuan->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Klasifikasi Transaksi --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Klasifikasi Transaksi</strong>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalKlasifikasi" data-action="create">Tambah</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="table-klasifikasi">
                            @foreach($klasifikasi as $k)
                            <tr data-id="{{ $k->id }}">
                                <td>{{ $k->id }}</td>
                                <td class="kategori">{{ $k->kategori }}</td>
                                <td class="nominal">{{ number_format($k->nominal,2) }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-klasifikasi" data-id="{{ $k->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-klasifikasi" data-id="{{ $k->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if($klasifikasi->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals (Personalisasi, Mengetahui, Persetujuan, Klasifikasi) --}}
@include('spj.arsip_kelola_akun.partials.modals')

<script>
    $(function () {
        // setup csrf
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // --- Personalisasi ---
        // prepare add modal (reset)
        $('button[data-bs-target="#modalPersonalisasi"]').on('click', function () {
            $('#personalisasi-id').val('');
            $('#personalisasi-nama').val('');
            $('#personalisasi-jabatan').val('');
            $('#personalisasi-submit').data('action','create').text('Simpan');
        });

        // edit button
        $('#table-personalisasi').on('click', '.btn-edit-personalisasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-personalisasi tr[data-id="${id}"]`);
            const nama = $tr.find('.nama').text().trim();
            const jabatan = $tr.find('.jabatan').text().trim();

            $('#personalisasi-id').val(id);
            $('#personalisasi-nama').val(nama);
            $('#personalisasi-jabatan').val(jabatan);
            $('#personalisasi-submit').data('action','edit').text('Update');

            // show modal
            new bootstrap.Modal(document.getElementById('modalPersonalisasi')).show();
        });

        // delete
        $('#table-personalisasi').on('click', '.btn-delete-personalisasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-personalisasi tr[data-id="${id}"]`);
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data personalisasi akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: `/spj/arsip_kelola_akun/personalisasi/${id}`,
                    method: 'DELETE',
                    success(res) {
                        $tr.remove();
                        Swal.fire({ icon: 'success', title: 'Terhapus', timer: 900, showConfirmButton: false })
                            .then(()=> location.reload());
                    },
                    error(xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });
        });

        // submit personalisasi form
        $('#personalisasi-form').on('submit', function (e) {
            e.preventDefault();
            const action = $('#personalisasi-submit').data('action') || 'create';
            const id = $('#personalisasi-id').val();
            const payload = {
                nama: $('#personalisasi-nama').val().trim(),
                jabatan: $('#personalisasi-jabatan').val().trim()
            };
            if (!payload.nama) { Swal.fire('Oops','Nama wajib diisi','error'); return; }

            const url = action === 'create' ? '/spj/arsip_kelola_akun/personalisasi' : `/spj/arsip_kelola_akun/personalisasi/${id}`;
            const method = action === 'create' ? 'POST' : 'PUT';

            $.ajax({
                url, method, data: payload,
                success(res) {
                    Swal.fire({ icon: 'success', title: 'Sukses', text: action === 'create' ? 'Data ditambahkan' : 'Data diperbarui', timer: 900, showConfirmButton: false })
                        .then(()=> location.reload());
                },
                error(xhr) {
                    if (xhr.status === 422) {
                        const msgs = Object.values(xhr.responseJSON.errors || {}).flat().join('<br>');
                        Swal.fire({ icon: 'error', title: 'Validasi', html: msgs });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                    }
                }
            });
        });

        // --- Mengetahui ---
        $('button[data-bs-target="#modalMengetahui"]').on('click', function () {
            $('#mengetahui-id').val('');
            $('#mengetahui-kategori').val('');
            $('#mengetahui-personalisasi_id').val('');
            $('#mengetahui-submit').data('action','create').text('Simpan');
        });

        $('#table-mengetahui').on('click', '.btn-edit-mengetahui', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-mengetahui tr[data-id="${id}"]`);
            const kategori = $tr.find('.kategori').text().trim();
            const namaPersonalisasi = $tr.find('.personalisasi_id').text().trim();

            // select by text
            $("#mengetahui-personalisasi_id option").each(function () {
                $(this).prop('selected', $(this).text().trim() === namaPersonalisasi);
            });

            $('#mengetahui-id').val(id);
            $('#mengetahui-kategori').val(kategori);
            $('#mengetahui-submit').data('action','edit').text('Update');
            new bootstrap.Modal(document.getElementById('modalMengetahui')).show();
        });

        $('#table-mengetahui').on('click', '.btn-delete-mengetahui', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-mengetahui tr[data-id="${id}"]`);
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data mengetahui akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            }).then((res) => {
                if (!res.isConfirmed) return;
                $.ajax({
                    url: `/spj/arsip_kelola_akun/mengetahui/${id}`, method: 'DELETE',
                    success() { $tr.remove(); Swal.fire({ icon:'success', title:'Terhapus', timer:800, showConfirmButton:false }).then(()=> location.reload()); },
                    error(xhr){ Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus', 'error'); }
                });
            });
        });

        $('#mengetahui-form').on('submit', function (e) {
            e.preventDefault();
            const action = $('#mengetahui-submit').data('action') || 'create';
            const id = $('#mengetahui-id').val();
            const payload = {
                kategori: $('#mengetahui-kategori').val().trim(),
                arsip_personalisasi_id: $('#mengetahui-personalisasi_id').val()
            };
            if (!payload.arsip_personalisasi_id) { Swal.fire('Oops','Pilih personalisasi','error'); return; }
            const url = action === 'create' ? '/spj/arsip_kelola_akun/mengetahui' : `/spj/arsip_kelola_akun/mengetahui/${id}`;
            const method = action === 'create' ? 'POST' : 'PUT';

            $.ajax({
                url, method, data: payload,
                success() { Swal.fire({ icon:'success', title:'Sukses', timer:900, showConfirmButton:false }).then(()=> location.reload()); },
                error(xhr){ if(xhr.status===422){ Swal.fire({icon:'error', html:Object.values(xhr.responseJSON.errors||{}).flat().join('<br>')}); } else Swal.fire('Error', 'Gagal menyimpan','error'); }
            });
        });

        // --- Persetujuan ---
        $('button[data-bs-target="#modalPersetujuan"]').on('click', function () {
            $('#persetujuan-id').val('');
            $('#persetujuan-kategori').val('');
            $('#persetujuan-personalisasi_id').val('');
            $('#persetujuan-submit').data('action','create').text('Simpan');
        });

        $('#table-persetujuan').on('click', '.btn-edit-persetujuan', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-persetujuan tr[data-id="${id}"]`);
            const kategori = $tr.find('.kategori').text().trim();
            const namaPersonalisasi = $tr.find('.personalisasi_id').text().trim();
            $("#persetujuan-personalisasi_id option").each(function () {
                $(this).prop('selected', $(this).text().trim() === namaPersonalisasi);
            });
            $('#persetujuan-id').val(id);
            $('#persetujuan-kategori').val(kategori);
            $('#persetujuan-submit').data('action','edit').text('Update');
            new bootstrap.Modal(document.getElementById('modalPersetujuan')).show();
        });

        $('#table-persetujuan').on('click', '.btn-delete-persetujuan', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-persetujuan tr[data-id="${id}"]`);
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data persetujuan akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            }).then((res) => {
                if (!res.isConfirmed) return;
                $.ajax({
                    url: `/spj/arsip_kelola_akun/persetujuan/${id}`, method: 'DELETE',
                    success() { $tr.remove(); Swal.fire({ icon:'success', title:'Terhapus', timer:800, showConfirmButton:false }).then(()=> location.reload()); },
                    error(xhr){ Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus', 'error'); }
                });
            });
        });

        $('#persetujuan-form').on('submit', function (e) {
            e.preventDefault();
            const action = $('#persetujuan-submit').data('action') || 'create';
            const id = $('#persetujuan-id').val();
            const payload = {
                kategori: $('#persetujuan-kategori').val().trim(),
                arsip_personalisasi_id: $('#persetujuan-personalisasi_id').val()
            };
            if (!payload.arsip_personalisasi_id) { Swal.fire('Oops','Pilih personalisasi','error'); return; }
            const url = action === 'create' ? '/spj/arsip_kelola_akun/persetujuan' : `/spj/arsip_kelola_akun/persetujuan/${id}`;
            const method = action === 'create' ? 'POST' : 'PUT';
            $.ajax({
                url, method, data: payload,
                success() { Swal.fire({ icon:'success', title:'Sukses', timer:900, showConfirmButton:false }).then(()=> location.reload()); },
                error(xhr){ if(xhr.status===422){ Swal.fire({icon:'error', html:Object.values(xhr.responseJSON.errors||{}).flat().join('<br>')}); } else Swal.fire('Error','Gagal menyimpan','error'); }
            });
        });

        // --- Klasifikasi ---
        $('button[data-bs-target="#modalKlasifikasi"]').on('click', function () {
            $('#klasifikasi-id').val('');
            $('#klasifikasi-kategori').val('');
            $('#klasifikasi-nominal').val('');
            $('#klasifikasi-submit').data('action','create').text('Simpan');
        });

        $('#table-klasifikasi').on('click', '.btn-edit-klasifikasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-klasifikasi tr[data-id="${id}"]`);
            const kategori = $tr.find('.kategori').text().trim();
            const nominal = $tr.find('.nominal').text().trim().replace(/,/g,'');
            $('#klasifikasi-id').val(id);
            $('#klasifikasi-kategori').val(kategori);
            $('#klasifikasi-nominal').val(nominal);
            $('#klasifikasi-submit').data('action','edit').text('Update');
            new bootstrap.Modal(document.getElementById('modalKlasifikasi')).show();
        });

        $('#table-klasifikasi').on('click', '.btn-delete-klasifikasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-klasifikasi tr[data-id="${id}"]`);
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data klasifikasi akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            }).then((res) => {
                if (!res.isConfirmed) return;
                $.ajax({
                    url: `/spj/arsip_kelola_akun/klasifikasi/${id}`, method: 'DELETE',
                    success() { $tr.remove(); Swal.fire({ icon:'success', title:'Terhapus', timer:800, showConfirmButton:false }).then(()=> location.reload()); },
                    error(xhr){ Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus', 'error'); }
                });
            });
        });

        $('#klasifikasi-form').on('submit', function (e) {
            e.preventDefault();
            const action = $('#klasifikasi-submit').data('action') || 'create';
            const id = $('#klasifikasi-id').val();
            const payload = {
                kategori: $('#klasifikasi-kategori').val().trim(),
                nominal: $('#klasifikasi-nominal').val()
            };
            if (!payload.nominal) { Swal.fire('Oops','Nominal wajib diisi','error'); return; }
            const url = action === 'create' ? '/spj/arsip_kelola_akun/klasifikasi' : `/spj/arsip_kelola_akun/klasifikasi/${id}`;
            const method = action === 'create' ? 'POST' : 'PUT';
            $.ajax({
                url, method, data: payload,
                success() { Swal.fire({ icon:'success', title:'Sukses', timer:900, showConfirmButton:false }).then(()=> location.reload()); },
                error(xhr){ if(xhr.status===422){ Swal.fire({icon:'error', html:Object.values(xhr.responseJSON.errors||{}).flat().join('<br>')}); } else Swal.fire('Error','Gagal menyimpan','error'); }
            });
        });

    }); // end jquery ready
</script>
@endsection
