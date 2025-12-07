<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        @can('admin')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin') ? '' : 'collapsed' }}" href="/admin">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard Admin</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/data-user/create') ? '' : 'collapsed' }}"
                    href="/admin/data-user/create">
                    <i class="bi bi-person-fill"></i>
                    <span>Tambah User</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/data-user') ? '' : 'collapsed' }}" href="/admin/data-user">
                    <i class="bi bi-people-fill"></i>
                    <span>All User</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/wilayah*') ? '' : 'collapsed' }}" data-bs-target="#menuWilayah"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-geo-alt"></i><span>Wilayah</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="menuWilayah" class="nav-content {{ Request::is('admin/wilayah*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">

                    {{-- Daftar kabupaten akan dimuat lewat JavaScript --}}
                    <div id="kabupaten-list" class="ps-3 py-2 text-muted small">Memuat kabupaten...</div>
                </ul>
            </li>

            {{-- Script AJAX --}}
            <script>
                document.addEventListener("DOMContentLoaded", async () => {
                    const kabupatenList = document.getElementById("kabupaten-list");

                    try {
                        // 🔹 ambil kabupaten dari backend Laravel proxy
                        const kabupatenRes = await fetch("/api/wilayah/kabupaten");
                        const kabupatenData = await kabupatenRes.json();

                        kabupatenList.innerHTML = "";

                        kabupatenData.forEach(kab => {
                            const kabItem = document.createElement("li");
                            kabItem.innerHTML = `
                <a class="nav-link collapsed" data-bs-target="#kab${kab.id}"
                   data-bs-toggle="collapse" href="#">
                    <i class="bi bi-circle"></i>
                    <span>${kab.name}</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="kab${kab.id}" class="nav-content collapse" data-bs-parent="#menuWilayah">
                    <li class="text-muted small ps-3 py-1" id="loading-${kab.id}">Memuat kecamatan...</li>
                </ul>
            `;
                            kabupatenList.appendChild(kabItem);

                            // 🔹 klik kabupaten → load kecamatan via proxy Laravel
                            kabItem.querySelector('a').addEventListener('click', async () => {
                                const targetUl = document.getElementById(`kab${kab.id}`);
                                const loading = document.getElementById(`loading-${kab.id}`);

                                if (targetUl.dataset.loaded) return;

                                try {
                                    const kecRes = await fetch(`/api/wilayah/kecamatan/${kab.id}`);
                                    const kecData = await kecRes.json();

                                    targetUl.innerHTML = "";
                                    kecData.forEach(kec => {
                                        const kecItem = document.createElement("li");
                                        kecItem.innerHTML = `
                            <a href="/admin/wilayah/kecamatan/${encodeURIComponent(kec.name)}">
                                <i class="bi bi-dash"></i><span>${kec.name}</span>
                            </a>`;
                                        targetUl.appendChild(kecItem);
                                    });

                                    targetUl.dataset.loaded = true;
                                } catch (err) {
                                    loading.textContent = "❌ Gagal memuat kecamatan";
                                }
                            });
                        });

                    } catch (error) {
                        kabupatenList.innerHTML = "❌ Gagal memuat kabupaten.";
                        console.error("Error:", error);
                    }
                });
            </script>




            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/langganan*') ? '' : 'collapsed' }}"
                    data-bs-target="#laporanKeuangan" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-pulse"></i><span>Atur Langganan</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="laporanKeuangan" class="nav-content  {{ Request::is('admin/langganan*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/admin/langganan/bumdesa"
                            class="{{ Request::is('admin/langganan/bumdesa*') ? 'active' : '' }}">
                            <i class="bi bi-circle "></i><span>Bumdes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/langganan/bumdes-bersama"
                            class="{{ Request::is('admin/langganan/bumdes-bersama*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Koperasi</span>
                        </a>
                    </li>


                </ul>
            </li><!-- End Charts Nav -->
        @endcan
        @can('bumdes')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="/">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('visi/misi') ? '' : 'collapsed' }}" href="/visi/misi">
                    <i class="bi bi-award-fill"></i>
                    <span>Visi Misi</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('unit*') ? '' : 'collapsed' }}" href="/unit">
                    <i class="bi bi-diagram-3"></i>
                    <span>Unit Usaha</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('modal*') ? '' : 'collapsed' }}" href="/modal">
                    <i class="bi bi-clipboard"></i>
                    <span>Modal</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('hutang*') ? '' : 'collapsed' }}" href="/hutang">
                    <i class="bi bi-clipboard2-fill"></i>
                    <span>Hutang</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('aset*') ? '' : 'collapsed' }}" data-bs-target="#charts-nav"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-boxes"></i><span>Aset/Harta</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="charts-nav" class="nav-content  {{ Request::is('aset*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/aset/buk" class="{{ Request::is('aset/buk*') ? 'active' : '' }}">
                            <i class="bi bi-circle "></i><span>Buku
                                Kas</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/bank" class="{{ Request::is('aset/bank*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Bank</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/piutang" class="{{ Request::is('aset/piutang*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Piutang</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/pinjaman" class="{{ Request::is('aset/pinjaman*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Pinjaman</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/persediaan" class="{{ Request::is('aset/persediaan*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Persediaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/bdmuk" class="{{ Request::is('aset/bdmuk*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Dibayar dimuka</span>
                        </a>
                    </li>

                    <li>
                        <a href="/aset/investasi" class="{{ Request::is('aset/investasi*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Iventaris</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/bangunan" class="{{ Request::is('aset/bangunan*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Bangunan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/aset/aktivalain" class="{{ Request::is('aset/aktivalain*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Aktiva Lain</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Charts Nav -->




            <li class="nav-item">
                <a class="nav-link {{ Request::is('rincian-laba-rugi*') ? '' : 'collapsed' }}" href="/rincian-laba-rugi">
                    <i class="bi bi-card-checklist"></i>
                    <span>Laba Rugi Bulanan</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('dithn*') ? '' : 'collapsed' }}" href="/dithn">
                    <i class="bi bi-ban"></i>
                    <span>Laba ditahan</span>
                </a>
            </li><!-- End Dashboard Nav -->



            <li class="nav-item">
                <a class="nav-link {{ Request::is('penyusutan*') ? '' : 'collapsed' }}" href="/penyusutan">
                    <i class="bi bi-graph-down"></i>
                    <span>Penyusutan</span>
                </a>
            </li><!-- End Dashboard Nav -->

            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('rekonsiliasi*') ? '' : 'collapsed' }}" href="/rekonsiliasi">
                    <i class="bi bi-percent"></i>
                    <span>Rekonsiliasi Kas</span>
                </a>
            </li><!-- End Dashboard Nav --> --}}



            <li class="nav-item">
                <a class="nav-link {{ Request::is('laporan-keuangan*') ? '' : 'collapsed' }}"
                    data-bs-target="#laporanKeuangan" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-pulse"></i><span>Laporan Keuangan</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="laporanKeuangan" class="nav-content  {{ Request::is('laporan-keuangan*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/laporan-keuangan/neraca"
                            class="{{ Request::is('laporan-keuangan/neraca*') ? 'active' : '' }}">
                            <i class="bi bi-circle "></i><span>Neraca</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laporan-keuangan/laporan-laba-rugi"
                            class="{{ Request::is('laporan-keuangan/laporan-laba-rugi*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Laba Rugi</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laporan-keuangan/laporan-arus-kas"
                            class="{{ Request::is('laporan-keuangan/laporan-arus-kas*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Arus Kas</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laporan-keuangan/laporan-perubahan-modal"
                            class="{{ Request::is('laporan-keuangan/laporan-perubahan-modal*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Perubahan Ekuitas</span>
                        </a>
                    </li>

                </ul>
            </li><!-- End Charts Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('export-pdf/cetak-laporan*') ? '' : 'collapsed' }}"
                    href="/export-pdf/cetak-laporan">
                    <i class="bi bi-filetype-pdf"></i>
                    <span>Cetak Laporan</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-heading">Lembar Pengesahan</li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('lpj*') ? '' : 'collapsed' }}" href="/lpj">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Pertanggung jawaban</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('proker*') ? '' : 'collapsed' }}" href="/proker">
                    <i class="bi bi-file-easel"></i>
                    <span>Program Kerja</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('akp*') ? '' : 'collapsed' }}" href="/akp">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    <span>Analisa Aspek Ketahanan Pangan</span>
                </a>
            </li><!-- End Dashboard Nav -->
            @php

                // Tanggal tujuan
                $targetDate = new DateTime(auth()->user()->tgl_langganan);
                // Tanggal hari ini
                $today = new DateTime('now');

                // Hitung selisihnya
                $interval = $today->diff($targetDate);

            @endphp
            <li class="nav-heading">Langganan</li>
            <li class="nav-item list-group-item justify-content-between align-items-center">
                <a class="nav-link {{ Request::is('langganan*') ? '' : 'collapsed' }}" href="/langganan">
                    <i class="bi bi-bell"></i>
                    <span>Langganan</span>
                    <span class="badge text-bg-primary rounded-pill ms-3">{{ $interval->days }} Hari</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-heading">Info Lebih Lanjut</li>
            <li class="nav-item list-group-item justify-content-between align-items-center">
                <a class="nav-link {{ Request::is('kontak/admin*') ? '' : 'collapsed' }}" href="/kontak/admin">
                    <i class="bi bi-envelope"></i>
                    <span>Contact</span>

                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item list-group-item justify-content-between align-items-center">
                <a class="nav-link collapsed" target="_blank" href="https://www.youtube.com/@bumdespro">
                    <i class="bi bi-youtube"></i>
                    <span>Tutorial</span>
                    <span class="badge text-bg-primary rounded-pill ms-3"></span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item list-group-item justify-content-between align-items-center">
                <a class="nav-link collapsed" target="_blank" href="https://portalbumdes.com/">
                    <i class="bi bi-question-circle"></i>
                    <span>pelajari bumdes</span>
                    <span class="badge text-bg-primary rounded-pill ms-3"></span>
                </a>
            </li><!-- End Dashboard Nav -->
        @endcan



    </ul>

</aside><!-- End Sidebar-->
