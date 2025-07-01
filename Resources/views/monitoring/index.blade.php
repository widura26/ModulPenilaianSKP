@extends('adminlte::page')

@section('title', 'Arsip SKP')

@section('content_header')
    <h1 class="m-0 text-dark">Monitoring</h1>
@stop

@php
    $role = Auth::user()->role_aktif;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="grid">
                        <div class="">
                            <div class="card m-0">
                                <div class="card-body">
                                <h5 class="card-title">Card 1</h5>
                                <p class="card-text">Rencana SKP</p>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card m-0">
                                <div class="card-body">
                                <h5 class="card-title">Card 1</h5>
                                <p class="card-text">Matriks Peran Hasil</p>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card m-0">
                                <div class="card-body">
                                <h5 class="card-title">Card 1</h5>
                                <p class="card-text">Log Harian & Capaian</p>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card m-0">
                                <div class="card-body">
                                <h5 class="card-title">Card 1</h5>
                                <p class="card-text">Statistik Bulanan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 16px;
            /* padding: 20px; */
        }
    </style>
@stop

@push('js')
    @include('penilaian::monitoring.script')
@endpush
