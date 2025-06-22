<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #table-penilaian th, #table-penilaian td {
            vertical-align: top;
            border: 0.5px solid rgb(113, 113, 113);
            border-collapse: collapse;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }

        body {
            font-size: 12pt;
        }

        .width {
            width: 794px;
            margin: auto;
        }
        @media print {
            @page {
                size: A4;
                margin: 1.016cm;
            }
            .width {
                width: auto;
            }
            .button-cetak {
                display: none;
            }

            body {
                font-size: 12pt;
            }
            #table-penilaian th, #table-penilaian td {
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
        <div class="width">
            <div class="text-center my-4">
                @include('penilaian::realisasi.components.garuda-image')
                <p class="mb-0">DOKUMEN EVALUASI KINERJA PEGAWAI</p>
                <p class="mb-0"><strong>PERIODE: {{
            \Carbon\Carbon::parse($rencana->periode->start_date)->translatedFormat('F')
            }}-{{ \Carbon\Carbon::parse($rencana->periode->end_date)->translatedFormat('F') }} {{ $rencana->periode->tahun }}</strong></p>
            </div>
            <button onclick="window.print()" class="button-cetak btn btn-primary mb-2">Cetak</button>
            <table id="table-penilaian" cellspacing="0" cellpadding="5" width="100%">
                <tbody>
                    <tr>
                        <th rowspan="6" style="width: 0%">1</th>
                        <th colspan="3">PEGAWAI YANG DINILAI</th>
                    </tr>
                    <tr>
                        <td>NAMA</td><td>:</td><td>{{ $pegawai->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td><td>:</td><td>{{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td>Pangkat/Gol</td><td>:</td><td>-</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td><td>:</td><td>-</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td><td>:</td><td>{{ $pegawai->timKerjaAnggota[0]->unit->nama }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th rowspan="6" style="width: 0%">2</th>
                        <th colspan="3">PEJABAT PENILAI KINERJA</th>
                    </tr>
                    <tr>
                        <td>NAMA</td><td>:</td><td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td><td>:</td><td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pangkat/Gol</td><td>:</td><td>-</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td><td>:</td><td>-</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td><td>:</td><td>{{ $pegawai->timKerjaAnggota[0]->parentUnit?->unit?->nama ?? '-' }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th rowspan="6" style="width: 0%">3</th>
                        <th colspan="3">ATASAN PEJABAT PENILAI KINERJA</th>
                    </tr>
                    <tr>
                        <td>NAMA</td><td>:</td><td>{{ $atasanpejabatpenilai != null ? $atasanpejabatpenilai->pegawai->nama : '-' }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td><td>:</td><td>{{ $atasanpejabatpenilai != null ? $atasanpejabatpenilai->pegawai->nip : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pangkat/Gol</td><td>:</td><td>{{ $atasanpejabatpenilai != null ? '-' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td><td>:</td><td>{{ $atasanpejabatpenilai != null ? '-' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td><td>:</td><td>{{ $atasanpejabatpenilai != null ? $atasanpejabatpenilai->pegawai->timKerjaAnggota[0]->unit?->nama : '-' }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th rowspan="3" style="width: 0%">4</th>
                        <th colspan="3">EVALUASI KINERJA</th>
                    </tr>
                    <tr>
                        <td>CAPAIAN KINERJA ORGANISASI</td><td>:</td><td>{{ $capaianKinerjaOrganisasi->capaian_kinerja ?? null }}</td>
                    </tr>
                    <tr>
                        <td>PREDIKAT KINERJA PEGAWAI</td><td>:</td><td>{{ $pegawai->rencanaKerja[0]->predikat_akhir }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th rowspan="2" style="width: 0%">5</th>
                        <th colspan="3">CATATAN / REKOMENDASI</th>
                    </tr>
                    <tr>
                        <td colspan="3">-</td>
                    </tr>
                </tbody>
            </table>

            <table class="mt-4" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td class="text-center" style="width: 50%;">{{ $ttd_pegawai_date ?? '-' }}</td>
                        <td class="text-center" style="width: 50%;">{{ $ttd_pejabat_date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="width: 50%;">Pegawai yang Dinilai</td>
                        <td class="text-center" style="width: 50%;">Pejabat Penilai Kinerja</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="height:100px;"></td>
                        <td class="text-center" style="height:100px;"></td>
                    </tr>
                    <tr>
                        <td class="text-center" style="width: 50%;">{{ $pegawai->nama }}</td>
                        <td class="text-center" style="width: 50%;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="width: 50%;">{{ $pegawai->nip }}</td>
                        <td class="text-center" style="width: 50%;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
