@extends('adminlte::page')

@section('title', 'Intervensi')

@section('content_header')
<h1 class="m-0 text-dark">Intervensi</h1>
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
                <!-- <form>
                    <div class="form-group">
                        <label for="peran-select">Peran</label>
                        <select class="form-control" id="peran-select" name="peran">
                            <option value="">-- Pilih Peran --</option>
                            @foreach ($pegawai->timKerjaAnggota as $item)
                            <option value="{{ $item->pivot->peran == 'Ketua' ? ($item->parentUnit->id ?? $item->id) : $item->id  }}">
                                {{ $item->pivot->peran }} {{ $item->unit->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Sasaran yang diintervensi :</label>
                        <input type="text" class="form-control col-8" id="deskripsi" aria-describedby="text">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form> -->
                <form action="{{ url('/skp/intervensi/store-intervensi/' . (is_null($rencana) ? '' : $rencana->id)) }}" method="POST">
                    @csrf
                    <div class="form-group col-8">
                        <label for="peran-select">Peran</label>
                        <select class="form-control" id="peran-select" name="rencana_id">
                            <option value="">-- Pilih Peran --</option>
                            @foreach ($pegawai->timKerjaAnggota as $item)
                            <option value="{{ $item->pivot->peran == 'Ketua' ? ($item->parentUnit->id ?? $item->id) : $item->id  }}">
                                {{ $item->pivot->peran }} {{ $item->unit->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-8">
                        <label for="deskripsi">Sasaran yang diintervensi :</label>
                        <input type="text" class="form-control " id="deskripsi" name="deskripsi" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>

    </div>
</div>
@stop

@section('css')
<!-- <style>
    textarea {
        border-color: #ced4da;
    }

    textarea:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style> -->
@stop

@push('js')
@include('penilaian::evaluasi.script-periode')
@endpush