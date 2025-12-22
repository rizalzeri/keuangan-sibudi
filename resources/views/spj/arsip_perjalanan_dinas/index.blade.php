@extends('layouts.spj.main')

@section('container')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Arsip Perjalanan Dinas</h4>
        <div>
            <button class="btn btn-primary" id="btnAddPD"><i class="bi bi-plus-lg"></i> Tambah Data</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <!-- urutan kolom: Kegiatan, Nomor, Tanggal, Tempat, Transport -->
                <table class="table table-striped table-bordered datatable" id="pdTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kegiatan</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal</th>
                            <th>Tempat</th>
                            <th>Transport</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->nomor_dokumen }}</td>
                                <td>
                                    {{ $item->tanggal_perjalanan_dinas
                                        ? $item->tanggal_perjalanan_dinas->locale('id')->translatedFormat('d F Y')
                                        : '' }}
                                </td>
                                <td>
                                    {{-- tempat = tempat_1 (kolom tempat di DB) --}}
                                    {{ $item->tempat }}
                                    @if($item->tempat_2)
                                        <div class="text-muted">({{ $item->tempat_2 }})</div>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($item->transport) && is_array($item->transport))
                                        {{ implode(', ', array_map(function($t){
                                            if(is_array($t)){
                                                return data_get($t,'other', data_get($t,'label', json_encode($t)));
                                            }
                                            return $t;
                                        }, $item->transport)) }}
                                    @else
                                        {{ is_string($item->transport) ? $item->transport : '' }}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    <a href="{{ url('/spj/arsip_perjalanan_dinas/'.$item->id.'/generate-doc') }}" class="btn btn-sm btn-success" title="Download Template"><i class="bi bi-download"></i></a>
                                    <button class="btn btn-sm btn-info btn-view" data-id="{{ $item->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal View Perjalanan Dinas -->
<div class="modal fade" id="viewModalPD" tabindex="-1" aria-labelledby="viewModalPDLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Perjalanan Dinas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <dl class="row mb-0">
            <dt class="col-sm-4">Kegiatan</dt>
            <dd class="col-sm-8" id="viewPdKegiatan">-</dd>

            <dt class="col-sm-4">Nomor Dokumen</dt>
            <dd class="col-sm-8" id="viewPdNomor">-</dd>

            <dt class="col-sm-4">Tanggal</dt>
            <dd class="col-sm-8" id="viewPdTanggal">-</dd>

            <dt class="col-sm-4">Tempat</dt>
            <dd class="col-sm-8" id="viewPdTempat">-</dd>

            <dt class="col-sm-4">Transport</dt>
            <dd class="col-sm-8" id="viewPdTransport">-</dd>

            <dt class="col-sm-4">GDrive</dt>
            <dd class="col-sm-8">
              <a href="#" id="viewPdGdrive" target="_blank" class="text-decoration-none text-muted">Tidak ada link</a>
            </dd>
          </dl>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>

@include('spj.arsip_perjalanan_dinas.components.modal_form')

