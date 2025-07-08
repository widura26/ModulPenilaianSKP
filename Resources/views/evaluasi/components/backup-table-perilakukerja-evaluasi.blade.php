<table class="table mb-0" style="table-layout: auto; width: 100%;">
    <thead>
        <tr>
        <th colspan="4">PERILAKU KERJA</th>
        </tr>
    </thead>
    <tbody>
        @if ($rencana && $rencana->perilakuKerja)
            @foreach ($rencana->perilakuKerja as $index => $item)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td style="width: 50%;">
                        <p class="mb-0">
                            {{ $item->deskripsi }}
                        </p>
                        @php
                            $sentence = $item->kriteria;
                            $lists = array_filter(array_map('trim', explode(';', $sentence)));
                        @endphp
                        <ul>
                            @foreach ($lists as $list)
                                <li>{{ $list }}</li>
                            @endforeach
                        </ul>
                        @if ($item->deskripsi == 'Berorientasi Pelayanan')
                            <p class="mb-0 badge badge-primary">
                                Kehadiran sesuai ketentuan : {{ number_format($rekapKehadiran['rerata_kehadiran_sesuai_ketentuan'], 2) }}%
                            </p>
                            <p class="mb-0 badge badge-warning">
                                Kehadiran tidak sesuai ketentuan : {{ number_format($rekapKehadiran['rerata_kehadiran_tidak_sesuai_ketentuan'], 2) }}%
                            </p>
                            <p class="mb-0 badge badge-danger">
                                Alpa : {{ number_format($rekapKehadiran['rerata_alpa'], 2) }}%
                            </p>
                        @endif
                    </td>
                    <td style="width: 25%;">
                        <span>Ekspektasi Khusus Pimpinan:</span>
                        <p>{{ $item->ekspektasi_pimpinan }}</p>
                    </td>
                    <td style="width: 25%;">
                        <span>Umpan Balik :</span>
                        <div class="input-group">
                            <input type="hidden" name="feedback_perilaku_kerja[{{ $index }}][perilaku_kerja_id]" value="{{ $item->rencanaPerilaku->id }}">

                            <select class="custom-select feedback-perilaku-template" id="perilaku_kerja_id" name="feedback_perilaku_kerja[{{ $index }}][perilaku_umpan_balik_predikat]">
                                @php
                                    $penilaian = $item->rencanaPerilaku->penilaianPerilakuKerja[0] ?? null;
                                @endphp

                                @if ($rencana->proses_umpan_balik == false)
                                    @include('penilaian::components.predikat-dropdown', [
                                        'jenis' => 'Predikat',
                                        'options' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
                                        'selected' => $penilaian->umpan_balik_predikat ?? null
                                    ])
                                @else
                                    <option value="{{ $penilaian->umpan_balik_predikat ?? null }}" selected>
                                        {{ $penilaian->umpan_balik_predikat ?? null }}
                                    </option>
                                @endif
                            </select>
                            <div class="input-group-append">
                                <button {{ $rencana->proses_umpan_balik == true ? 'disabled' : '' }} class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#template-umpanbalik-perilaku-{{ $item->id }}">
                                    <i class="nav-icon fas fa-copy "></i>
                                </button>
                            </div>
                        </div>
                        @error('feedback_perilaku_kerja.' . $index . '.perilaku_umpan_balik_predikat')
                            <small class="text-danger">Predikat wajib diisi</small>
                        @enderror
                        @include('penilaian::evaluasi.components.modal-template-umpanbalik-perilaku')
                        <textarea
                            class="form-control feedback-perilaku-text mt-2 {{ ($penilaian && $penilaian->umpan_balik_predikat !== null && $penilaian->umpan_balik_deskripsi === null) ? 'd-none' : '' }}"
                            {{ $rencana->proses_umpan_balik == True ? 'disabled' : '' }}
                            name="feedback_perilaku_kerja[{{ $index }}][perilaku_umpan_balik_deskripsi]"
                            required
                            placeholder="{{ ($penilaian && $penilaian->umpan_balik_predikat !== null && $penilaian->umpan_balik_deskripsi !== null) ? $penilaian->umpan_balik_deskripsi : '' }}"
                            style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;">{{ $penilaian->umpan_balik_deskripsi ?? '' }}</textarea>

                        @error('feedback_perilaku_kerja.' . $index . '.perilaku_umpan_balik_deskripsi')
                            <small class="text-danger">Deskripsi wajib diisi</small>
                        @enderror
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
