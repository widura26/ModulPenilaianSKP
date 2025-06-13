<table class="table mb-0" style="table-layout: auto; width: 100%;">
    <thead>
        <tr>
        <th colspan="4">HASIL KERJA</th>
        </tr>
        <tr>
        <th colspan="4">A. Utama</th>
        </tr>
    </thead>
    <tbody>
        @if ($rencana && $rencana->hasilKerja)
            @foreach ($rencana->hasilKerja as $index => $item)
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
                            <input type="hidden" name="feedback[{{ $index }}][hasil_kerja_id]" value="{{ $item->id }}">

                            <select class="custom-select feedback-template" id="umpan_bali_id" name="feedback[{{ $index }}][umpan_balik_predikat]">
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
                        @include('penilaian::components.modal-template-umpanbalik-hasilkerja')
                        <textarea class="feedback-text form-control mt-2 {{ $predikat !== null && $deskripsi === null ? 'd-none' : '' }}"
                            {{ $predikat !== null && $deskripsi !== null ? 'disabled' : '' }}
                            name="feedback[{{ $index }}][umpan_balik_deskripsi]" required
                            placeholder="{{ $predikat !== null && $deskripsi !== null ? $deskripsi : '' }}"
                            style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                        @error('feedback.' . $index . '.umpan_balik_deskripsi')
                            <small class="text-danger">Deskripsi wajib diisi</small>
                        @enderror
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
