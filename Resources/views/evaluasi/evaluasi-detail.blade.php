@extends('adminlte::page')

@section('title', 'Detail Evaluasi')

@section('content_header')
    <h1 class="m-0 text-dark">Evaluasi SKP</h1>
@stop

@section('content')
    @php
        switch ($rencana->evaluasiPeriodik->isNotEmpty() && $rencana->evaluasiPeriodik[0]?->predikat) {
            case 'Sangat Baik':
            case 'Baik':
                $badgeClass = 'badge-success';
                break;
            case 'Butuh Perbaikan':
                $badgeClass = 'badge-danger';
                break;
            default:
                $badgeClass = 'badge-danger';
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
            })->values()->merge($suratTugas);
        }

        $evaluasi = $rencana->evaluasiPeriodik->first();
    @endphp

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
                <div class="w-100 justify-content-between align-items-center p-4
                    {{ $evaluasi && $evaluasi->predikat ? 'd-flex' : 'd-none' }}">
                    <span class="badge m-2 {{ $badgeClass }}" style="width: fit-content">{{ $evaluasi?->predikat }}</span>
                    @include('penilaian::evaluasi.components.modal-batalkan-evaluasi')
                </div>
                @include('penilaian::components.atasan-bawahan-section')
                <div class="bg-white p-4">
                    <form method="POST" action="{{ url('/skp/evaluasi/' . $periodeId .'/proses-umpan-balik/' . $pegawai->id) }}">
                        @csrf
                        @include('penilaian::evaluasi.components.backup-table-hasilkerja-utama-evaluasi')
                        @include('penilaian::evaluasi.components.backup-table-hasilkerja-tambahan-evaluasi')
                        @include('penilaian::evaluasi.components.backup-table-perilakukerja-evaluasi')
                        @php
                            $loggedInKetuaId = $pegawaiWhoLogin->id;
                            $semuaSudahTerisi = $rencana->hasilKerja->every(function ($hasil) use ($loggedInKetuaId) {
                                $penilaian = $hasil->penilaian->firstWhere('ketua_tim_id', $loggedInKetuaId);
                                return $penilaian && !is_null($penilaian->umpan_balik_predikat);
                            });
                        @endphp
                        {{-- @if (!$semuaSudahTerisi || $rencana->proses_umpan_balik == true)
                            <div class="w-100 mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Proses Umpan Balik</button>
                            </div>
                        @endif --}}
                        @if (!$semuaSudahTerisi)
                            <div class="w-100 mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Proses Umpan Balik</button>
                            </div>
                        @endif
                    </form>

                    {{-- @if($semuaSudahTerisi && count($rencana->hasilKerja) !== 0 && $rencana->proses_umpan_balik == false)
                        <div>@include('penilaian::evaluasi.components.proses-umpan-balik')</div>
                    @endif --}}
                    @if($semuaSudahTerisi && count($rencana->hasilKerja) !== 0)
                        <div>@include('penilaian::evaluasi.components.proses-umpan-balik')</div>
                    @endif
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
    @include('penilaian::evaluasi.script-evaluasi-detail')
    @include('penilaian::evaluasi.script-periode')
@endpush
