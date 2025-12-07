@extends('layouts.main')

@section('container')
    <div class="card">
        <div class="card-body">
            <form action="/lpj/{{ $lpj->id }}" method="POST">
                @csrf
                @method('PUT') <!-- Required for updating data -->

                <h3 class="card-title">Ikhtisar</h3>

                {{-- <div class="mb-3">
                    <label for="kegiatan_usaha" class="form-label">1. Jalannya kegiatan usaha sesuai dengan rencana program
                        kerja?</label>
                    <div>
                        <input type="radio" name="kegiatan_usaha" value="Sesuai" id="sesuai" class="form-check-input"
                            {{ $lpj->kegiatan_usaha == 'Sesuai' ? 'checked' : '' }} required>
                        <label for="sesuai" class="form-check-label">Sesuai</label>
                    </div>
                    <div>
                        <input type="radio" name="kegiatan_usaha" value="Tidak Sesuai" id="tidak_sesuai"
                            class="form-check-input" {{ $lpj->kegiatan_usaha == 'Tidak Sesuai' ? 'checked' : '' }}>
                        <label for="tidak_sesuai" class="form-check-label">Tidak Sesuai</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="penambahan_modal" class="form-label">2. Penambahan penyertaan modal di tahun
                        pembukuan?</label>
                    <div>
                        <input type="radio" name="penambahan_modal" value="Ada" id="ada" class="form-check-input"
                            {{ $lpj->penambahan_modal == 'Ada' ? 'checked' : '' }} required>
                        <label for="ada" class="form-check-label">Ada</label>
                    </div>
                    <div>
                        <input type="radio" name="penambahan_modal" value="Tidak Ada" id="tidak_ada"
                            class="form-check-input" {{ $lpj->penambahan_modal == 'Tidak Ada' ? 'checked' : '' }}>
                        <label for="tidak_ada" class="form-check-label">Tidak Ada</label>
                    </div>
                </div> --}}

                <div class="mb-3">
                    <!-- Input Hidden untuk Trix Editor Misi -->
                    <label for="hasil_capaian" class="form-label">Gambarkan hasil capaian selama satu tahun!</label>
                    <input id="hasil_capaian" type="hidden" name="hasil_capaian"
                        value="{{ old('hasil_capaian', $lpj->hasil_capaian ?? '') }}">
                    <trix-editor input="hasil_capaian" class="@error('hasil_capaian') is-invalid @enderror"></trix-editor>
                    @error('hasil_capaian')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    {{-- <textarea name="hasil_capaian" id="hasil_capaian" rows="3" class="form-control">{{ $lpj->hasil_capaian }}</textarea> --}}
                </div>

                <h3 class="card-title">Laporan Direktur</h3>

                <div class="mb-3">
                    <label for="kebijakan_strategi" class="form-label">4. Kebijakan dan strategi yang telah dilakukan
                        manajemen</label>
                    <input id="kebijakan_strategi" type="hidden" name="kebijakan_strategi"
                        value="{{ old('kebijakan_strategi', $lpj->kebijakan_strategi ?? '') }}">
                    <trix-editor input="kebijakan_strategi"
                        class="@error('kebijakan_strategi') is-invalid @enderror"></trix-editor>
                    @error('kebijakan_strategi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tantangan_hambatan" class="form-label">5. Tantangan dan hambatan yang dihadapi</label>
                    <input id="tantangan_hambatan" type="hidden" name="tantangan_hambatan"
                        value="{{ old('tantangan_hambatan', $lpj->tantangan_hambatan ?? '') }}">
                    <trix-editor input="tantangan_hambatan"
                        class="@error('tantangan_hambatan') is-invalid @enderror"></trix-editor>
                    @error('tantangan_hambatan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="apresiasi" class="form-label">6. Penyampaian apresiasi</label>
                    <input id="apresiasi" type="hidden" name="apresiasi"
                        value="{{ old('apresiasi', $lpj->apresiasi ?? '') }}">
                    <trix-editor input="apresiasi" class="@error('apresiasi') is-invalid @enderror"></trix-editor>
                    @error('apresiasi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <h3 class="card-title">Laporan Pengawas</h3>

                <div class="mb-3">
                    <label for="tugas_pengawasan" class="form-label">7. Tugas Pengawasan yang telah dilakukan</label>
                    <input id="tugas_pengawasan" type="hidden" name="tugas_pengawasan"
                        value="{{ old('tugas_pengawasan', $lpj->tugas_pengawasan ?? '') }}">
                    <trix-editor input="tugas_pengawasan"
                        class="@error('tugas_pengawasan') is-invalid @enderror"></trix-editor>
                    @error('tugas_pengawasan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pandangan_pengawas" class="form-label">8. Pandangan Pengawas atas realisasi program
                        kerja</label>
                    <input id="pandangan_pengawas" type="hidden" name="pandangan_pengawas"
                        value="{{ old('pandangan_pengawas', $lpj->pandangan_pengawas ?? '') }}">
                    <trix-editor input="pandangan_pengawas"
                        class="@error('pandangan_pengawas') is-invalid @enderror"></trix-editor>
                    @error('pandangan_pengawas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="catatan_pengawas" class="form-label">9. Catatan dari pengawas</label>
                    <input id="catatan_pengawas" type="hidden" name="catatan_pengawas"
                        value="{{ old('catatan_pengawas', $lpj->catatan_pengawas ?? '') }}">
                    <trix-editor input="catatan_pengawas"
                        class="@error('catatan_pengawas') is-invalid @enderror"></trix-editor>
                    @error('catatan_pengawas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rekomendasi_pengawas" class="form-label">10. Rekomendasi pengawas</label>
                    <input id="rekomendasi_pengawas" type="hidden" name="rekomendasi_pengawas"
                        value="{{ old('rekomendasi_pengawas', $lpj->rekomendasi_pengawas ?? '') }}">
                    <trix-editor input="rekomendasi_pengawas"
                        class="@error('rekomendasi_pengawas') is-invalid @enderror"></trix-editor>
                    @error('rekomendasi_pengawas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <h3 class="card-title">Laporan Kinerja</h3>

                <div class="mb-3">
                    <label for="hasil_kinerja" class="form-label">11. Interpretasikan hasil Kinerja masing-masing unit
                        usaha</label>
                    <input id="hasil_kinerja" type="hidden" name="hasil_kinerja"
                        value="{{ old('hasil_kinerja', $lpj->hasil_kinerja ?? '') }}">
                    <trix-editor input="hasil_kinerja" class="@error('hasil_kinerja') is-invalid @enderror"></trix-editor>
                    @error('hasil_kinerja')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="permasalahan_usaha" class="form-label">12. Permasalahan yang mempengaruhi usaha</label>
                    <input id="permasalahan_usaha" type="hidden" name="permasalahan_usaha"
                        value="{{ old('permasalahan_usaha', $lpj->permasalahan_usaha ?? '') }}">
                    <trix-editor input="permasalahan_usaha"
                        class="@error('permasalahan_usaha') is-invalid @enderror"></trix-editor>
                    @error('permasalahan_usaha')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">

                    <label for="potensi_peluang" class="form-label">13. Potensi, Peluang dan Prospek Usaha</label>
                    <input id="potensi_peluang" type="hidden" name="potensi_peluang"
                        value="{{ old('potensi_peluang', $lpj->potensi_peluang ?? '') }}">
                    <trix-editor input="potensi_peluang"
                        class="@error('potensi_peluang') is-invalid @enderror"></trix-editor>
                    @error('potensi_peluang')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="strategi_kebijakan" class="form-label">14. Strategi dan Kebijakan tahun berikutnya</label>
                    <input id="strategi_kebijakan" type="hidden" name="strategi_kebijakan"
                        value="{{ old('strategi_kebijakan', $lpj->strategi_kebijakan ?? '') }}">
                    <trix-editor input="strategi_kebijakan"
                        class="@error('strategi_kebijakan') is-invalid @enderror"></trix-editor>
                    @error('strategi_kebijakan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <style>
                    .selanjutnya {
                        width: 100%;
                        padding: 15px;
                        border-radius: 10px;
                        position: sticky;
                        bottom: 0;
                        background-color: white;
                    }

                    .back-to-top {
                        display: none !important;
                    }
                </style>




                <div class="selanjutnya text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a target="_blank" href="/cetak/lpj" class="btn btn-danger">Cetak</a>
                </div>

            </form>
        </div>
    </div>
@endsection
