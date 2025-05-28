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
                            <select class="custom-select" id="perilaku_kerja_id" name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_predikat]">
                                @include('penilaian::components.predikat-dropdown', ['jenis' => 'Predikat'])
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="nav-icon fas fa-copy "></i>
                                </button>
                            </div>
                        </div>
                        <textarea
                        class="mt-2"
                        name="feedback_hasil_kerja_tambahan[{{ $index }}][umpan_balik_deskripsi]"
                        placeholder="Halo"
                        style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
