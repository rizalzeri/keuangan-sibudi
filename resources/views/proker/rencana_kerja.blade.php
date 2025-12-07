  <h5 class="card-title">F. RENCANA KERJASAMA</h5>

  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#kerjasamaModal">
      Tambah Kerjasama
  </button>

  <!-- Modal -->
  <div class="modal fade" id="kerjasamaModal" tabindex="-1" aria-labelledby="kerjasamaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="kerjasamaModalLabel">Modal title</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="/proker/rencana/kerjasama/store" method="POST">
                      @csrf
                      <table class="table table-bordered" id="table2">
                          <thead>
                              <tr>

                                  <th>
                                      Pihak Kerjasama
                                  </th>
                                  <th>
                                      Deskrisi Bentuk Kerjasama
                                  </th>
                                  <th>
                                      Output Kerjasama
                                  </th>
                                  <th>
                                      Aksi
                                  </th>
                              </tr>

                          </thead>
                          <tbody>

                              <tr>

                                  <td>
                                      <input type="text" class="form-control" name="input[0][pihak]">
                                  </td>
                                  <td>
                                      <input type="text" class="form-control" name="input[0][deskripsi]">
                                  </td>
                                  <td>
                                      <input type="text" class="form-control" name="input[0][output]">
                                  </td>
                                  <td><Button type="button" id="tambah2"
                                          class="btn btn-sm btn-success">Tambah</Button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>


                      <script>
                          var i = 0;

                          $('#tambah2').click(function() {
                              i++;

                              $('#table2').append(
                                  `<tr>

                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][pihak]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][deskripsi]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="input[` + i + `][output]">
                                    </td>
                                    <td><Button class="btn btn-sm btn-danger hapus-table2" id="tambah">hapus</Button></td>
                                </tr>`
                              );

                          });

                          $(document).on('click', '.hapus-table2', function() {
                              $(this).parents('tr').remove()
                          });
                      </script>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
          </div>
      </div>
  </div>
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>
                  No
              </th>
              <th>
                  Pihak Kerja Sama
              </th>
              <th>
                  Deskripsi Bentuk Kerjasama
              </th>
              <th>
                  Output Kerjasama
              </th>
              <th>aksi</th>
          </tr>

      </thead>
      <tbody>
          @php
              $i = 1;
          @endphp
          @foreach ($kerjasamas as $kerjasama)
              <tr>
                  <td>
                      {{ $i++ }}
                  </td>
                  <td>
                      {{ $kerjasama->pihak }}
                  </td>
                  <td>
                      {{ $kerjasama->deskripsi }}
                  </td>
                  <td>
                      {{ $kerjasama->output }}
                  </td>
                  <td>
                      <form action="/proker/rencana/kerjasama/{{ $kerjasama->id }}" method="POST">
                          @csrf
                          @method('DELETE')

                          <button type="submit" onclick="return confirm('Apakah yakin dihapus?')"
                              class="btn btn-sm btn-danger">Hapus</button>
                      </form>
                  </td>
              </tr>
          @endforeach

          @if (count($kerjasamas) <= 0)
              <tr class="text-center">
                  <td colspan="4">Data Kerjasama Kosong</td>
              </tr>
          @endif
      </tbody>
  </table>
