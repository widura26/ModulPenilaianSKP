<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pengaturan\Entities\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Cuti\Services\AtasanService;
use Modules\Penilaian\Entities\CapaianKinerjaOrganisasi;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\RencanaKerja;

class PrintController extends Controller {

    protected $penilaianController, $rencanaController, $periodeController;

    public function __construct( PenilaianController $penilaianController, RencanaController $rencanaController, PeriodeController $periodeController) {
        $this->penilaianController = $penilaianController;
        $this->rencanaController = $rencanaController;
        $this->periodeController = $periodeController;
    }

    public function cetakEvaluasi(Request $request){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawaiWhoLogin->username);
        $periodeId = $this->periodeController->periode_aktif();
        $periode = Periode::find($periodeId);
        $rekapKehadiran = $this->penilaianController->getRekapKehadiran($pegawaiWhoLogin->username);
        $capaianKinerjaOrganisasi = CapaianKinerjaOrganisasi::first();
        $rencana = RencanaKerja::with([
            'hasilKerja.parent.rencanakerja',
            'hasilKerja.parent',
            'perilakuKerja',
            'hasilKerja.penilaian',
            'perilakuKerja.rencanaPerilaku.penilaianPerilakuKerja',
            'hasilKerja',
            'perilakuKerja' => function ($query) use ($periodeId, $pegawaiWhoLogin) {
                $query->with(['rencanaPerilaku' => function ($q) use ($periodeId, $pegawaiWhoLogin) {
                    $q->whereHas('rencanakerja', function ($qr) use ($periodeId, $pegawaiWhoLogin) {
                        $qr->where('periode_id', $periodeId)
                        ->where('pegawai_id', $pegawaiWhoLogin->id);
                    });
                }]);
            }])
        ->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawaiWhoLogin->id)->first();

        $pegawai = Pegawai::with([
            'pejabat.jabatan', 'timKerjaAnggota', 'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit', 'timKerjaAnggota.subUnits.unit', 'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiWhoLogin->username)->first();

        $data = [
            'title' => 'Laporan Kinerja',
            'pegawai' => $pegawai,
            'print_date' => $request->print_date,
            'margin_top'    => $request->margin_top,
            'margin_bottom' => $request->margin_bottom,
            'margin_left'   => $request->margin_left,
            'margin_right'  => $request->margin_right,
            'capaianKinerjaOrganisasi' => $capaianKinerjaOrganisasi,
            'rencana' => $rencana,
            'periode' => $periode,
            'rekapKehadiran' => $rekapKehadiran
        ];

        $pdf = Pdf::loadView('penilaian::cetak-evaluasi-page', $data);
        $pdf->setPaper('a4', $request->position);
        return $pdf->download('laporan.pdf');
    }

    public function cetakDokEvaluasi(Request $request){
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawai->username);

        $authUser = Auth::user();
        $authPegawai = $authUser->pegawai;
        $pegawaiUsername = $authPegawai->username;
        $pegawaiId = $authPegawai->id;
        $capaianKinerjaOrganisasi = CapaianKinerjaOrganisasi::first();
        $pegawai = Pegawai::with([
            'pejabat.jabatan',
            'timKerjaAnggota',
            'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiUsername)->first();

        $atasanService = new AtasanService();
        $atasanpejabatpenilai = $atasanService->getAtasanPegawai($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai->id);

        $data = [
            'title' => 'Laporan Kinerja',
            'pegawai' => $pegawai,
            'ttd_pegawai_date' => $request->ttd_pegawai_date,
            'ttd_pejabat_date' => $request->ttd_pejabat_date,
            'margin_top'    => $request->margin_top,
            'margin_bottom' => $request->margin_bottom,
            'margin_left'   => $request->margin_left,
            'margin_right'  => $request->margin_right,
            'position' => $request->position,
            'pegawai_id' => $pegawaiId,
            'capaianKinerjaOrganisasi' => $capaianKinerjaOrganisasi,
            'atasanpejabatpenilai' => $atasanpejabatpenilai,
            'rencana' => $rencana
        ];
        $pdf = Pdf::loadView('penilaian::cetak-dokevaluasi-page', $data)
        ->setPaper('a4', $request->position);

        return $pdf->download('laporan.pdf');
    }
}