<script>
document.addEventListener('DOMContentLoaded', function(){
    // DataTable init if available
    if(window.jQuery && $.fn.DataTable){
        $('#pdTable').DataTable();
    }

    const form = document.getElementById('pdForm');
    const modalEl = document.getElementById('formModalPD');
    const bsModal = new bootstrap.Modal(modalEl);
    const viewModal = new bootstrap.Modal(document.getElementById('viewModalPD'));

    const pembiayaanDisplay = document.getElementById('pembiayaan_display');
    const pembiayaanHidden = document.getElementById('pembiayaan');

    // format helper: 100000 -> "100.000"
    function formatRupiah(angkaStr){
        if(angkaStr === null || angkaStr === undefined) return '';
        let s = String(angkaStr).replace(/^0+/, '');
        if(s === '') s = '0';
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePembiayaan(value){
        const angka = String(value || '').replace(/\D/g, '').replace(/^0+/, '') || '';
        pembiayaanHidden.value = angka === '' ? '' : parseInt(angka, 10);
        pembiayaanDisplay.value = angka === '' ? '' : 'Rp.' + formatRupiah(angka);
    }

    // listeners untuk pembiayaan display
    pembiayaanDisplay.addEventListener('input', function(e){
        const onlyDigits = this.value.replace(/\D/g, '');
        updatePembiayaan(onlyDigits);
    });
    pembiayaanDisplay.addEventListener('blur', function(){
        if(pembiayaanHidden.value === ''){ this.value = ''; }
        else { this.value = 'Rp.' + formatRupiah(String(pembiayaanHidden.value)); }
    });

    // Open create modal
    document.getElementById('btnAddPD').addEventListener('click', function(){
        resetForm();
        document.getElementById('formModalLabelPD').textContent = 'Tambah Perjalanan Dinas';
        document.getElementById('_method_pd').value = 'POST';
        form.action = '{{ url('/spj/arsip_perjalanan_dinas') }}';
        bsModal.show();
    });

    // Add personil row (use wrapper to avoid passing event object)
    document.getElementById('btnAddPersonil').addEventListener('click', function(){ addPersonilRow(); });

    // transport lainnya toggle
    // transport 'Lainnya' toggle (pastikan ini dieksekusi setelah DOM ready)
    const trLain = document.getElementById('tr_lainnya');
    if (trLain) {
        trLain.addEventListener('change', function(e){
            const wrapper = document.getElementById('transportOtherWrapper');
            const otherInput = document.getElementById('transportOtherInput');
            if (e.target.checked) {
                wrapper.classList.remove('d-none');
                // beri name agar ikut submit sebagai transport[]
                otherInput.setAttribute('name','transport[]');
            } else {
                wrapper.classList.add('d-none');
                otherInput.removeAttribute('name');
                otherInput.value = '';
            }
        });
    }


    // Edit button
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', async function(){
            const id = this.dataset.id;
            resetForm();
            document.getElementById('formModalLabelPD').textContent = 'Edit Perjalanan Dinas';
            document.getElementById('_method_pd').value = 'PUT';
            form.action = '{{ url('/spj/arsip_perjalanan_dinas') }}/' + id;

            try{
                const res = await fetch('{{ url('/spj/arsip_perjalanan_dinas') }}/' + id);
                if(!res.ok) throw new Error('Gagal ambil data');
                const data = await res.json();

                // populate simple fields
                document.getElementById('pdKegiatan').value = data.kegiatan || '';
                document.getElementById('pdNomor').value = data.nomor_dokumen || '';
                if(data.tanggal_perjalanan_dinas){ document.getElementById('pdTanggal').value = data.tanggal_perjalanan_dinas; }
                document.getElementById('pdTempat').value = data.tempat || '';
                document.getElementById('tempat2').value = data.tempat_2 || '';
                document.getElementById('pdGdrive').value = data.link_gdrive || '';
                document.getElementById('dasarPerjalanan').value = data.dasar_perjalanan_tugas || '';
                document.getElementById('pejabatPemberi').value = data.pejabat_pemberi_tugas || '';
                document.getElementById('jabatanPejabat').value = data.jabatan_pejabat || '';
                document.getElementById('maksudPerjalanan').value = data.maksud_perjalanan_tugas || '';
                document.getElementById('tujuan1').value = data.tujuan_1 || '';
                document.getElementById('tujuan2').value = data.tujuan_2 || '';
                document.getElementById('lamaHari').value = data.lama_perjalanan_hari || '';
                document.getElementById('dasarPembeban').value = data.dasar_pembebanan_anggaran || '';
                // set pembiayaan: hidden numeric + display formatted
                if(data.pembiayaan !== null && data.pembiayaan !== undefined){
                    pembiayaanHidden.value = String(Math.round(data.pembiayaan));
                    pembiayaanDisplay.value = 'Rp.' + formatRupiah(String(Math.round(data.pembiayaan)));
                } else {
                    pembiayaanHidden.value = '';
                    pembiayaanDisplay.value = '';
                }
                document.getElementById('keterangan').value = data.keterangan || '';
                document.getElementById('tempatDikeluarkan').value = data.tempat_dikeluarkan || '';

                // transport (clear checkboxes first)
                document.querySelectorAll('.transport-checkbox').forEach(cb => cb.checked = false);
                document.getElementById('transportOtherWrapper').classList.add('d-none');
                document.getElementById('transportOtherInput').removeAttribute('name');

                if(data.transport){
                    let transports = data.transport;
                    if(typeof transports === 'string'){
                        try{ transports = JSON.parse(transports); }catch(e){ transports = [transports]; }
                    }
                    if(Array.isArray(transports)){
                        transports.forEach(t => {
                            if(typeof t === 'string'){
                                const el = Array.from(document.querySelectorAll('.transport-checkbox')).find(x => x.value === t);
                                if(el){ el.checked = true; }
                                else{
                                    document.getElementById('transportOtherWrapper').classList.remove('d-none');
                                    document.getElementById('transportOtherInput').value = t;
                                    document.getElementById('transportOtherInput').setAttribute('name','transport[]');
                                }
                            } else if(typeof t === 'object'){
                                const label = t.other || t.label || JSON.stringify(t);
                                document.getElementById('transportOtherWrapper').classList.remove('d-none');
                                document.getElementById('transportOtherInput').value = label;
                                document.getElementById('transportOtherInput').setAttribute('name','transport[]');
                            }
                        });
                    }
                }

                // pegawai_personil: normalisasi sebelum menampilkan
                document.getElementById('personilList').innerHTML = '';
                if(data.pegawai_personil && Array.isArray(data.pegawai_personil)){
                    const normalized = normalizePersonilArray(data.pegawai_personil);
                    normalized.forEach(p => addPersonilRow(p.nama || '', p.jabatan || ''));
                }

                bsModal.show();

            }catch(err){
                console.error(err);
                alert('Gagal mengambil data');
            }
        });
    });

    // View button -> isi modal view dan tampilkan
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', async function(){
            const id = this.dataset.id;
            try{
                const res = await fetch('{{ url('/spj/arsip_perjalanan_dinas') }}/' + id);
                if(!res.ok) throw new Error('Gagal ambil data');
                const data = await res.json();

                // isi view modal fields
                document.getElementById('viewPdKegiatan').textContent = data.kegiatan || '-';
                document.getElementById('viewPdNomor').textContent = data.nomor_dokumen || '-';
                // tanggal -> indonesia
                document.getElementById('viewPdTanggal').textContent = data.tanggal_perjalanan_dinas
                    ? (new Date(data.tanggal_perjalanan_dinas)).toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })
                    : '-';
                document.getElementById('viewPdTempat').textContent = (data.tempat || '') + (data.tempat_2 ? (' ('+data.tempat_2+')') : '');
                // transport display
                let transportText = '';
                if(data.transport){
                    if(typeof data.transport === 'string'){
                        try{ transportText = JSON.parse(data.transport); }catch(e){ transportText = data.transport; }
                    } else if(Array.isArray(data.transport)){
                        transportText = data.transport.map(t => {
                            if(typeof t === 'string') return t;
                            if(typeof t === 'object') return t.other || t.label || JSON.stringify(t);
                            return t;
                        }).join(', ');
                    } else transportText = String(data.transport);
                }
                document.getElementById('viewPdTransport').textContent = transportText || '-';

                const gdrive = data.link_gdrive || '';
                const a = document.getElementById('viewPdGdrive');
                if(gdrive){
                    a.href = gdrive;
                    a.textContent = 'Buka GDrive';
                    a.classList.remove('text-muted');
                } else {
                    a.href = '#';
                    a.textContent = 'Tidak ada link';
                    a.classList.add('text-muted');
                }

                viewModal.show();
            }catch(e){
                console.error(e);
                alert('Gagal mengambil data');
            }
        });
    });

    // Delete with SweetAlert2 if available (keputusan Anda: saya biarkan seperti ini)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            function doDelete() {
                fetch(`{{ url('/spj/arsip_perjalanan_dinas') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // JANGAN paksa json, cukup cek status
                    if (response.ok) {
                        return { success: true };
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.success) {
                        // 🔥 tampilkan notif sukses dulu
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data perjalanan dinas berhasil dihapus',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert('Data berhasil dihapus');
                            location.reload();
                        }
                    } else {
                        alert(result.message || 'Gagal menghapus data');
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }

            // konfirmasi hapus
            if (window.Swal) {
                Swal.fire({
                    title: 'Hapus data?',
                    text: 'Data akan dihapus secara permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) doDelete();
                });
            } else {
                if (confirm('Yakin ingin menghapus data ini?')) doDelete();
            }
        });
    });
    // helpers
    function resetForm(){
        form.reset();
        document.getElementById('personilList').innerHTML = '';
        document.getElementById('transportOtherWrapper').classList.add('d-none');
        document.getElementById('transportOtherInput').removeAttribute('name');
        // clear pembiayaan fields
        pembiayaanHidden.value = '';
        pembiayaanDisplay.value = '';
    }

    // normalize pegawai_personil array in JS:
    // handles arrays like [{nama:..},{jabatan:..}, {nama:.., jabatan:..}, ...] into [{nama:..., jabatan:...}, ...]
    function normalizePersonilArray(raw){
        if(!Array.isArray(raw)) return [];
        const out = [];
        let temp = {};
        for(let i=0;i<raw.length;i++){
            const it = raw[i];
            if(it === null || it === undefined) continue;
            if(typeof it === 'string'){
                if(!temp.nama && !temp.jabatan) { temp.nama = it; }
                else { out.push(Object.assign({}, temp)); temp = { nama: it }; }
                continue;
            }
            const namaVal = (it.nama !== undefined) ? it.nama : (it[0] !== undefined ? it[0] : undefined);
            const jabVal = (it.jabatan !== undefined) ? it.jabatan : (it[1] !== undefined ? it[1] : undefined);

            if(namaVal !== undefined && jabVal !== undefined){
                out.push({ nama: namaVal, jabatan: jabVal });
                temp = {};
            } else if(namaVal !== undefined){
                if(temp.nama && !temp.jabatan){
                    out.push(Object.assign({}, temp));
                    temp = { nama: namaVal };
                } else {
                    temp.nama = namaVal;
                    if(temp.nama && temp.jabatan){ out.push(Object.assign({}, temp)); temp = {}; }
                }
            } else if(jabVal !== undefined){
                if(temp.jabatan && !temp.nama){
                    out.push(Object.assign({}, temp));
                    temp = { jabatan: jabVal };
                } else {
                    temp.jabatan = jabVal;
                    if(temp.nama && temp.jabatan){ out.push(Object.assign({}, temp)); temp = {}; }
                }
            } else {
                const keys = Object.keys(it);
                if(keys.length === 1){
                    temp[keys[0]] = it[keys[0]];
                    if(temp.nama && temp.jabatan){ out.push(Object.assign({}, temp)); temp = {}; }
                } else if(keys.length >= 2){
                    out.push({ nama: it[keys[0]], jabatan: it[keys[1]] });
                    temp = {};
                }
            }
        }
        if(Object.keys(temp).length) out.push(temp);
        return out;
    }

    function addPersonilRow(nama = '', jabatan = ''){
        // ensure we don't get event object (caller uses wrapper function)
        if(typeof nama === 'object' && nama !== null) nama = '';
        const container = document.getElementById('personilList');
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex gap-2 align-items-start mb-2 personil-row';

        const inpNama = document.createElement('input');
        inpNama.type = 'text';
        inpNama.name = 'pegawai_personil[][nama]';
        inpNama.placeholder = 'Nama';
        inpNama.className = 'form-control';
        inpNama.value = nama || '';

        const inpJab = document.createElement('input');
        inpJab.type = 'text';
        inpJab.name = 'pegawai_personil[][jabatan]';
        inpJab.placeholder = 'Jabatan';
        inpJab.className = 'form-control';
        inpJab.value = jabatan || '';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-danger btnRemovePersonil';
        btn.textContent = 'Hapus';
        btn.addEventListener('click', function(){ wrapper.remove(); });

        wrapper.appendChild(inpNama);
        wrapper.appendChild(inpJab);
        wrapper.appendChild(btn);
        container.appendChild(wrapper);
    }

});
</script>

@endsection
