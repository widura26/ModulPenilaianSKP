@extends('adminlte::page')

@section('title', 'Dasbor Simlitabmas')

@section('content_header')
    <h1 class="m-0 text-dark">Detail Arsip</h1>
@stop

@php
    switch ($arsipData->jenis_arsip) {
        case 'Rencana':
            $url = '/skp/arsip-skp/rencana/';
            break;
        case 'Evaluasi':
            $url = '/skp/arsip-skp/evaluasi/';
            break;
        case 'Dokumen Evaluasi':
            $url = '/skp/arsip-skp/dok-evaluasi/';
            break;
        default:
            break;
    }
@endphp
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="bg-white p-4">
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
                    <form method="POST" action="{{ url($url . 'verifikasi/' . $arsipData->id) }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama-pegawai">Nama Pegawai</label>
                                <input disabled value="{{ $arsipData->pegawai->nama }}" type="text" class="form-control" id="nama-pegawai">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jenis-arsip">Jenis Arsip</label>
                                <input disabled value="{{ $arsipData->jenis_arsip }}" type="text" class="form-control" id="jenis-arsip">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Belum Verifikasi" {{ $arsipData->status === 'Belum Verifikasi' ? 'selected' : '' }}>Belum Verifikasi</option>
                                    <option value="Sudah Verifikasi" {{ $arsipData->status === 'Sudah Verifikasi' ? 'selected' : '' }}>Sudah Verifikasi</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">status wajib dipilih</small>
                                @enderror
                            </div>

                            <div class="d-flex flex-column col-md-6">
                                <label for="status">File</label>
                                <div class="d-flex" style="gap: 4px;">
                                    <button class="btn btn-warning w-50" onclick="window.open('{{ asset('storage/' . $arsipData->file) }}', '_blank')">
                                        <i class="fas fa-eye"></i> Lihat Dokumen
                                    </button>
                                    {{-- <button class="btn btn-warning w-50" onclick="window.open('{{ asset('storage/' . $arsipData->file) }}', '_blank')">
                                        <i class="fas fa-file-download"></i> Download
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
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
