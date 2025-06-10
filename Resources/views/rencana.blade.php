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
                        <form method="POST" action="{{ url('/penilaian/rencana/store') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Buat SKP</button>
                        </form>
                    </div>
                @endif

                @include('penilaian::components.atasan-bawahan-section', ['pegawai' => $pegawai])
                <div class="bg-white p-4">
                    <table class="table mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="5">Hasil Kerja</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="width: 90%">A. Utama</th>
                                <th colspan="1" style="width: 10%">
                                    @if (!is_null($rencana))
                                        @include('penilaian::components.modal-create-hasil-kerja-utama')
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($hasilKerjaUtama->count())
                                @foreach ($hasilKerjaUtama as $index => $item)
                                    <tr>
                                        <th style="width: 0%;" scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <p>{{ $item['deskripsi'] }}</p>
                                            <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                            <ul>
                                                @foreach ($item->indikator as $indikator)
                                                    <li>{{ $indikator['deskripsi'] }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="width: 10%;">
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
                    <table class="table mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="2" style="width: 90%">A. Tambahan</th>
                                <th colspan="1" style="width: 10%">
                                    @if (!is_null($rencana))
                                        @include('penilaian::components.modal-create-hasil-kerja-tambahan')
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tbody>
                                @if ($hasilKerjaTambahan->count())
                                    @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
                                        <tr>
                                            <th style="width: 0%;" scope="row">{{ $indexTambahan + 1 }}</th>
                                            <td>
                                                <p>{{ $item['deskripsi'] }}</p>
                                                <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                                <ul>
                                                    @foreach ($item->indikator as $indikator)
                                                        <li>{{ $indikator['deskripsi'] }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td style="width: 10%;">
                                            </td>
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
    @include('penilaian::evaluasi.script-periode')
    @include('penilaian::evaluasi.script-hasilkerja')
@endpush
