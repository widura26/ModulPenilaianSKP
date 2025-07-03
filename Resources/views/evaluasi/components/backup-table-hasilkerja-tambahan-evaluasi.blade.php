<table class="table mb-0" style="table-layout: auto; width: 100%;">
    <thead>
        <tr>
        <th colspan="4">B. Tambahan</th>
        </tr>
    </thead>
    <tbody>
        @if (count($suratTugas) == null)
            <tr>
                <td colspan="4">No data</td>
            </tr>
        @else
            @foreach ($hasilKerjaTambahan as $index => $item)
                @if (isset($item->rencana_id))
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td style="width: 50%;">
                            <p>{{ $item->deskripsi }}</p>
                            <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                            <ul>
                                @foreach ($item->indikator as $indikator)
                                    <li>{{ $indikator->deskripsi }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="width: 25%;">
                            <span>Realisasi :</span>
                            <p>{{ $item->realisasi }}</p>
                            @if ($item->bukti_dukung !== null)
                                <button onclick="window.open('{{ $item->bukti_dukung }}', '_blank')" class="btn btn-primary">
                                    <i class="bi bi-file-arrow-up"></i>Bukti Dukung</button>
                            @endif
                        </td>
                        <td style="width: 25%;">
                            <span>Umpan Balik :</span>
                            <div class="input-group">
                                <input type="hidden" name="feedback_hasil_kerja_tambahan[{{ $index }}][hasil_kerja_id]" value="{{ $item->id }}">

                                <select class="custom-select feedback-template" id="umpan_bali_id" name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_predikat]">
                                    @php
                                        $predikatTerpilih = $item->penilaian[0]->umpan_balik_predikat ?? null;
                                        $hasilKerja = $item->penilaian[0] ?? null;
                                        $predikat = $hasilKerja?->umpan_balik_predikat;
                                        $deskripsi = $hasilKerja?->umpan_balik_deskripsi;
                                    @endphp
                                    @if (!$predikatTerpilih || $predikatTerpilih === null)
                                        @include('penilaian::components.predikat-dropdown', [ 'jenis' => 'Predikat', 'selected' => $predikatTerpilih ])
                                    @else
                                        <option value="{{ $predikatTerpilih }}" selected>
                                            {{ $predikatTerpilih }}
                                        </option>
                                    @endif
                                </select>
                                <div class="input-group-append">
                                    <button {{ $predikat !== null && $deskripsi !== null ? 'disabled' : '' }} class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#template-umpanbalik-hasilkerja-{{ $item->id }}">
                                        <i class="nav-icon fas fa-copy "></i>
                                    </button>
                                </div>
                            </div>
                            @error('feedback.' . $index . '.umpan_balik_predikat')
                                <small class="text-danger">Predikat wajib diisi</small>
                            @enderror
                            @include('penilaian::evaluasi.components.modal-template-umpanbalik-hasilkerja')
                            <textarea class="feedback-text form-control mt-2 {{ $predikat !== null && $deskripsi === null ? 'd-none' : '' }}"
                                {{ $rencana->proses_umpan_balik == True ? 'disabled' : '' }}
                                name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_deskripsi]" required
                                placeholder="{{ $predikat !== null && $deskripsi !== null ? $deskripsi : '' }}"
                                value={{ $deskripsi }}
                                style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                            @error('feedback.' . $index . '.umpan_balik_deskripsi')
                                <small class="text-danger">Deskripsi wajib diisi</small>
                            @enderror
                        </td>
                    </tr>
                @elseif (isset($item->nomor_surat))
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td style="width: 50%;">
                            {{ $item->detail->kegiatan_maksud }}
                        </td>
                        <td style="width: 25%;">
                        </td>
                        <td style="width: 25%;">
                            <span>Umpan Balik :</span>
                            <div class="input-group">
                                <input type="hidden" name="feedback_hasil_kerja_tambahan[{{ $index }}][surat_tugas_id]" value="{{ $item->id }}">
                                @php
                                    $penilaianSuratTugas = $item->detail->penilaian[0] ?? null;
                                    $predikat_hasiltugas = $penilaianSuratTugas?->umpan_balik_predikat;
                                    $deskripsi_hasiltugas = $penilaianSuratTugas?->umpan_balik_deskripsi;
                                @endphp
                                <select class="custom-select feedback-template" id="perilaku_kerja_id" name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_predikat]">
                                    @if ($rencana->proses_umpan_balik == false)

                                        @include('penilaian::components.predikat-dropdown', ['jenis' => 'Predikat', 'selected' => $predikat_hasiltugas ])
                                    @else
                                        <option value="{{ $predikat_hasiltugas }}" selected>
                                            {{ $predikat_hasiltugas }}
                                        </option>
                                    @endif
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="nav-icon fas fa-copy "></i>
                                    </button>
                                </div>
                            </div>
                            @error('feedback_hasil_kerja_tambahan.' . $index . '.umpan_balik_predikat')
                                <small class="text-danger">Predikat wajib diisi</small>
                            @enderror

                            <textarea
                            class="form-control feedback-text mt-2 {{ $predikat_hasiltugas !== null && $deskripsi_hasiltugas === null ? 'd-none' : '' }}"
                            {{ $rencana->proses_umpan_balik == True ? 'disabled' : '' }}
                            name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_deskripsi]"
                            required
                            placeholder="{{ $predikat_hasiltugas !== null && $deskripsi_hasiltugas !== null ? $deskripsi_hasiltugas : '' }}"
                            style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;">{{ $deskripsi_hasiltugas }}</textarea>
                            @error('feedback_hasil_kerja_tambahan.' . $index . '.umpan_balik_deskripsi')
                                <small class="text-danger">Deskripsi wajib diisi</small>
                            @enderror
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
