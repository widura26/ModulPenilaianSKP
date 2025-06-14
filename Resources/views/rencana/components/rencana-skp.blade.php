@extends('adminlte::page')

@section('title', 'SKP Poliwangi')

@section('content_header')
<h1 class="m-0 text-dark">Sasaran Kinerja</h1>
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
            switch (true) {
            case 'Belum Dievaluasi':
            $badgeClass = 'badge-secondary';
            break;
            case 'Belum Ajukan Realisasi':
            $badgeClass = 'badge-danger';
            break;
            case 'Sudah Dievaluasi':
            $badgeClass = 'badge-success';
            break;
            default:
            $badgeClass = 'badge-secondary';
            break;
            }

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
                <!-- <div class="d-flex justify-content-end p-2 border-bottom">
          <a href="#" class="btn btn-primary">Buat SKP</a>
        </div> -->

                <!-- <div class="d-flex justify-content-end p-2 border-bottom align-items-center gap-2" id="skp-container">
                    <button id="skp-button" class="btn btn-primary">Buat SKP</button>
                    <button id="skp-button" class="btn btn-danger">Reset SKP</button>
                </div> -->
                <div class="d-flex justify-content-end p-2 border-bottom align-items-center gap-2" id="skp-container">
                    <button id="buat-skp" class="btn btn-primary">Buat SKP</button>
                    <button id="reset-skp" class="btn btn-danger" style="display: none;">Reset SKP</button>
                </div>


                @include('penilaian::components.atasan-bawahan-section', ['pegawai' => $pegawai])

                <div class="mt-3">

                    <table class="table  mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="5">Hasil Kerja</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="width: 90%">A. Utama</th>
                                <th colspan="1" style="width: 10%">
                                    <!-- @if (!is_null($rencana)) -->
                                    @include('perencanaan::rencana.modal-create-rencana')
                                    <!-- @endif -->
                                </th>
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
                                <td class="col-sm-2">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-right"></td>
                                <td class="col-sm-7 border-right" scope="row">
                                    <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="border-right"></td>
                                <td class="col-sm-7 border-right" scope="row">
                                    @foreach ($item->indikator as $indikator)
                                    <li>{{ $indikator['deskripsi'] }}</li>
                                    @endforeach
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                    <table class="table  mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right">B. Tambahan</th>
                                <th colspan="1" class="col-sm-2">
                                    <!-- @if (!is_null($rencana)) -->
                                    @include('penilaian::components.modal-create-hasil-kerja-tambahan')
                                    <!-- @endif -->
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @if ($hasilKerjaTambahan->count())
                            @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
                            <tr>
                                <th style="width: 0%;" scope="row">{{ $indexTambahan + 1 }}</th>
                                <td class="col-sm-7 border-right">
                                    <p>{{ $item['deskripsi'] }}</p>
                                </td>
                                <td class="col-sm-2">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="col-sm-7 border-right"><span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span></td>
                                <td></td>
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
                                <td class="col-sm-2"></td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            @endif
                        </tbody>
                        </tbody>
                    </table>
                    <table class="table  mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right">C. Lampiran</th>
                                <th colspan="1" class="col-sm-2">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Dukungan Sumber Daya</th>
                                <th colspan="1" class="col-sm-2">
                                    <!-- @if (!is_null($rencana)) -->
                                    @include('perencanaan::rencana.modal-create-dukungan-sumber-daya')
                                    <!-- @endif -->
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Skema Pertanggung Jawaban</th>
                                <th colspan="1" class="col-sm-2">
                                    @include('perencanaan::rencana.modal-create-skema-pertanggung-jawaban')
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Konsekuensi</th>
                                <th colspan="1" class="col-sm-2">
                                    @include('perencanaan::rencana.modal-create-konsekuensi')
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                        </tbody>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/assets/css/admin_custom.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buatBtn = document.getElementById('buat-skp');
        const resetBtn = document.getElementById('reset-skp');

        buatBtn.addEventListener('click', function() {
            buatBtn.style.display = 'none';
            resetBtn.style.display = 'inline-block';
        });

        resetBtn.addEventListener('click', function() {
            resetBtn.style.display = 'none';
            buatBtn.style.display = 'inline-block';
        });
    });
</script>

@endpush