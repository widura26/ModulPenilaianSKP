@extends('adminlte::page')

@section('title', 'Dasbor Simlitabmas')

@section('content_header')
    <h1 class="m-0 text-dark">Realisasi</h1>
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
                @if ($rencana == null)
                    <div class="mx-auto my-4">
                        <h1>Belum Membuat SKP</h1>
                    </div>
                @else
                    @php
                        switch ($rencana?->status_realisasi) {
                            case 'Sudah Diajukan':
                                $badgeClass = 'badge-success';
                                break;
                            case 'Belum Diajukan':
                                $badgeClass = 'badge-secondary';
                                break;
                            case 'Sudah Dievaluasi':
                                $badgeClass = 'badge-success';
                                break;
                        }
                        $semuaSudahTerisi = $rencana->hasilKerja->every(function ($item) {
                            $penilaian = $item->realisasi;
                            return !is_null($penilaian);
                        });
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

                    <div class="w-100 d-flex justify-content-between align-items-center p-2">
                        <span class="badge m-2 {{ $badgeClass }}" style="width: fit-content">{{ $rencana->status_realisasi }}</span>
                        @if ($rencana?->status_realisasi == 'Belum Diajukan')
                            <form method="POST" action="{{ url('/penilaian/realisasi/ajukan-realisasi/' . $rencana->id) }}">
                                @csrf
                                <button id="proses-umpan-balik-button" class="btn btn-primary" {{ !$semuaSudahTerisi ? 'disabled' : '' }}>Ajukan Realisasi</button>
                            </form>
                        @elseif($rencana?->status_realisasi == 'Sudah Diajukan')
                            <form method="POST" action="{{ url('/penilaian/realisasi/batalkan-realisasi/' . $rencana->id) }}">
                                @csrf
                                <button id="proses-umpan-balik-button" class="btn btn-danger">Batalkan Pengajuan</button>
                            </form>
                        @endif

                        @if ($rencana?->predikat_akhir !== null)
                            <div class="d-flex">
                                @include('penilaian::components.modal-cetak-evaluasi')
                                @include('penilaian::components.modal-cetak-dokevaluasi')
                            </div>
                        @endif
                    </div>

                    @include('penilaian::components.atasan-bawahan-section')

                    <div class="bg-white p-4">
                        {{-- Hasil kerja --}}
                        <table class="table mb-0" style="width: 100%;">
                            <thead>
                            <tr>
                                <th colspan="5">HASIL KERJA</th>
                            </tr>
                            <tr>
                                <th colspan="5">A. Utama</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if ($rencana && $rencana->hasilKerja)
                                    @foreach ($rencana->hasilKerja as $index => $item)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}.</th>
                                            <td style="width: 50%;">
                                                <p>{{ $item['deskripsi'] }}</p>
                                                <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                                <ul>
                                                    @foreach ($item->indikator as $indikator)
                                                        <li>{{ $indikator['deskripsi'] }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td style="width: 20%;">
                                                <span>Realisasi :</span>
                                                <p>{{ $item['realisasi'] }}</p>
                                            </td>
                                            <td style="width: 20%;">
                                                <span>Umpan Balik :</span>
                                                <p>{{ $item['umpan_balik_predikat'] }}</p>
                                            </td>
                                            <td style="width: 10%;">
                                                <div class="d-flex" style="gap: 5px;">
                                                    @include('penilaian::components.modals-create-realisasi')
                                                    <form action="{{ url('/penilaian/realisasi/delete/' . $item->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#realisasi">
                                                            <i class="nav-icon fas fa-ban "></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <table class="table mb-0" style="width: 100%;">
                            <thead>
                            <tr>
                                <th colspan="5">B. Tambahan</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="5">Not Found</td>
                                    </tr>
                                </tbody>
                            </tbody>
                        </table>
                    </div>
                @endif
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
    @include('penilaian::realisasi.script-realisasi')
@endpush
