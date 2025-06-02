@extends('adminlte::page')

@section('title', 'Dasbor Simlitabmas')

@section('content_header')
    <h1 class="m-0 text-dark">Capaian & Kurva Kinerja Organisasi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="bg-white p-4">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success">Refresh</button>
                    </div>
                    <div class="">
                        <form class="form-inline">
                            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                              <option selected>Silahkan Pilih</option>
                              <option value="1">Istimewa</option>
                              <option value="2">Baik</option>
                              <option value="3">Butuh Perbaikan</option>
                              <option value="3">Kurang</option>
                              <option value="3">Sangat Kurang</option>
                            </select>

                            <button type="submit" class="btn btn-primary my-1">Simpan</button>
                        </form>
                    </div>
                    <div class="border p-4" style="width: 50%;">
                        <img src="{{ asset('modules/penilaian/images/baik.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                    </div>
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
<script>

</script>
@endpush
