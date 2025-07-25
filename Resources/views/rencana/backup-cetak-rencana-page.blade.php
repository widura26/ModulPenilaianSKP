<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dokumen</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #table-rencana th,
        #table-rencana td {
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
            width: 794px;
            margin: auto;
        }

        .print-input {
            display: inline;
        }

        .print-value {
            display: none;
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

            #table-rencana th,
            #table-rencana td {
                vertical-align: top;
                border: 0.5px solid rgb(113, 113, 113);
                border-collapse: collapse;
            }

            .section-title {
                font-weight: bold;
                text-transform: uppercase;
                margin-top: 20px;
            }

            .print-input {
                display: none;
            }

            .print-value {
                display: inline;
            }
        }
    </style>
</head>

<body>
    <div class="width">
        <div class="text-center my-4">
            <h6>SASARAN KINERJA PEGAWAI</h6>
            <p class="mb-0">PENDEKATAN HASIL KERJA KUANTITATIF <br> BAGI PEJABAT ADMINISTRASI / FUNGSIONAL</p>
            <p class="mb-0"><strong>PERIODE: {{
            \Carbon\Carbon::parse($rencana->periode->start_date)->translatedFormat('F')
            }}-{{ \Carbon\Carbon::parse($rencana->periode->end_date)->translatedFormat('F') }} {{ $rencana->periode->tahun }}</strong></p>
        </div>
        <button onclick="printPage()" class="button-cetak btn btn-primary mb-2">Cetak</button>
        <div></div>
        <table id="table-rencana" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 12px;">
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
                    <td>1.</td>
                    <td style="width: 5%">Nama</td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>1.</td>
                    <td style="width: 5%">Nama</td>
                    <td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>NIP</td>
                    <td>{{ $pegawai->nip }}</td>
                    <td>2.</td>
                    <td>NIP</td>
                    <td>{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Pangkat/Gol</td>
                    <td>-</td>
                    <td>3.</td>
                    <td>Pangkat/Gol</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Jabatan</td>
                    <td>-</td>
                    <td>4.</td>
                    <td>Jabatan</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Unit Kerja</td>
                    <td>{{ $pegawai->timKerjaAnggota[0]->unit->nama }}</td>
                    <td>5.</td>
                    <td>Unit Kerja</td>
                    <td>{{ $pegawai->timKerjaAnggota[0]->parentUnit?->unit?->nama ?? '-' }}</td>
                </tr>
            </tbody>
        </table>



        <table id="table-rencana" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 12px;">
            <thead>
                <tr>
                <tr>
                    <th colspan="2" style="width: 50%; background-color: #f2f2f2; text-align: left;">HASIL KERJA</th>

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

                </tr>
                @endforeach
                <!-- <tr></tr> -->
            </tbody>
            <thead>
                <tr>
                    <th colspan="2" style="width: 50%; background-color: #f2f2f2; text-align: left;">B. Tambahan</th>
                </tr>
            </thead>
            <tbody>
                <tr></tr>
            </tbody>
        </table>



        <p class="mb-2"><em>Dokumen milik {{ $pegawai->nama }} (NIP {{ $pegawai->nip }})</em></p>

        <table id="table-rencana" class="mb-2" cellspacing="0" cellpadding="5" width="100%" style="font-size: 12px;">
            <thead>
                <tr>
                    <th colspan="3" style="background-color: #f2f2f2; width: 75%;">PERILAKU KERJA</th>

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
                        <!-- <p class="mb-0 badge badge-primary">
                        </p>
                        <p class="mb-0 badge badge-primary">
                        </p> -->
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
                    <!-- <td style="width: 25%;" class=""></td> -->
                </tr>
                @endforeach
                @endif

            </tbody>
        </table>



        <p class="mb-4"><em>Dokumen milik {{ $pegawai->nama }} (NIP {{ $pegawai->nip }})</em></p>

        <table cellspacing="0" width="100%" style="font-size: 12px;">
            <tbody>
                <tr>
                    <td style="width:50%; text-align: center;"></td>
                    <td style="width:50%; text-align: center;">
                        <p class="print-value"></p>
                        <input type="text" class="print-input" value="Banyuwangi , {{ \Carbon\Carbon::parse($rencana->periode->start_date)->translatedFormat('d F Y') }}">
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; text-align: center;">Pegawai Yang Dinilai</td>
                    <td style="width:50%; text-align: center;">Pejabat Penilai Kinerja</td>
                </tr>
                <tr>
                    <td style="height:100px; text-align: center;"></td>
                    <td style="height:100px; text-align: center;"></td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{ $pegawai->nama}}</td>
                    <td style="text-align: center;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{ $pegawai->nip}}</td>
                    <td style="text-align: center;">{{ optional($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai)->nip ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script>
    const printPage = () => {
        window.print();
    }

    window.addEventListener('beforeprint', function() {
        const inputs = document.querySelectorAll('.print-input');
        const p = document.querySelectorAll('.print-value');

        inputs.forEach((input, index) => {
            p[index].textContent = input.value;
        });
    });
</script>

</html>