@extends('adminlte::page')

@section('title', 'Dasbor Simlitabmas')

@section('content_header')
    <h1 class="m-0 text-dark">Detail Periode</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="bg-white p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ url('/penilaian/periode/update/' . $periode->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="mb-2">
                                    <label for="nama-pegawai">Periode</label>
                                    <p>{{ \Carbon\Carbon::parse($periode->start_date)->translatedFormat('j F Y') }} - {{ \Carbon\Carbon::parse($periode->end_date)->translatedFormat('j F Y') }}</p>
                                </div>
                                <div class="">
                                    <label for="status">Jenis Periode</label>
                                    <p>{{ $periode->jenis_periode }}</p>
                                </div>
                                <div class="">
                                    <label for="jenis-arsip">Capaian Kinerja</label>
                                    <select name="capaian_kinerja" id="" class="form-control">
                                        <option value="">-- Pilih Capaian Kinerja --</option>
                                        <option value="Istimewa" {{ $periode->capaian_kinerja === 'Istimewa' ? 'selected' : '' }}>Istimewa</option>
                                        <option value="Baik" {{ $periode->capaian_kinerja === 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Butuh Perbaikan" {{ $periode->capaian_kinerja === 'Butuh Perbaikan' ? 'selected' : '' }}>Butuh Perbaikan</option>
                                        <option value="Kurang" {{ $periode->capaian_kinerja === 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                        <option value="Sangat Kurang" {{ $periode->capaian_kinerja === 'Sangat Kurang' ? 'selected' : '' }}>Sangat Kurang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="mb-2">
                                    <label for="nama-pegawai">Kurva</label>
                                    <input type="file" name="kurva" class="form-control" id="gambarInput" accept="image/*">
                                </div>
                                <div class="">
                                    <label for="status">Preview</label>
                                    <p id="noImageText">Tidak ada gambar yang dipilih</p>
                                    <img id="gambarPreview" src="#" alt="Preview" style="display: none; max-width: 300px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@push('js')
    <script>
        document.getElementById('gambarInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('gambarPreview');
            const noImageText = document.getElementById('noImageText');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    noImageText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.style.display = 'none';
                noImageText.style.display = 'block';
            }
        });
    </script>
@endpush
