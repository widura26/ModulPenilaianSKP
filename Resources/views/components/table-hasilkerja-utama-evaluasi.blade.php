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
                        <p>{{ $item['realisasi'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <span>Umpan Balik :</span>
                        <div class="input-group">
                            <input type="hidden" name="feedback[{{ $index }}][hasil_kerja_id]" value="{{ $item->id }}">
                            <select class="custom-select" id="umpan_bali_id" name="feedback[{{ $index }}][umpan_balik_predikat]">
                                @php
                                    $predikatTerpilih = $item->penilaian[0]->umpan_balik_predikat ?? null;
                                @endphp
                                @include('penilaian::components.predikat-dropdown', [ 'jenis' => 'Predikat', 'selected' => $predikatTerpilih ])
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="nav-icon fas fa-copy "></i>
                                </button>
                            </div>
                        </div>
                        @php
                            $hasilKerja = $item->penilaian[0] ?? null;
                            $predikat = $hasilKerja?->umpan_balik_predikat;
                            $deskripsi = $hasilKerja?->umpan_balik_deskripsi;
                        @endphp

                        <textarea
                            class="mt-2 {{ $predikat !== null && $deskripsi === null ? 'd-none' : '' }}"
                            {{ $predikat !== null && $deskripsi !== null ? 'disabled' : '' }}
                            name="feedback[{{ $index }}][umpan_balik_deskripsi]"
                            required
                            placeholder="{{ $predikat !== null && $deskripsi !== null ? $deskripsi : '' }}"
                            style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
