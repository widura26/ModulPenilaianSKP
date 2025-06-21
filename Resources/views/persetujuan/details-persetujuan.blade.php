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
          <!-- Table hasil kerja utama -->
          <table class="table mb-0 table-bordered">
            <thead>
              <tr>
                <th colspan="5">Hasil Kerja</th>
              </tr>
              <tr>
                <th colspan="2" class="col-sm-7 border-right">A. Utama</th>

              </tr>
            </thead>
            <tbody>
              @if ($hasilKerjaUtama->count())
              @foreach ($hasilKerjaUtama as $index => $item)
              <tr>
                <th class="border-right" style="width: 0%;" scope="row">{{ $index + 1 }}</th>
                <td class="col-sm-7 border-right">
                  <p>{{ $item['deskripsi'] }}</p>
                </td>
              </tr>
              <tr>
                <td class="border-right"></td>
                <td class="col-sm-7 border-right" scope="row">
                  <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                </td>
              </tr>
              <tr>
                <td class="border-right"></td>
                <td class="col-sm-7 border-right" scope="row">
                  @foreach ($item->indikator as $indikator)
                  <li>{{ $indikator['deskripsi'] }}</li>
                  @endforeach
                </td>

              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="5">-</td>
              </tr>
              @endif
            </tbody>
          </table>
          <!-- Table hasil kerja tambahan -->
          <table class="table mb-0 table-bordered">
            <thead>
              <tr>
                <th colspan="2" class="col-sm-7 border-right">B. Tambahan</th>
                </th>
              </tr>
            </thead>
            <tbody>
              @if ($hasilKerjaTambahan->count())
              @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
              <tr>
                <th style="width: 0%;" scope="row">{{ $indexTambahan + 1 }}</th>
                <td class="col-sm-7 border-right">
                  <p>{{ $item['deskripsi'] }}</p>
                </td>
              </tr>
              <tr>
                <td></td>
                <td class="col-sm-7 border-right"><span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span></td>
              </tr>
              <tr>
                <td></td>
                <td class="col-sm-7 border-right">
                  <ul>
                    @foreach ($item->indikator as $indikator)
                    <li>{{ $indikator['deskripsi'] }}</li>
                    @endforeach
                  </ul>
                </td>
              </tr>
              @endforeach

              @else
              <tr>
                <td colspan="5">-</td>
              </tr>
              @endif
            </tbody>
          </table>
          <!-- Table lampiran -->
          <table class="table  mb-0 table-bordered">
            <thead>
              <tr>
                <th colspan="2" class="col-sm-7 border-right">C. Lampiran</th>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th colspan="2" class="col-sm-7 border-right" scope="row">Dukungan Sumber Daya</th>
                </th>
              </tr>
              <tr>
                <td colspan="5">-</td>
              </tr>
              <tr>
                <th colspan="2" class="col-sm-7 border-right" scope="row">Skema Pertanggung Jawaban</th>
                </th>
              </tr>
              <tr>
                <td colspan="5">-</td>
              </tr>
              <tr>
                <th colspan="2" class="col-sm-7 border-right" scope="row">Konsekuensi</th>
                </th>
              </tr>
              <tr>
                <td colspan="5">-</td>
              </tr>
            </tbody>
          </table>

          <!-- Table perilaku kerja -->
          <!-- <table class="table mb-0 mt-4 table-bordered">
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
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="3">Perilaku Kerja</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($perilakuKerja as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
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
            <button type="submit" class="btn btn-success">Simpan Ekspektasi</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/assets/css/admin_custom.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
@stop

@push('js')
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      responsive: true,
      autoWidth: false
    });
  });

  // const tdStatus = document.querySelector('#td-status')
  // console.log(tdStatus.innerText)
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
@endpush