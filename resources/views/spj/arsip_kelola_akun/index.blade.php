@extends('layouts.spj.main')

@section('container')
<div class="container py-4">
    <h3>Kelola Akun</h3>
    <p class="text-muted">Lembaga Bumdes</p>
        <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
            <div class="card-body mt-4">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                {{-- Avatar / badge --}}
                <div class="d-flex align-items-center" style="min-width:150px;">
                    
                    <div class="d-none d-md-block">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <div class="text-muted small d-flex align-items-center">
                        <span id="email-text">{{ $user->email }}</span>
                        <button id="btn-copy-email" class="btn btn-link btn-sm p-0 ms-2" title="Salin email">
                        <i class="bi bi-clipboard"></i>
                        </button>
                    </div>

                    <div class="mt-2">
                        @php
                        $complete = !empty($user->nama_bumdes) && !empty($user->alamat_bumdes) && !empty($user->nomor_hukum_bumdes);
                        @endphp

                        <span class="badge {{ $complete ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $complete ? 'Profil BUMDes Lengkap' : 'Profil BUMDes Belum Lengkap' }}
                        </span>
                    </div>
                    </div>
                </div>

                {{-- Info detail (expandable) --}}
                <div class="flex-fill">
                    <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <div class="fw-semibold">Nama BUMDes</div>
                        <div class="text-truncate">{{ $user->nama_bumdes ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="fw-semibold">Nomor Hukum</div>
                        <div class="text-truncate">{{ $user->nomor_hukum_bumdes ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="fw-semibold">Alamat BUMDes</div>
                        <div class="text-muted small text-truncate" style="max-width:100%;">{{ $user->alamat_bumdes ?? '-' }}</div>
                    </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="text-end">
                    <button id="btn-edit-akun" class="btn btn-primary mb-2">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil BUMDes
                    </button>

                    {{-- tombol kecil untuk lengkapi segera jika belum lengkap --}}
                    @unless($complete)
                    <div>
                        <button class="btn btn-sm btn-outline-primary" id="btn-lengkapi-sekarang" type="button">
                        <i class="bi bi-exclamation-circle me-1"></i> Lengkapi Sekarang
                        </button>
                    </div>
                    @endunless
                </div>
                </div>

                {{-- Warning / hint --}}
                @if(!$complete)
                <div class="mt-3">
                    <div class="alert alert-warning mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <div class="small mb-0">
                        Informasi BUMDes belum lengkap. Lengkapi <strong>Nama</strong>, <strong>Alamat</strong>, dan <strong>Nomor Hukum</strong> supaya dokumen & laporan lebih rapi.
                    </div>
                    </div>
                </div>
                @else
                <div class="mt-3">
                    <div class="alert alert-success mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle"></i>
                    <div class="small mb-0">
                        Profil BUMDes sudah lengkap — terima kasih!
                    </div>
                    </div>
                </div>
                @endif
            </div>
            </div>
        </div>
        </div>
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
                                <td>{{ $loop->iteration }}</td>
                                <td class="nama">{{ $p->nama }}</td>
                                <td class="jabatan">{{ $p->jabatan }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-personalisasi" data-id="{{ $p->id }}"><i class="bi bi-pencil"></i></button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-personalisasi" data-id="{{ $p->id }}"><i class="bi bi-trash"></i></button>
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
                            <tr>
                                <th>#</th>
                                <th>Kategori</th>
                                <th>Nominal</th>
                                <th>Mengetahui</th>
                                <th>Persetujuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-klasifikasi">
                            @foreach($klasifikasi as $k)
                            <tr data-id="{{ $k->id }}"
                                data-mengetahui-personalisasi-id="{{ optional($k->mengetahui)->arsip_personalisasi_id ?? '' }}"
                                data-persetujuan-personalisasi-id="{{ optional($k->persetujuan)->arsip_personalisasi_id ?? '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="kategori">{{ $k->kategori }}</td>
                                <td class="nominal">{{ formatRupiah($k->nominal) }}</td>
                                <td class="mengetahui_name">{{ optional(optional($k->mengetahui)->personalisasi)->nama ?? '-' }}</td>
                                <td class="persetujuan_name">{{ optional(optional($k->persetujuan)->personalisasi)->nama ?? '-' }}</td>
                                <td>
                                    <button title="Edit" class="btn btn-sm btn-warning btn-edit-klasifikasi" data-id="{{ $k->id }}"><i class="bi bi-pencil"></i></button>
                                    <button title="Hapus" class="btn btn-sm btn-danger btn-delete-klasifikasi" data-id="{{ $k->id }}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @if($klasifikasi->isEmpty())
                            <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@include('spj.arsip_kelola_akun.partials.modals')

<script>
    $(function () {
        // setup csrf
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // --- Utility: format rupiah (JS) ---
        function formatRupiahJS(angka) {
            if (angka === null || angka === undefined || angka === '') return '';
            // ensure numeric string
            const s = String(angka).replace(/[^\d]/g,'');
            if (!s) return '';
            let x = s.split('').reverse().join('').match(/\d{1,3}/g);
            return 'Rp' + x.join('.').split('').reverse().join('');
        }

        function parseToRawNumber(str) {
            if (!str) return '';
            return String(str).replace(/[^\d]/g,'');
        }

        // --- Personalisasi (sama seperti sebelumnya) ---
        $('button[data-bs-target="#modalPersonalisasi"]').on('click', function () {
            $('#personalisasi-id').val('');
            $('#personalisasi-nama').val('');
            $('#personalisasi-jabatan').val('');
            $('#personalisasi-submit').data('action','create').text('Simpan');
        });

        $('#table-personalisasi').on('click', '.btn-edit-personalisasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-personalisasi tr[data-id="${id}"]`);
            const nama = $tr.find('.nama').text().trim();
            const jabatan = $tr.find('.jabatan').text().trim();

            $('#personalisasi-id').val(id);
            $('#personalisasi-nama').val(nama);
            $('#personalisasi-jabatan').val(jabatan);
            $('#personalisasi-submit').data('action','edit').text('Update');

            new bootstrap.Modal(document.getElementById('modalPersonalisasi')).show();
        });

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
                    success() {
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

        // --- Klasifikasi: gunakan personalisasi list untuk dropdown ---
        $('button[data-bs-target="#modalKlasifikasi"]').on('click', function () {
            $('#klasifikasi-id').val('');
            $('#klasifikasi-kategori').val('');
            $('#klasifikasi-nominal').val('');
            $('#klasifikasi-nominal-display').val('');
            $('#klasifikasi-mengetahui_personalisasi_id').val('');
            $('#klasifikasi-persetujuan_personalisasi_id').val('');
            $('#klasifikasi-submit').data('action','create').text('Simpan');
        });

        // when user types in display input, update hidden raw input
        $('#klasifikasi-nominal-display').on('input', function () {
            const raw = parseToRawNumber($(this).val());
            $('#klasifikasi-nominal').val(raw);
            // format and set display (keep caret simple)
            $(this).val(formatRupiahJS(raw));
        });

        $('#table-klasifikasi').on('click', '.btn-edit-klasifikasi', function () {
            const id = $(this).data('id');
            const $tr = $(`#table-klasifikasi tr[data-id="${id}"]`);
            const kategori = $tr.find('.kategori').text().trim();
            // parse nominal from table cell (table has "Rp..." text)
            const nominalText = $tr.find('.nominal').text().trim();
            const nominalRaw = parseToRawNumber(nominalText); // digits only
            const mengetahuiPid = $tr.data('mengetahui-personalisasi-id') || '';
            const persetujuanPid = $tr.data('persetujuan-personalisasi-id') || '';

            $('#klasifikasi-id').val(id);
            $('#klasifikasi-kategori').val(kategori);
            $('#klasifikasi-nominal').val(nominalRaw);
            $('#klasifikasi-nominal-display').val(formatRupiahJS(nominalRaw));
            $('#klasifikasi-mengetahui_personalisasi_id').val(mengetahuiPid);
            $('#klasifikasi-persetujuan_personalisasi_id').val(persetujuanPid);
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
                // take raw value (digits only) and send number
                nominal: $('#klasifikasi-nominal').val() ? parseInt($('#klasifikasi-nominal').val(), 10) : null,
                mengetahui_personalisasi_id: $('#klasifikasi-mengetahui_personalisasi_id').val() || null,
                persetujuan_personalisasi_id: $('#klasifikasi-persetujuan_personalisasi_id').val() || null
            };
            if (!payload.nominal && payload.nominal !== 0) { Swal.fire('Oops','Nominal wajib diisi','error'); return; }
            const url = action === 'create' ? '/spj/arsip_kelola_akun/klasifikasi' : `/spj/arsip_kelola_akun/klasifikasi/${id}`;
            const method = action === 'create' ? 'POST' : 'PUT';
            $.ajax({
                url, method, data: payload,
                success() { Swal.fire({ icon:'success', title:'Sukses', timer:900, showConfirmButton:false }).then(()=> location.reload()); },
                error(xhr){ if(xhr.status===422){ Swal.fire({icon:'error', html:Object.values(xhr.responseJSON.errors||{}).flat().join('<br>')}); } else Swal.fire('Error','Gagal menyimpan','error'); }
            });
        });
        // buka modal dan isi form dari data yang ada di halaman (blade) atau fetch via AJAX
        $('#btn-edit-akun').on('click', function() {
            // isi dari blade (safe) — fallback ke AJAX get jika perlu
            $('#akun-name').val("{{ addslashes($user->name) }}");
            $('#akun-email').val("{{ addslashes($user->email) }}");
            $('#akun-nama_bumdes').val("{{ addslashes($user->nama_bumdes ?? '') }}");
            $('#akun-alamat_bumdes').val("{{ addslashes($user->alamat_bumdes ?? '') }}");
            $('#akun-nomor_hukum_bumdes').val("{{ addslashes($user->nomor_hukum_bumdes ?? '') }}");

            new bootstrap.Modal(document.getElementById('modalAkunBumdes')).show();
        });

        $('#akun-form').on('submit', function(e) {
            e.preventDefault();
            $('#akun-form-errors').html('');
            $('#akun-submit').prop('disabled', true);

            const payload = {
                name: $('#akun-name').val().trim(),
                email: $('#akun-email').val().trim(),
                nama_bumdes: $('#akun-nama_bumdes').val().trim(),
                alamat_bumdes: $('#akun-alamat_bumdes').val().trim(),
                nomor_hukum_bumdes: $('#akun-nomor_hukum_bumdes').val().trim()
            };

            $.ajax({
                url: '/spj/arsip_kelola_akun/account',
                method: 'PUT',
                data: payload,
                success(res) {
                    Swal.fire({ icon: 'success', title: 'Tersimpan', timer: 900, showConfirmButton: false })
                        .then(() => location.reload());
                },
                error(xhr) {
                    $('#akun-submit').prop('disabled', false);
                    if (xhr.status === 422) {
                        const msgs = Object.values(xhr.responseJSON.errors || {}).flat();
                        $('#akun-form-errors').html('<div class="alert alert-danger">'+ msgs.join('<br>') +'</div>');
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menyimpan data', 'error');
                    }
                }
            });
        });


    }); // end jquery ready
</script>
@endsection
