<div class="mt-4">
    <form class="needs-validation" novalidate method="POST" action="{{ url('/skp/evaluasi/simpan-hasil-evaluasi/' . $pegawai->id) }}">
        @csrf
        <table class="table mb-0" style="table-layout: fixed; width: 100%;">
            <thead>
              <tr>
                <th colspan="2">EVALUASI HASIL KERJA</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Rekomendasi</td>
                <td><input id="rekomendasi-rating-hasil-kerja" value="{{ $hasiKerjaRecommendation }}" type="text" class="form-control" disabled></td>
              </tr>
              <tr>
                <td>Rating Hasil Kerja</td>
                <td>
                    <select class="custom-select" id="rating-hasil-kerja-select" name="rating_hasil_kerja">
                        @include('penilaian::components.predikat-dropdown', ['jenis' => 'Rating Hasil Kerja'])
                    </select>
                    <textarea
                    name="deskripsi_rating_hasil_kerja" required
                    class="form-control d-none mt-2" id="textarea-rating-hasil-kerja" style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                    <div class="invalid-feedback">Deskripsi tidak boleh kosong</div>
                </td>
              </tr>
            </tbody>
        </table>
        <table class="table" style="table-layout: fixed; width: 100%;">
            <thead>
              <tr>
                <th colspan="2">EVALUASI PERILAKU</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Rekomendasi</td>
                <td><input value="{{ $perilakuRecommendation }}" id="rekomendasi-rating-perilaku" type="text" class="form-control" disabled></td>
              </tr>
              <tr>
                <td>Rating Perilaku</td>
                <td>
                    <select class="custom-select" id="rating-perilaku-select" name="rating_perilaku">
                        @include('penilaian::components.predikat-dropdown', ['jenis' => 'Rating Perilaku'])
                    </select>
                    <textarea
                    name="deskripsi_rating_perilaku" required
                    class="form-control d-none mt-2 border border-2" id="textarea-rating-perilaku" style="height: 150px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                    <div class="invalid-feedback">Deskripsi tidak boleh kosong</div>
                </td>
              </tr>
            </tbody>
        </table>
        <table class="table" style="table-layout: fixed; width: 100%;">
            <tbody>
              <tr>
                <td>Predikat Kinerja Pegawai</td>
                <td>{{ $rencana->predikat_akhir }}</td>
              </tr>
            </tbody>
        </table>
        @if ($rencana->predikat_akhir == null)
            <div class="w-100 mt-4 d-flex justify-content-end">
                <button id="proses-umpan-balik-button" class="btn btn-primary mr-1">Ubah Umpan Balik</button>
                <button type="submit" id="proses-umpan-balik-button" class="btn btn-primary ml-1">Simpan Hasil Evaluasi</button>
            </div>
        @endif
    </form>
</div>
