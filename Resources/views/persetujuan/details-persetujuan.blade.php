@extends('adminlte::page')

@section('title', 'SKP Poliwangi')

@section('content_header')
<h1 class="m-0 text-dark">Details Persetujuan SKP</h1>
@stop
@php
@endphp

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      @include('penilaian::components.set-periode')
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      @php
      $hasilKerjaUtama = collect();
      $hasilKerjaTambahan = collect();

      if (!is_null($rencana) && !is_null($rencana->hasilKerja)) {
      $hasilKerjaUtama = $rencana->hasilKerja->filter(function($item) {
      return $item->jenis === 'utama';
      })->values();

      $hasilKerjaTambahan = $rencana->hasilKerja->filter(function($item) {
      return $item->jenis === 'tambahan';
      })->values();
      }
      @endphp

      <div class="card-body">
        @include('penilaian::components.atasan-bawahan-section', ['pegawai' => $pegawai])
        <div class="bg-white p-4 ">
          {{-- TABEL HASIL KERJA UTAMA --}}
          <table class="table mb-0">
            <thead>
              <tr>
                <th colspan="3">Hasil Kerja</th>
              </tr>
              <tr>
                <th colspan="3" class="col-sm-10">A. Utama</th>
              </tr>
            </thead>
            <tbody>
              @if ($hasilKerjaUtama->count())
              @foreach ($hasilKerjaUtama as $index => $item)
              <tr class="bg-light">
                <td class="border-right align-top" style="width: 5%;" scope="row">{{ $index + 1 }}</td>
                <td class=" align-top" colspan="2">
                  {{ $item->deskripsi }}
                </td>
              </tr>
              <tr>
                <td class="border-right"></td>
                <td colspan="2">
                  <span>Ukuran keberhasilan / Indikator Kinerja Individu dan Target:</span>
                </td>
              </tr>
              @foreach ($item->indikator as $indikator)
              <tr>
                <td class="border-right"></td>
                <td colspan="2">
                  <li class="mb-0">{{ $indikator->deskripsi }}</li>
                </td>
              </tr>
              @endforeach
              @endforeach
              @else
              <tr>
                <td colspan="3" class="text-muted">-</td>
              </tr>
              @endif
            </tbody>
          </table>

          {{-- TABEL HASIL KERJA TAMBAHAN --}}
          <table class="table mb-0">
            <thead>
              <tr>
                <th colspan="3" class="col-sm-10 ">B. Tambahan</th>
              </tr>
            </thead>
            <tbody>
              @if ($hasilKerjaTambahan->count())
              @foreach ($hasilKerjaTambahan as $index => $item)
              <tr class="bg-light">
                <td class="border-right align-top" scope="row">{{ $index + 1 }}</td>
                <td class="border-right align-top" colspan="2">
                  {{ $item->deskripsi }}
                </td>
              </tr>
              <tr>
                <td class="border-right"></td>
                <td colspan="2">
                  <span>Ukuran keberhasilan / Indikator Kinerja Individu dan Target:</span>
                </td>
              </tr>
              @foreach ($item->indikator as $indikator)
              <tr>
                <td class="border-right"></td>
                <td colspan="2">
                  <li class="mb-0">{{ $indikator->deskripsi }}</li>
                </td>
              </tr>
              @endforeach
              @endforeach
              @else
              <tr>
                <td colspan="3" class="text-muted">-</td>
              </tr>
              @endif
            </tbody>
          </table>

          <!-- Table lampiran -->
          <table class="table mb-0">
            <thead>
              <tr>
                <th colspan="3" class="col-sm-10">C. Lampiran</th>
              </tr>
            </thead>
            <tbody>
              @php
              $jenisList = ['Dukungan Sumber Daya', 'Skema Pertanggung Jawaban', 'Konsekuensi'];
              @endphp

              @foreach ($jenisList as $jenis)
              <tr>
                <th colspan="3" class="bg-light">{{ $jenis }}</th>
              </tr>
              @php $nomor = 1; @endphp

              @foreach ($rencana->lampirans->where('jenis_lampiran', $jenis) as $lampiran)
              <tr>
                <td class="border-right text-center" style="width: 5%;">{{ $nomor++ }}</td>
                <td colspan="2">
                  <p class="mb-0">{{ $lampiran->deskripsi_lampiran ?? '-' }}</p>
                </td>
              </tr>
              @endforeach

              @if ($nomor === 1)
              <tr>
                <td colspan="3" class="text-muted">-</td>
              </tr>
              @endif
              @endforeach
            </tbody>
          </table>




          <!-- Table perilaku kerja -->
          <!-- <table class="table mb-0 mt-4 ">
            <thead>
              <tr>
                <th colspan="5">Perilaku Kerja</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($perilakuKerja as $index => $item)
              <tr>
                <th class="border-right" style="width: 0%;" scope="row">{{ $index + 1 }}</th>
                <td class="col-sm-7 border-right">
                  <p>{{ $item['deskripsi'] }}</p>
                  <ul scope="row">
                    <li>{{ $item['kriteria'] }}</li>
                  </ul>
                </td>
                <td>
                  <form action="#" method="post">
                    <div class="form-group">
                      <label for="exampleFormControlTextarea1">Ekspektasi Khusus Pimpinan :</label>
                      <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                  </form>
                </td>

              </tr>

              @endforeach
            </tbody>
            <tr><button type="button" class="btn btn-success">Success</button></tr>

          </table> -->
          <form action="{{ url('/skp/persetujuan/ekspektasi/' . $rencana->id) }}" method="POST">
            @csrf
            <table class="table ">
              <thead>
                <tr>
                  <th colspan="3">Perilaku Kerja</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($perilakuKerja as $index => $item)
                <tr>
                  <td class="border-right" style="width: 5%;">{{ $index + 1 }}</td>
                  <td>
                    <p><strong>{{ $item->deskripsi }}</strong></p>
                    <ul>
                      <li>{{ $item->kriteria }}</li>
                    </ul>
                  </td>
                  <td class="col-sm-4">
                    <div class="form-group ">
                      <label>Ekspektasi Khusus Pimpinan:</label>
                      <textarea
                        name="ekspektasi_pimpinan[{{ $item->id }}]"
                        class="form-control autosize"
                        rows="5"
                        oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                        style="overflow:hidden; resize:none;"
                        {{ isset($rencanaPerilaku[$item->id]) ? 'readonly' : '' }}>{{ old("ekspektasi_pimpinan.{$item->id}", $rencanaPerilaku[$item->id] ?? '') }}
                      </textarea>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ url('/skp/persetujuan') }}" class="btn btn-secondary">
                Batal
              </a>
              <button type="submit" class="btn btn-success">
                Simpan Ekspektasi
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('css')

@stop

@push('js')
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      responsive: true,
      autoWidth: false
    });
  });

</script>

@endpush