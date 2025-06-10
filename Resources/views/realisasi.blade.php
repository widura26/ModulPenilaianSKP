@extends('adminlte::page')

@section('title', 'Realisasi')

@section('content_header')
    <h1 class="m-0 text-dark">Realisasi SKP</h1>
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
                            case 'Belum Dievaluasi':
                                $badgeClass = 'badge-primary';
                                $label = 'Sudah Diajukan';
                                break;
                            case 'Belum Ajukan Realisasi':
                                $badgeClass = 'badge-danger';
                                $label = 'Belum Diajukan';
                                break;
                            case 'Sudah Dievaluasi':
                                $badgeClass = 'badge-success';
                                $label = 'Sudah Dievaluasi';
                                break;
                        }

                        $realisasiKosong = $rencana->hasilKerja->every(function ($item) {
                            $realisasi = $item->realisasi;
                            return is_null($realisasi);
                        });

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

                    <div class="w-100 d-flex justify-content-between align-items-center p-2">
                        <span class="badge m-2 {{ $badgeClass }}" style="width: fit-content">{{ $label }}</span>
                        @if ($rencana?->status_realisasi == 'Belum Ajukan Realisasi')
                            <form method="POST" action="{{ url('/penilaian/realisasi/ajukan-realisasi/' . $rencana->id) }}">
                                @csrf
                                <button id="proses-umpan-balik-button" class="btn btn-primary" {{ $realisasiKosong || ($hasilKerjaUtama->isEmpty() && $hasilKerjaTambahan->isEmpty()) ? 'disabled' : '' }}>Ajukan Realisasi</button>
                            </form>
                        @elseif($rencana?->status_realisasi == 'Belum Dievaluasi')
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
                                @if ($hasilKerjaUtama->count())
                                    @foreach ($hasilKerjaUtama as $index => $item)
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
                                                @if ($item->bukti_dukung !== null)
                                                    <button onclick="window.open('{{ $item->bukti_dukung }}', '_blank')" class="btn btn-primary">
                                                        <i class="bi bi-file-arrow-up"></i>Bukti Dukung</button>
                                                @endif
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
                                                        <button {{ in_array($item->rencanakerja->status_realisasi, ['Sudah Dievaluasi', 'Belum Dievaluasi']) ? 'disabled' : '' }} type="submit" class="btn btn-danger" data-toggle="modal" data-target="#realisasi">
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
                                    @if ($hasilKerjaTambahan->count())
                                        @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
                                            <tr>
                                                <th scope="row">{{ $indexTambahan + 1 }}.</th>
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
                                                    @if ($item->bukti_dukung !== null)
                                                        <button onclick="window.open('{{ $item->bukti_dukung }}', '_blank')" class="btn btn-primary">
                                                            <i class="bi bi-file-arrow-up"></i>Bukti Dukung</button>
                                                    @endif
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
                                                            <button {{ in_array($item->rencanakerja->status_realisasi, ['Sudah Dievaluasi', 'Belum Dievaluasi']) ? 'disabled' : '' }} type="submit" class="btn btn-danger" data-toggle="modal" data-target="#realisasi">
                                                                <i class="nav-icon fas fa-ban "></i>
                                                            </button>
                                                        </form>
                                                    </div>
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
