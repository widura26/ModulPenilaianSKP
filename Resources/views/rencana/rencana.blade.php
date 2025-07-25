@extends('adminlte::page')

@section('title', 'Rencana')

@section('content_header')
<h1 class="m-0 text-dark">Rencana</h1>
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
            @php
            $hasilKerjaUtama = collect();
            $hasilKerjaTambahan = collect();

            if (!is_null($rencana) && !is_null($rencana->hasilKerja)) {
            $hasilKerjaUtama = $rencana->hasilKerja->filter(function($item) {
            return $item->jenis === 'utama';
            })->values();

            $hasilKerjaTambahan = $rencana->hasilKerja->filter(function($item) {
            return $item->jenis === 'tambahan';
            })->values();
            }
            @endphp

            <div class="card-body">
                @if (session('failed'))
                <div id="alert-failed" class="p-2">
                    <div class="alert alert-danger">
                        {{ session('failed') }}
                    </div>
                </div>
                @elseif(session('success'))
                <div class="p-2" id="alert-passed">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
                @elseif(session('error'))
                <div class="p-2" id="alert-error">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                <!-- <div class="alert alert-info">
                    Status Tombol: {{ $statusTombol }} |
                    Hasil Kerja: {{ $rencana?->hasilKerja?->count() ?? 0 }}
                </div> -->



                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- <div>
                        <span class="badge badge-info p-2">
                            <strong>{{ $rencana->status_persetujuan ?? 'Belum disetujui' }}</strong>
                        </span>
                        <span class="badge badge-info p-2">
                            <strong>{{ $rencana->status_pengajuan ?? 'Belum diajukan' }}</strong>
                        </span>
                    </div> -->
                    <div>
                        {{-- Status Persetujuan --}}
                        <span class="badge p-2 
                    {{ ($rencana->status_persetujuan ?? 'Belum disetujui') === 'Sudah Disetujui' ? 'badge-success' : 'badge-danger' }}">
                            <strong>{{ $rencana->status_persetujuan ?? 'Belum disetujui' }}</strong>
                        </span>

                        {{-- Status Pengajuan --}}
                        <span class="badge p-2 
                            {{ ($rencana->status_pengajuan ?? 'Belum diajukan') === 'Sudah Diajukan' ? 'badge-success' : 'badge-danger' }}">
                            <strong>{{ $rencana->status_pengajuan ?? 'Belum diajukan' }}</strong>
                        </span>
                    </div>


                    <div class="d-flex justify-content-end">
                        @if (is_null($rencana))
                        <form method="POST" action="{{ url('/skp/rencana/store') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Buat SKP</button>
                        </form>
                        @else
                        @if ($statusTombol === 'salin')
                        <form method="POST" action="{{ url('/skp/rencana/salin-skp/') }}">
                            @csrf
                            <button type="submit" class="btn btn-info ml-2">Salin SKP</button>
                        </form>

                        <form method="POST" action="{{ url('/skp/rencana/ajukan/' . $rencana->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Ajukan SKP</button>
                        </form>

                        @elseif ($statusTombol === 'reset')
                        <form method="POST" action="{{ url('/skp/rencana/reset/' . $rencana->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mr-2">Reset SKP</button>
                        </form>
                        <form method="POST" action="{{ url('/skp/rencana/salin-skp/') }}">
                            @csrf
                            <button type="submit" class="btn btn-info mr-2">Salin SKP</button>
                        </form>
                        <form method="POST" action="{{ url('/skp/rencana/ajukan/' . $rencana->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Ajukan SKP</button>
                        </form>
                        @elseif ($statusTombol === 'ajukan')
                        <form method="POST" action="{{ url('/skp/rencana/ajukan/' . $rencana->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Ajukan SKP</button>
                        </form>
                        @elseif ($statusTombol === 'batalkan')
                        <form method="POST" action="{{ url('/skp/rencana/batalkan-pengajuan/' . $rencana->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger">Batalkan Pengajuan</button>
                        </form>
                        @elseif ($statusTombol === 'cetak')
                        <a href="{{ url('/skp/rencana/cetak/' . $rencana->id) }}" target="_blank" class="btn btn-primary">Cetak Rencana</a>
                        @elseif ($statusTombol === 'ditolak')
                        <a href="{{ url('/skp/rencana/catatan/' . $rencana->id) }}" class="btn btn-warning mr-2">Catatan</a>
                        <form method="POST" action="{{ url('/skp/rencana/ajukan/' . $rencana->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Ajukan SKP</button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>

                @include('penilaian::components.atasan-bawahan-section', ['pegawai' => $pegawai])
                <div class="bg-white mt-3">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th colspan="3">Hasil Kerja</th>
                                <th></th>

                            </tr>
                            <tr>
                                <th colspan="3" class="col-sm-10 border-right">A. Utama</th>
                                <th class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-hasil-kerja-utama')
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rencana && $rencana->hasilKerja->count() > 0)
                            @foreach ($hasilKerjaUtama as $index => $item)
                            {{-- Baris Hasil Kerja --}}
                            <tr class="bg-light">
                                <th class="border-right align-top" scope="row">{{ $index + 1 }}</th>
                                <td class="border-right align-top" colspan="2">
                                    {{ $item->deskripsi }}
                                </td>
                                <td class="align-top">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-edit-hasil-kerja-utama')
                                    @endif
                                    <!-- <button type="button" class="btn btn-success btn-sm" title="Tandai Penting">
                                        <i class="fas fa-star"></i>
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-danger btn-sm" title="Nonaktifkan">
                                        <i class="fas fa-ban"></i>
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-danger btn-sm" title="Hapus Hasil Kerja">
                                        <i class="fas fa-trash"></i>
                                    </button> -->
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-delete-hasil-kerja-utama')
                                    @endif
                                </td>
                            </tr>

                            {{-- Judul Indikator --}}
                            <tr>
                                <td class="border-right"></td>
                                <td colspan="3">
                                    <span>Ukuran keberhasilan / Indikator Kinerja Individu dan Target:</span>
                                </td>
                            </tr>

                            {{-- Looping Indikator --}}
                            @foreach ($item->indikator as $indikator)
                            <tr>
                                <td class="border-right"></td>

                                {{-- Indikator Utama --}}
                                <td class="align-top border-right col-sm-7">
                                    <li class="mb-0">{{ $indikator->deskripsi }}</li>
                                </td>

                                {{-- Indikator Manual --}}
                                <td class="align-top border-right col-sm-3">
                                    @if ($indikator->definisiOperasional->count())
                                    <ul class="mb-0 ps-3">
                                        @foreach ($indikator->definisiOperasional as $manual)
                                        <li class="mb-1">
                                            <p class="mb-0"><strong>Topik:</strong> {{ $manual->topik }}</p>
                                            <p class="mb-0"><strong>Sub Topik:</strong> {{ $manual->sub_topik }}</p>
                                            <p class="mb-0"><strong>Deskripsi:</strong> {{ $manual->deskripsi }}</p>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="align-top">
                                    @include('penilaian::rencana.components.modal-edit-indikator-hasil-kerja-utama', ['indikator' => $indikator])
                                    @include('penilaian::rencana.components.modal-create-manual-indikator-utama', ['indikator' => $indikator])
                                    <!-- <button type="button" class="btn btn-danger btn-sm" title="Hapus Indikator">
                                        <i class="fas fa-trash"></i>
                                    </button> -->

                                    @include('penilaian::rencana.components.modal-delete-indikator-hasil-kerja-utama')

                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class=" text-muted">-</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>


                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="col-sm-10 border-right">B. Tambahan</th>
                                <th colspan="1" class="col-sm-2">
                                    @if (!is_null($rencana))
                                    @include('penilaian::rencana.components.modal-create-hasil-kerja-tambahan')
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($hasilKerjaTambahan->count())
                            @foreach ($hasilKerjaTambahan as $indexTambahan => $item)
                            <tr>
                                <th class="border-right" style="width: 0%;" scope="row">{{ $indexTambahan + 1 }}</th>
                                <td class="border-right">
                                    <p>{{ $item['deskripsi'] }}</p>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <!-- <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-star"></i>
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ban"></i>
                                    </button> -->
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class=" border-right"></td>
                                <td><span>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target :</span></td>
                                <td></td>
                            </tr>
                            @foreach ($item->indikator as $indikator)
                            <tr>
                                <td class="border-right"></td>
                                <td class=" border-right align-middle">
                                    <li class="mb-0">{{ $indikator['deskripsi'] }}</li>
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-warning btn-sm" title="Edit Indikator">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Indikator">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach

                            @else
                            <tr>
                                <td colspan="5">-</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="col-sm-10">C. Lampiran</th>
                                <th colspan="1" class="col-sm-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DUKUNGAN SUMBER DAYA --}}
                            <tr>
                                <th colspan="2" class="col-sm-10 border-right" scope="row">Dukungan Sumber Daya</th>
                                <th colspan="1" class="col-sm-2">
                                    @if ($rencana)
                                    @include('penilaian::rencana.components.modal-create-dukungan-sumber-daya')
                                    @endif
                                </th>
                            </tr>
                            @php
                            $nomor = 1;
                            $lampiranDukungan = $rencana && $rencana->lampirans
                            ? $rencana->lampirans->where('jenis_lampiran', 'Dukungan Sumber Daya')
                            : collect();
                            @endphp
                            @forelse ($lampiranDukungan as $lampiran)
                            <tr>
                                <th class="border-right text-center" style="width: 5%;">{{ $nomor++ }}</th>
                                <td class="align-middle" colspan="2">{{ $lampiran->deskripsi_lampiran }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">-</td>
                            </tr>
                            @endforelse

                            {{-- SKEMA PERTANGGUNG JAWABAN --}}
                            <tr>
                                <th colspan="2" class="col-sm-10 border-right" scope="row">Skema Pertanggung Jawaban</th>
                                <th colspan="1" class="col-sm-2">
                                    @if ($rencana)
                                    @include('penilaian::rencana.components.modal-create-skema-pertanggung-jawaban')
                                    @endif
                                </th>
                            </tr>
                            @php
                            $nomor = 1;
                            $lampiranSkema = $rencana && $rencana->lampirans
                            ? $rencana->lampirans->where('jenis_lampiran', 'Skema Pertanggung Jawaban')
                            : collect();
                            @endphp
                            @forelse ($lampiranSkema as $lampiran)
                            <tr>
                                <th class="border-right text-center" style="width: 5%;">{{ $nomor++ }}</th>
                                <td class="align-middle" colspan="2">{{ $lampiran->deskripsi_lampiran }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">-</td>
                            </tr>
                            @endforelse

                            {{-- KONSEKUENSI --}}
                            <tr>
                                <th colspan="2" class="col-sm-10 border-right" scope="row">Konsekuensi</th>
                                <th colspan="1" class="col-sm-2">
                                    @if ($rencana)
                                    @include('penilaian::rencana.components.modal-create-konsekuensi')
                                    @endif
                                </th>
                            </tr>
                            @php
                            $nomor = 1;
                            $lampiranKonsekuensi = $rencana && $rencana->lampirans
                            ? $rencana->lampirans->where('jenis_lampiran', 'Konsekuensi')
                            : collect();
                            @endphp
                            @forelse ($lampiranKonsekuensi as $lampiran)
                            <tr>
                                <th class="border-right text-center" style="width: 5%;">{{ $nomor++ }}</th>
                                <td class="align-middle" colspan="2">{{ $lampiran->deskripsi_lampiran }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">-</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>


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
<!-- <script>
    window.definisiList = {
        !!json_encode($definisiOperasional - > toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!
    };
</script> -->


@include('penilaian::evaluasi.script-periode')
@include('penilaian::rencana.script.script-hasilkerja')
@endpush