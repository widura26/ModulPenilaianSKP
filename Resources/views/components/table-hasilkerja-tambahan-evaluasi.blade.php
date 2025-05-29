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
            @foreach ($suratTugas as $index => $item)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td style="width: 75%;">
                        {{ $item->detail->kegiatan_maksud }}
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
                                @if (!$predikat_hasiltugas || $predikat_hasiltugas === null)

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

                        <textarea
                        class="feedback-text mt-2 {{ $predikat_hasiltugas !== null && $deskripsi_hasiltugas === null ? 'd-none' : '' }}"
                        {{ $predikat_hasiltugas !== null && $deskripsi_hasiltugas !== null ? 'disabled' : '' }}
                        name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_deskripsi]"
                        required
                        placeholder="{{ $predikat_hasiltugas !== null && $deskripsi_hasiltugas !== null ? $deskripsi_hasiltugas : '' }}"
                        style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
