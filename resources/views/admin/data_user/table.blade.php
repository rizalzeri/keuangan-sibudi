<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Kecamatan</th>
            <th>Desa</th>
            <th>Sisa Langganan (Hari)</th>
            <th>Status</th>
            <th>Password</th>
            <th>Langganan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @forelse ($users as $user)
            @if ($user->role != 'admin')
                @php
                    $target = new DateTime($user->tgl_langganan);
                    $today = new DateTime();
                    $remaining = $target < $today ? 0 : $today->diff($target)->days;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profil->no_wa ?? '-' }}</td>
                    <td>{{ $user->profil->kecamatan ?? '-' }}</td>
                    <td>{{ $user->profil->desa ?? '-' }}</td>
                    <td>{{ $remaining }}</td>
                    <td>
                        {!! $remaining <= 0 ? '<span class="text-danger">Tidak Aktif</span>' : '<span class="text-success">Aktif</span>' !!}
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#user{{ $user->id }}">
                            Ubah Password
                        </a>
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#langganan{{ $user->id }}">
                            Ubah Langganan
                        </a>
                    </td>
                    <td>
                        <form action="/admin/data-user/{{ $user->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin dihapus?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="langganan{{ $user->id }}" tabindex="-1"
                    aria-labelledby="langganan{{ $user->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="langganan{{ $user->id }}Label">Update
                                    Langganan
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="/admin/langganan/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <label for="langganan">Langganan {{ $user->id }}</label>
                                    <select class="form-select" aria-label="Default select example" name="langganan">
                                        @php

                                            if ($user->referral == true) {
                                                $jenis = 'bumdesa';
                                            } elseif ($user->referral == false) {
                                                $jenis = 'bumdes-bersama';
                                            }
                                        @endphp

                                        @foreach ($langganans->where('jenis', $jenis) as $langganan)
                                            <option value="{{ $langganan->jumlah_bulan }}">
                                                {{ $langganan->waktu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="user{{ $user->id }}" tabindex="-1"
                    aria-labelledby="user{{ $user->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="user{{ $user->id }}Label">Ganti
                                    Password
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="/admin/data-user/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" class="form-control">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <tr>
                <td colspan="11" class="text-center text-muted">Tidak ada data</td>
            </tr>
        @endforelse


    </tbody>
</table>
