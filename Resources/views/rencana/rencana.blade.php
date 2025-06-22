@extends('adminlte::page')

@section('title', 'Rencana')

@section('content_header')
<h1 class="m-0 text-dark">Rencana</h1>
@stop
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
                @if (session('failed'))
                <div id="alert-failed" class="p-2">
                    <div class="alert alert-danger">
                        {{ session('failed') }}
                    </div>
                </div>
                @elseif(session('success'))
                <div class="p-2" id="alert-passed">
                    <div id="alert-passed" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if (is_null($rencana))
                <div class="w-100 d-flex justify-content-end align-items-center p-4">
                    <form method="POST" action="{{ url('/skp/rencana/store') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Buat SKP</button>
                    </form>
                </div>
                @endif

                @include('penilaian::components.atasan-bawahan-section', ['pegawai' => $pegawai])
                <div class="bg-white mt-3">
                    <table class="table mb-0" style="width: 100%;">
                        

                        <thead>
                            <tr>
                                <th colspan="5">Hasil Kerja</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right">A. Utama</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-hasil-kerja-utama')
                                    @endif
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
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-edit-hasil-kerja-utama')
                                    @endif
                                    <!-- <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button> -->
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
                            @foreach ($item->indikator as $indikator)
                            <tr>
                                <td class="border-right"></td>
                                <td class="col-sm-7 border-right align-middle">
                                    <li class="mb-0">{{ $indikator['deskripsi'] }}</li>
                                </td>
                                <td class="align-middle">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-edit-indikator-hasil-kerja-utama')
                                    @endif
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-manual-indikator-utama', ['hasilKerja' => $item])
                                    @endif
                                    <!-- <button type="button" class="btn btn-success btn-sm" title="Edit Indikator">
                                        <i class="fas fa-pen">
                                        </i>
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-success btn-sm" title="Indikator Manual">
                                        <i class="fas fa-balance-scale"></i>
                                    </button> -->
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Indikator">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            <!-- <tr>
                                <td class="border-right"></td>
                                <td class="col-sm-7 border-right" scope="row">
                                    @foreach ($item->indikator as $indikator)
                                    <li>{{ $indikator['deskripsi'] }}</li>
                                    @endforeach
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-success btn-sm me-1">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr> -->
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right">B. Tambahan</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-hasil-kerja-tambahan')
                                    @endif
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
                            @foreach ($item->indikator as $indikator)
                            <tr>
                                <td class="border-right"></td>
                                <td class="col-sm-7 border-right align-middle">
                                    <li class="mb-0">{{ $indikator['deskripsi'] }}</li>
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-success btn-sm" title="Edit Indikator">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Indikator">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
                                <th colspan="2" class="col-sm-7 border-right">C. Lampiran</th>
                                <th colspan="1" class="col-sm-2">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Dukungan Sumber Daya</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-dukungan-sumber-daya')
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Skema Pertanggung Jawaban</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-skema-pertanggung-jawaban')
                                    @endif
                                </th>

                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="col-sm-7 border-right" scope="row">Konsekuensi</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-konsekuensi')
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
<style>
    textarea {
        border-color: #ced4da;
    }

    textarea:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
@stop

@push('js')
<!-- <script>
    window.definisiList = {
        !!json_encode($definisiOperasional - > toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!
    };
</script> -->


@include('penilaian::evaluasi.script-periode')
@include('penilaian::rencana.script.script-hasilkerja')
@endpush