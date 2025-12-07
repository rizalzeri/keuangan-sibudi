<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }

    .row-modal {
        margin: 0 -5px;
        width: 100%;
    }

    .card-modal {
        border: 1px solid black;
        float: left;
        width: 200px;
        padding: 0 10px;
        margin-right: 20px;
        border-radius: 10px;
    }

    .modal {
        font-size: 25px;
        font-weight: bold;
    }

    .card {}

    table {
        border-collapse: collapse;
        width: 100%
    }

    table,
    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 10px;
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h2>Rincian Pernyertaan Modal</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>



            {{-- <div class="row-modal">
                <div class="card-modal">
                    <h5 class="card-title">Modal <span>| Desa</span></h5>

                    <p class="modal">{{ formatRupiah($modals->sum('mdl_desa')) }}</p>
                </div>

                <div class="card-modal">

                    <h5 class="card-title">Modal <span>| Masyarakat</span></h5>

                    <p class="modal">{{ formatRupiah($modals->sum('mdl_masyarakat')) }}</p>

                </div>
            </div> --}}


            <div class="card overflow-auto">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                            <tr style="background-color: #dbdbdb">
                                <th scope="col">#</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Sumber</th>
                                @can('referral')
                                    <th scope="col">Simpanan Pokok</th>
                                    <th scope="col">Simpanan Wajib</th>
                                    <th scope="col">Simpanan Sukarela</th>
                                @else
                                    <th scope="col">Modal Desa</th>
                                    <th scope="col">Modal Masyarakat</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($modals as $modal)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $modal->tahun }}</td>
                                    <td>{{ $modal->sumber }}</td>
                                    <td>{{ formatRupiah($modal->mdl_desa) }}</td>
                                    <td>{{ formatRupiah($modal->mdl_masyarakat) }}</td>
                                    @can('referral')
                                        <td>{{ formatRupiah($modal->mdl_bersama) }}</td>
                                    @endcan
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold; background-color:rgb(125, 224, 125)">
                                <td colspan="3">Akumulasi</td>
                                <td>{{ formatRupiah($modals->sum('mdl_desa')) }}</td>
                                <td>{{ formatRupiah($modals->sum('mdl_masyarakat')) }}</td>
                                @can('referral')
                                    <td>{{ formatRupiah($modals->sum('mdl_bersama')) }}</td>
                                @endcan
                            </tr>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>

    </div>
</body>
