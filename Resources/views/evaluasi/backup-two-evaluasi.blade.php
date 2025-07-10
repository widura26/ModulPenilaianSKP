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
                    {{-- <div class="d-flex justify-content-end mb-2">
                        <select name="" class="form-control" id="" style="width: 200px;">
                            <option value="">-- Filter Status --</option>
                            <option value="">Belum Ajukan Realisasi</option>
                            <option value="">Belum Dievaluasi</option>
                            <option value="">Sudah Dievaluasi</option>
                        </select>
                        <select name="" class="form-control ml-2" id="" style="width: 200px;">
                            <option value="">-- Filter Predikat --</option>
                            <option value="">Sangat Kurang</option>
                            <option value="">Butuh Perbaikan</option>
                            <option value="">Kurang</option>
                            <option value="">Baik</option>
                            <option value="">Sangat Baik</option>
                        </select>
                    </div>
                    <table id="table-pegawai" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Predikat Kinerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table> --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Periode Evaluasi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periodes as $index => $item)
                                <tr>
                                    <th>{{ $index + 1 }}.</th>
                                    <td style="width: 65%;">Evaluasi {{ $item->jenis_periode }}</td>
                                    <td>
                                        <div class="d-flex" style="gap: 4px;">
                                            <button class="btn btn-primary" onclick="window.location.href='{{ url('/skp/realisasi/' . str_replace(' ', '-', $item->jenis_periode)) }}'">Isi Realisasi</button>
                                            <button class="btn btn-primary" onclick="window.location.href='{{ url('/skp/evaluasi/periode/' . $item->id) }}'">Evaluasi Pegawai</button>
                                        </div>
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
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop

@push('js')
    @include('penilaian::evaluasi.script-evaluasi')
    @include('penilaian::evaluasi.script-periode')
@endpush
