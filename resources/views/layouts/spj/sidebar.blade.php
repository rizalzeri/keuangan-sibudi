<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        {{-- Dashboard utama --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        {{-- 1. Arsip kelembagaan --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('arsip-kelembagaan*') ? '' : 'collapsed' }}" href="/spj/arsip_kelembagaan">
                <i class="bi bi-archive-fill"></i>
                <span>Arsip Kelembagaan</span>
            </a>
        </li>

        {{-- 2. Prosedur transaksi (collapse) --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('prosedur-transaksi*') ? '' : 'collapsed' }}"
               data-bs-target="#menuProsedur" data-bs-toggle="collapse" href="#">
                <i class="bi bi-receipt"></i><span>Prosedur Transaksi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="menuProsedur" class="nav-content {{ Request::is('prosedur-transaksi*') ? '' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/spj/bukti_kas_masuk" class="{{ Request::is('spj/prosedur_transaksi/bukti_kas_masuk*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Buat Bukti Kas Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/bukti_kas_keluar" class="{{ Request::is('spj/prosedur_transaksi/bukti_kas_keluar*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Buat Bukti Kas Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/bukti_bank_masuk" class="{{ Request::is('spj/prosedur_transaksi/bukti_bank_masuk*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Buat Bukti Bank Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/bukti_bank_keluar" class="{{ Request::is('spj/prosedur_transaksi/bukti_bank_keluar*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Buat Bukti Bank Keluar</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Prosedur Transaksi Nav -->

        {{-- 3. Arsip SPJ Pembukuan --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('/spj/arsip_pembukuan_1*') ? '' : 'collapsed' }}" href="/spj/arsip_pembukuan_1">
                <i class="bi bi-folder2-open"></i>
                <span>Arsip SPJ Pembukuan</span>
            </a>
        </li>

        {{-- 4. Arsip SPJ Pembukuan 2 --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('/spj/arsip_pembukuan_2*') ? '' : 'collapsed' }}" href="/spj/arsip_pembukuan_2">
                <i class="bi bi-folder2"></i>
                <span>Arsip SPJ Pembukuan 2</span>
            </a>
        </li>

        {{-- 5. Arsip kegiatan (collapse) --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('arsip-kegiatan*') ? '' : 'collapsed' }}"
               data-bs-target="#menuKegiatan" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Arsip Kegiatan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="menuKegiatan" class="nav-content {{ Request::is('arsip-kegiatan*') ? '' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/spj/arsip_surat_masuk" class="{{ Request::is('/spj/arsip_surat_masuk*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Surat Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_surat_keluar" class="{{ Request::is('/spj/arsip_surat_keluar*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Surat Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_sop" class="{{ Request::is('/spj/arsip_sop*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Standar Operational Prosedur</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_berita_acara" class="{{ Request::is('arsip_berita_acara*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Berita Acara</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_perjanjian_kerja" class="{{ Request::is('/spj/arsip_perjanjian_kerja*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Perjanjian Kerjasama</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_perjalanan_dinas" class="{{ Request::is('/spj/arsip_perjalanan_dinas*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Perintah Perjalanan Dinas</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_notulen_rapat" class="{{ Request::is('/spj/arsip_notulen_rapat*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Notulen Rapat</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Arsip Kegiatan Nav -->

        {{-- 6. Dokumentasi (collapse) --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dokumentasi*') ? '' : 'collapsed' }}"
               data-bs-target="#menuDokumentasi" data-bs-toggle="collapse" href="#">
                <i class="bi bi-camera-video-fill"></i><span>Dokumentasi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="menuDokumentasi" class="nav-content {{ Request::is('dokumentasi*') ? '' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/spj/arsip_dokumentasi_foto" class="{{ Request::is('/spj/arsip_dokumentasi_foto*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Foto</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_dokumentasi_video" class="{{ Request::is('/spj/arsip_dokumentasi_video*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Video</span>
                    </a>
                </li>
                <li>
                    <a href="/spj/arsip_dokumentasi_berkas_dokumen" class="{{ Request::is('/spj/arsip_dokumentasi_berkas_dokumen*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Berkas Dokumen</span>
                    </a>
                </li>
            </ul>
        </li>

    </ul>

</aside>
