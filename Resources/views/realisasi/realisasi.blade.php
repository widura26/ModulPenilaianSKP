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
                        switch ($rencana?->pengajuanRealisasiPeriodik?->status) {
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
                            case null :
                                $badgeClass = 'badge-danger';
                                $label = 'Belum Diajukan';
                                break;
                        }

                        $semuaRealisasiSudahDiisi  = $rencana->hasilKerja->every(function ($item) {
                            $realisasi = $item->realisasi;
                            return !is_null($realisasi);
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

                        $adaRealisasiUtama = $hasilKerjaUtama->contains(function ($item) {
                            return !is_null($item->realisasiPeriodik?->realisasi) && $item->realisasiPeriodik?->realisasi !== '';
                        });

                        $adaRealisasiTambahan = $hasilKerjaTambahan->contains(function ($item) {
                            return !is_null($item->realisasiPeriodik?->realisasi) && $item->realisasiPeriodik?->realisasi !== '';
                        });

                        $realisasiPeriodik = $rencana->hasilKerja->contains(function ($item) use ($periode) {
                            return $item->realisasiPeriodik?->periode_id;
                        });

                        $evaluasi = $rencana->evaluasiPeriodik->first();
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
                        @if ($rencana?->pengajuanRealisasiPeriodik?->status == null)
                            @include('penilaian::realisasi.components.modals-pengajuan-realisasi')
                        @elseif($rencana?->pengajuanRealisasiPeriodik?->status == 'Belum Dievaluasi')
                            @include('penilaian::realisasi.components.modals-batalkan-realisasi')
                        @endif

                        @if ($evaluasi?->predikat !== null)
                            <div class="d-flex">
                                {{-- @include('penilaian::components.modal-cetak-evaluasi')
                                @include('penilaian::components.modal-cetak-dokevaluasi') --}}
                                <button class="btn btn-primary" onclick="window.location.href='{{ url('/skp/preview/backup-evaluasi') }}'">Cetak Evaluasi</button>
                                <button class="btn btn-primary ml-2" onclick="window.location.href='{{ url('/skp/preview/backup-dok-evaluasi?periode=' . $periode->id) }}'">Cetak Dok. Evaluasi</button>
                            </div>
                        @endif
                    </div>

                    @include('penilaian::components.atasan-bawahan-section')

                    <div class="bg-white p-4">
                        <table class="table mb-0" style="width: 100%;">
                            <thead>
                            <tr>
                                <th colspan="5" class="border-left border-right">HASIL KERJA</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="border-left border-right">A. Utama</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if ($hasilKerjaUtama->count())
                                    @foreach ($hasilKerjaUtama as $index => $item)
                                        <tr>
                                            <th  class="border-left border-right" scope="row">{{ $index + 1 }}.</th>
                                            <td style="width: 50%;" class="border-right">
                                                <p>{{ $item['deskripsi'] }} {{ $item['id'] }}</p>
                                                <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                                <ul>
                                                    @foreach ($item->indikator as $indikator)
                                                        <li>{{ $indikator['deskripsi'] }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td style="width: 20%;" class="border-right">
                                                <span>Realisasi :</span>
                                                <p>{{ $item->realisasiPeriodik?->realisasi }}</p>
                                                @if ($item->realisasiPeriodik?->bukti_dukung !== null)
                                                    <a href="{{ $item->realisasiPeriodik?->bukti_dukung }}" target="_blank" class="btn btn-primary">
                                                        <i class="bi bi-file-arrow-up"></i>Bukti Dukung
                                                    </a>
                                                @endif
                                            </td>
                                            <td style="width: 20%;" class="border-right">
                                                <span>Umpan Balik :</span>
                                                <p>{{ $item->penilaian->first()?->umpan_balik_predikat }}</p>
                                            </td>
                                            <td style="width: 10%;" class="border-right">
                                                @if ((($item->penilaian->first() == null) && ($evaluasi?->predikat == null)) && $rencana?->pengajuanRealisasiPeriodik?->status !== 'Belum Dievaluasi' )
                                                    <div class="d-flex" style="gap: 5px;">
                                                        @include('penilaian::realisasi.components.modals-create-realisasi')
                                                        @include('penilaian::realisasi.components.modals-delete-realisasi')
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <table class="table mb-0" style="width: 100%;">
                            <thead>
                            <tr>
                                <th colspan="5" class="border-left border-right">B. Tambahan</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tbody>
                                    @if ($hasilKerjaTambahan->count())
                                        @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
                                            <tr>
                                                <th scope="row" class="border">{{ $indexTambahan + 1 }}.</th>
                                                <td style="width: 50%;" class="border-right border-bottom">
                                                    <p>{{ $item['deskripsi'] }}</p>
                                                    <span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span>
                                                    <ul>
                                                        @foreach ($item->indikator as $indikator)
                                                            <li>{{ $indikator['deskripsi'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td style="width: 20%;" class="border-right border-bottom">
                                                    <span>Realisasi :</span>
                                                    <p>{{ $item->realisasiPeriodik?->realisasi }}</p>
                                                    @if ( $item->realisasiPeriodik?->bukti_dukung !== null)
                                                        <a href="{{ $item->realisasiPeriodik?->bukti_dukung }}" target="_blank" class="btn btn-primary">
                                                            <i class="bi bi-file-arrow-up"></i>Bukti Dukung
                                                        </a>
                                                    @endif
                                                </td>
                                                <td style="width: 20%;" class="border-right border-bottom">
                                                    <span>Umpan Balik :</span>
                                                    <p>{{ $item->penilaian->first()?->umpan_balik_predikat }}</p>
                                                </td>
                                                <td style="width: 10%;" class="border-right border-bottom">
                                                    @if (($item->penilaian->first() == null) && ($evaluasi?->predikat == null))
                                                        <div class="d-flex" style="gap: 5px;">
                                                            @include('penilaian::realisasi.components.modals-create-realisasi')
                                                            <form action="{{ url('/skp/realisasi/delete/' . $item->id) }}" method="POST">
                                                                @csrf
                                                                <button {{ in_array($item->rencanakerja->status_realisasi, ['Sudah Dievaluasi', 'Belum Dievaluasi']) ? 'disabled' : '' }} type="submit" class="btn btn-danger" data-toggle="modal" data-target="#realisasi">
                                                                    <i class="nav-icon fas fa-ban "></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
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
    @include('penilaian::realisasi.scripts.script-realisasi')
@endpush
