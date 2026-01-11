@extends('layouts.main')

@section('container')
<div class="row">
    <div class="col-lg-12">
        <div class="pagetitle">
            <h1>Produk Digital</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Manajemen Produk Digital</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg"></i> Tambah Produk Digital
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Sub Kategori / Judul Produk</th>
                            <th>Link</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subCategories as $i => $sub)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $sub->kategori->kategori ?? '-' }}</td>
                            <td>{{ $sub->sub_kategori }}</td>
                            <td>
                                @if($sub->link)
                                    <a href="{{ $sub->link }}" target="_blank" rel="noopener noreferrer">
                                        {{ $sub->link }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning btn-edit"
                                    data-id="{{ $sub->id }}"
                                    data-kategori="{{ $sub->kategori_id }}"
                                    data-title="{{ $sub->sub_kategori }}"
                                    data-link="{{ $sub->link }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $sub->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.produk_digital.store') }}" method="POST" id="form-create">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Produk Digital</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Judul Produk Template</label>
                <input type="text" name="sub_kategori" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Download</label>
                <input type="text" name="link" class="form-control"  />
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Produk Digital</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit-id" name="id">
            <div class="mb-3">
                <label class="form-label">Judul Produk Template</label>
                <input type="text" name="sub_kategori" id="edit-title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Download</label>
                <input type="text" name="link" id="edit-link" class="form-control"  />
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" id="edit-kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    const $tbody = $('.datatable tbody');

    // show session success once using sessionStorage + global guard
    @if(session('success'))
    (function () {
        try {
            const msg = "{{ addslashes(session('success')) }}";
            const key = 'flash_shown_' + btoa(msg);
            if (!sessionStorage.getItem(key) && !window._flashShown) {
                window._flashShown = true;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: msg,
                    timer: 1800,
                    showConfirmButton: false
                });
                sessionStorage.setItem(key, '1');
            }
        } catch (err) {
            if (!window._flashShown) {
                window._flashShown = true;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 1800,
                    showConfirmButton: false
                });
            }
        }
    })();
    @endif

    // EDIT: delegated on tbody
    $tbody.on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const id = $btn.data('id');
        const kategori = $btn.data('kategori') || '';
        const title = $btn.data('title') || '';
        const link = $btn.data('link') || '';

        $('#edit-id').val(id);
        $('#edit-title').val(title);
        $('#edit-link').val(link);
        $('#edit-kategori').val(kategori);

        // set action to the correct route (underscore)
        $('#form-edit').attr('action', '{{ url("admin/produk_digital") }}/' + id);

        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });

    // DELETE: delegated on tbody (AJAX)
    $tbody.on('click', '.btn-delete', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const $btn = $(this);

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            // disable button to avoid double click
            $btn.prop('disabled', true);

            fetch('{{ url("admin/produk_digital") }}/' + id, {
                method: 'DELETE',
                credentials: 'same-origin', // ensure cookies/session sent
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (res) {
                // treat 2xx as success; allow 204, 200 etc.
                if (res.status >= 200 && res.status < 300) {
                    // try to parse json if any, otherwise resolve to {}
                    const ct = res.headers.get('content-type') || '';
                    if (ct.indexOf('application/json') !== -1) {
                        return res.json();
                    }
                    return {};
                }

                // if 419 (session expired) show specific message
                if (res.status === 419) {
                    throw new Error('Session expired. Silakan login ulang.');
                }

                // try to parse error body if JSON
                const ct2 = res.headers.get('content-type') || '';
                if (ct2.indexOf('application/json') !== -1) {
                    return res.json().then(err => {
                        throw new Error(err.message || 'Server error');
                    });
                }

                throw new Error('Network error');
            })
            .then(function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus',
                    timer: 1200,
                    showConfirmButton: false
                });
                // remove row from DataTable without full reload
                // use the table variable we initialized earlier
                try {
                    table.row(row).remove().draw(false);
                } catch (err) {
                    // fallback: remove DOM row if DataTable fails
                    row.remove();
                }
            })
            .catch(function (err) {
                const msg = err && err.message ? err.message : 'Gagal menghapus data';
                Swal.fire('Error', msg, 'error');
            })
            .finally(function () {
                // re-enable button
                $btn.prop('disabled', false);
            });
        });
    });

});
</script>

@endsection
