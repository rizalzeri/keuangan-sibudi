<nav style="--bs-breadcrumb-divider: '>';" class="d-flex justify-content-center mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item fs-6 "><a href="/proker" class="{{ Request::is('proker') ? 'active' : '' }}">B.1
                Kuantitatif</a>
        </li>
        <li class="breadcrumb-item fs-6 "><a href="/proker/kualititif"
                class="{{ Request::is('proker/kualititif*') ? 'active' : '' }}">B.2
                Kualititif</a>
        </li>
        <li class="breadcrumb-item fs-6"><a href="/proker/strategi"
                class="{{ Request::is('proker/strategi*') ? 'active' : '' }}">Strategi dan kebijakan</a></li>
        <li class="breadcrumb-item fs-6"><a href="/proker/sasaran"
                class="{{ Request::is('proker/sasaran*') ? 'active' : '' }}">Sasaran Kinerja</a></li>
        <li class="breadcrumb-item fs-6"><a href="/proker/rencana/kegiatan"
                class="{{ Request::is('proker/rencana/kegiatan*') ? 'active' : '' }}">Rencana Kegiatan & Kerjasama</a>
        </li>
        <li class="breadcrumb-item fs-6"><a href="/proker/penambahan/modal"
                class="{{ Request::is('proker/penambahan/modal*') ? 'active' : '' }}">Penambahan Modal</a>
        </li>
    </ol>
</nav>
