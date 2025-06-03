@extends('adminlte::page')

@section('title', 'Capaian Kinerja Organisasi')

@section('content_header')
    <h1 class="m-0 text-dark">Capaian & Kurva Kinerja Organisasi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('penilaian::components.set-tahun')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="bg-white p-4">
                    {{-- <div class="d-flex justify-content-end">
                        <button class="btn btn-success">Refresh</button>
                    </div> --}}
                    <div class="mb-3">
                        <form class="form-inline" action="{{ url('/penilaian/kinerja-organisasi/set-capaian-kinerja') }}" method="POST">
                            @csrf
                            <select name="capaian_kinerja" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                              <option>Silahkan Pilih</option>
                              <option value="Istimewa">Istimewa</option>
                              <option value="Baik">Baik</option>
                              <option value="Butuh Perbaikan">Butuh Perbaikan</option>
                              <option value="Kurang">Kurang</option>
                              <option value="Sangat Kurang">Sangat Kurang</option>
                            </select>

                            <button type="submit" class="btn btn-primary my-1">Simpan</button>
                        </form>
                    </div>
                    @if ($capaianKinerja != null)
                        <div class="border p-4" style="width: 50%;">
                            @if ($capaianKinerja->capaian_kinerja == 'Istimewa')
                                <img src="{{ asset('modules/penilaian/images/Istimewa.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            @elseif($capaianKinerja->capaian_kinerja == 'Baik')
                                <img src="{{ asset('modules/penilaian/images/baik.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            @elseif($capaianKinerja->capaian_kinerja == 'Butuh Perbaikan')
                                <img src="{{ asset('modules/penilaian/images/butuhperbaikan.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            @elseif($capaianKinerja->capaian_kinerja == 'Kurang')
                                <img src="{{ asset('modules/penilaian/images/kurang.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            @elseif($capaianKinerja->capaian_kinerja == 'Sangat Kurang')
                                <img src="{{ asset('modules/penilaian/images/sangat kurang.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            @endif
                        </div>
                    @else
                        <p>Capaian Kinerja Organisasi belum diset</p>
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
<script>

</script>
@endpush
