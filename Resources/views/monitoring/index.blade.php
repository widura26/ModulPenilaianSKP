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
                    <table id="table-monitoring" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nip</th>
                                <th>Nama</th>
                                <th>Status SKP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@push('js')
    @include('penilaian::monitoring.script')
@endpush
