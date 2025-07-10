@extends('adminlte::page')

@section('title', 'Evaluasi | Daftar Pegawai')

@section('content_header')
    <h1 class="m-0 text-dark">Evaluasi SKP</h1>
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
                <div class="card-body">
                    @include('penilaian::evaluasi.partials.table-evaluasi')
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop

@push('js')
    @include('penilaian::evaluasi.script-evaluasi')
    @include('penilaian::evaluasi.script-periode')
@endpush
