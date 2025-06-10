@extends('adminlte::page')

@section('title', 'Dasbor Simlitabmas')

@section('content_header')
    <h1 class="m-0 text-dark">Periode SKP</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="bg-white p-4">
                    <div class="d-flex justify-content-end">
                        @include('penilaian::components.modal-create-periode')
                    </div>
                    <table id="table-periode" class="mt-4 table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>Capaian Kinerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periodes as $index => $periode)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($periode->start_date)->translatedFormat('j F Y') }}
                                        - {{ \Carbon\Carbon::parse($periode->end_date)->translatedFormat('j F Y') }} ({{ $periode->jenis_periode }})
                                    </td>
                                    <td>
                                        {{ $periode->capaian_kinerja == null ? '-' : $periode->capaian_kinerja }}
                                    </td>
                                    <td>
                                        <button class="btn btn-warning" title="update" onclick="window.location.href='{{ url('/penilaian/periode/' . $periode->id) }}'">
                                            <i class="nav-icon fas fa-pencil-alt "></i>
                                        </button>
                                        <button class="btn btn-danger" title="delete">
                                            <i class="nav-icon fas fa-trash "></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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
@include('penilaian::evaluasi.script-periode')
@endpush
