<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Kinerja Pegawai</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin-top: {{ $margin_top ?? '20mm' }};
            margin-bottom: {{ $margin_bottom ?? '20mm' }};
            margin-left: {{ $margin_left ?? '20mm' }};
            margin-right: {{ $margin_right ?? '20mm' }};
        }
        body {
            font-size: 10px;
            font-family: "Times New Roman", Times, serif;
        }
        #table-evaluasi th, #table-evaluasi td {
            vertical-align: top;
            border: 0.5px solid rgb(113, 113, 113);
            border-collapse: collapse;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }

        .width {
            width: 910px;
            margin: auto;
        }
        @media print {
            .width {
                width: auto;
                margin: auto;
            }
            .button-cetak {
                display: none;
            }

            body {
            font-size: 10px;
            }
            #table-evaluasi th, #table-evaluasi td {
                vertical-align: top;
                border: 0.5px solid rgb(113, 113, 113);
                border-collapse: collapse;
            }
            .section-title {
                font-weight: bold;
                text-transform: uppercase;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div
    {{-- class="width" --}}
    >
        {{-- <button onclick="printPage()" class="button-cetak">Test cetak</button> --}}
        <div class="text-center mb-4">
            <h6>EVALUASI KINERJA PEGAWAI</h6>
            <p class="mb-0">PENDEKATAN HASIL KERJA KUANTITATIF <br> BAGI PEJABAT FUNGSIONAL PRANATA HUBUNGAN MASYARAKAT</p>
            <p class="mb-0"><strong>PERIODE: {{
            \Carbon\Carbon::parse($rencana->periode->start_date)->translatedFormat('F')
            }}-{{ \Carbon\Carbon::parse($rencana->periode->end_date)->translatedFormat('F') }} {{ $rencana->periode->tahun }}</strong></p>
        </div>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th colspan="1" style="width: 4%">No</th>
                    <th colspan="2" style="width: 46%">Pegawai yang Dinilai</th>
                    <th colspan="1" style="width: 4%">No</th>
                    <th colspan="2" style="width: 46%">Pejabat Penilai Kinerja</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td><td style="width: 5%">Nama</td><td>{{ $pegawai->nama }}</td>
                    <td>1.</td><td style="width: 5%">Nama</td><td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>2.</td><td>NIP</td><td>{{ $pegawai->nip }}</td>
                    <td>2.</td><td>NIP</td><td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>3.</td><td>Pangkat/Gol</td><td>-</td>
                    <td>3.</td><td>Pangkat/Gol</td><td>-</td>
                </tr>
                <tr>
                    <td>4.</td><td>Jabatan</td><td>-</td>
                    <td>4.</td><td>Jabatan</td><td>-</td>
                </tr>
                <tr>
                    <td>5.</td><td>Unit Kerja</td><td>{{ $pegawai->timKerjaAnggota[0]->unit->nama }}</td>
                    <td>5.</td><td>Unit Kerja</td><td>{{ $pegawai->timKerjaAnggota[0]->parentUnit?->unit?->nama ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <thead>
                <tr>
                    <th style="background-color: #f2f2f2;">CAPAIAN KINERJA ORGANISASI : {{ $periode->capaian_kinerja }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td  style="background-color: #f2f2f2;">
                        <p>POLA DISTRIBUSI :</p>
                        <div class="">
                            No Data
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <thead>
                <tr>
                    <tr>
                        <th colspan="2" style="width: 50%; background-color: #f2f2f2; text-align: left;">HASIL KERJA</th>
                        <th rowspan="2" class="text-center align-middle" style="width: 25%; background-color: #f2f2f2;">Realisasi Berdasarkan Bukti Dukung</th>
                        <th rowspan="2" class="text-center align-middle" style="width: 25%; background-color: #f2f2f2;">Umpan Balik Berkelanjutan Berdasarkan Bukti Dukung</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="width: 50%; background-color: #f2f2f2; text-align: left;">A. Utama</th>
                    </tr>
                </tr>
            </thead>
            <tbody>
                @foreach ($rencana->hasilKerja as $index => $item)
                    <tr>
                        <td style="width: 3%;">{{ $index + 1 }}</td>
                        <td style="vertical-align: top;">
                        <strong>{{ $item->deskripsi }}</strong>
                        <br>( Penugasan dari ... )
                        <br><br>
                        <strong>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</strong>
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach ($item->indikator as $indikator)
                                <li>{{ $indikator->deskripsi }}</li>
                            @endforeach
                        </ul>
                        </td>
                        <td style="vertical-align: top;">{{ $item['realisasi'] }}</td>
                        @php
                            $hasilKerja = $item->penilaian[0] ?? null;
                            $predikat = $hasilKerja?->umpan_balik_predikat;
                            $deskripsi = $hasilKerja?->umpan_balik_deskripsi;
                        @endphp
                        <td style="vertical-align: top;">{{ $deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <tbody>
                <tr>
                    <td style="width: 50%; background-color: #f2f2f2;">RATING HASIL KERJA</td>
                    <td style="width: 50%; background-color: #f2f2f2;">{{ $rencana->rating_hasil_kerja }}</td>
                </tr>
            </tbody>
        </table>

        <p class="mb-2"><em>Dokumen milik {{ $pegawai->nama }} (NIP {{ $pegawai->nip }})</em></p>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <thead>
                <tr>
                    <th colspan="3" style="background-color: #f2f2f2; width: 75%;">PERILAKU KERJA</th>
                    <th colspan="1" style="background-color: #f2f2f2; width: 25%;" class="text-center">Umpan Balik Berkelanjutan Berdasarkan Bukti Dukung</th>
                </tr>
            </thead>
            <tbody>
                @if ($rencana && $rencana->perilakuKerja)
                    @foreach ($rencana->perilakuKerja as $index => $item)
                        <tr>
                            <td style="width: 3%;">{{ $index + 1 }}</td>
                            <td style="width: 47%;">
                                <p class="mb-0">{{ $item->deskripsi }}</p>
                                @if ($item->deskripsi == 'Berorientasi Pelayanan')
                                    <p class="mb-0 badge badge-primary">
                                        Kehadiran sesuai ketentuan : {{ number_format($rekapKehadiran['rerata_kehadiran_sesuai_ketentuan'], 2) }}%
                                    </p>
                                    <p class="mb-0 badge badge-primary">
                                        Kehadiran tidak sesuai ketentuan : {{ number_format($rekapKehadiran['rerata_kehadiran_tidak_sesuai_ketentuan'], 2) }}%
                                    </p>
                                @endif
                                @php
                                    $sentence = $item->kriteria;
                                    $lists = array_filter(array_map('trim', explode(';', $sentence)));
                                @endphp
                                <ul class="mb-0 pl-4">
                                    @foreach ($lists as $list)
                                        <li>{{ $list }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width: 25%;">
                                Ekspektasi Khusus Pimpinan :
                                <br>
                                {{ $item->ekspektasi_pimpinan == null ? '-' : $item->ekspektasi_pimpinan }}
                            </td>
                            @php
                                $penilaian = $item->rencanaPerilaku->penilaianPerilakuKerja[0] ?? null;
                            @endphp
                            <td style="width: 25%;" class="">{{ $penilaian->umpan_balik_deskripsi }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <table id="table-evaluasi" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 10px;">
            <tbody>
                <tr>
                    <td style="width:50%; background-color: #f2f2f2;">RATING PERILAKU</td>
                    <td style="width:50%; background-color: #f2f2f2;">{{ $rencana->rating_perilaku }}</td>
                </tr>
                <tr>
                    <td style="width:50%; background-color: #f2f2f2;">PREDIKAT KINERJA PEGAWAI</td>
                    <td style="width:50%; background-color: #f2f2f2;">{{ $rencana->predikat_akhir }}</td>
                </tr>
            </tbody>
        </table>

        <p class="mb-4"><em>Dokumen milik {{ $pegawai->nama }} (NIP {{ $pegawai->nip }})</em></p>

        <table cellspacing="0" width="100%" style="font-size: 10px;">
            <tbody>
                <tr>
                    <td style="width:50%; text-align: center;"></td>
                    <td style="width:50%; text-align: center;">{{ $print_date ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width:50%; text-align: center;"></td>
                    <td style="width:50%; text-align: center;">Pejabat Penilai Kinerja</td>
                </tr>
                <tr>
                    <td style="height:100px; text-align: center;"></td>
                    <td style="height:100px; text-align: center;"></td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script>
    function printPage() {
        window.print();
    }
</script>
</html>
