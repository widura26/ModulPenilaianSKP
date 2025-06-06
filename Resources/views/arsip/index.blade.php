@extends('adminlte::page')

@section('title', 'Arsip SKP')

@section('content_header')
    <h1 class="m-0 text-dark">Arsip SKP</h1>
@stop
@php
    $role = Auth::user()->role_aktif;
@endphp
@section('content')
    @if ($role == !in_array($role, ['admin']))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @include('penilaian::components.set-periode')
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if ($errors->any())
                    <div id="alert-failed" class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('failed') || session('error'))
                    <div id="alert-failed" class="p-2">
                        <div class="alert alert-danger">
                            {{ session('failed') ?? session('error') }}
                        </div>
                    </div>
                @elseif(session('success'))
                    <div class="p-2" id="alert-passed">
                        <div id="alert-passed" class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    @if ($role == 'admin')
                        @include('penilaian::arsip.partials.arsip-admin')
                    @else
                        @include('penilaian::arsip.components.modal-create-arsip')
                        <table id="" class="table table-striped table-bordered mt-4" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Arsip</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($arsips as $index => $arsip)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $arsip->jenis_arsip }}</td>
                                        <td>{{ \Carbon\Carbon::parse($arsip->periode->start_date)->translatedFormat('j F Y') }} - {{ \Carbon\Carbon::parse($arsip->periode->end_date)->translatedFormat('j F Y') }}</td>
                                        <td>
                                            @include('penilaian::arsip.components.modal-update-arsip')
                                            @include('penilaian::arsip.components.modal-delete-arsip')
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@push('js')
    @include('penilaian::arsip.script')
@endpush
