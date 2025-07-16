<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\CapaianKinerjaOrganisasi;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\RencanaKerja;

class PreviewController extends Controller {

    protected $penilaianController, $rencanaController, $periodeController;

    public function __construct( PenilaianController $penilaianController, RencanaController $rencanaController, PeriodeController $periodeController) {
        $this->penilaianController = $penilaianController;
        $this->rencanaController = $rencanaController;
        $this->periodeController = $periodeController;
    }

    public function previewEvaluasi(){
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
        return view('penilaian::cetak-evaluasi-page', compact('pegawai', 'rencana', 'rekapKehadiran', 'periode'));
    }

    public function previewDokEvaluasi(Request $request){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawaiWhoLogin->username);

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

        if($request->query('params') == 'json'){
            return response()->json($pegawai);
        }else {
            return view('penilaian::cetak-dokevaluasi-page', compact('pegawai', 'rencana', 'capaianKinerjaOrganisasi', 'atasanpejabatpenilai'));
        }
    }

    public function backupPreviewEvaluasi(Request $request){
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

        if($request->query('params') == 'json'){
            return response()->json($rencana->hasilKerja);
        }else {
            return view('penilaian::backup-cetak-evaluasi-page', compact('pegawai', 'rencana', 'rekapKehadiran', 'periode'));
        }
    }

    public function backupPreviewDokEvaluasi(Request $request){
        $periodeRequest = $request->query('periode');
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawaiWhoLogin->username);
        $periodeId = $this->periodeController->periode_aktif();
        $authUser = Auth::user();
        $authPegawai = $authUser->pegawai;
        $pegawaiUsername = $authPegawai->username;
        $pegawaiId = $authPegawai->id;
        $capaianKinerjaOrganisasi = CapaianKinerjaOrganisasi::first();
        // $pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai->nip;
        $pegawai = Pegawai::with([
            'pejabat.jabatan',
            'timKerjaAnggota',
            'rencanaKerja' => function ($query) use ($periodeId, $periodeRequest) {
                $query->where('periode_id', $periodeId)
                ->whereHas('evaluasiPeriodik', function ($q) use ($periodeRequest) {
                    $q->where('periode_id', $periodeRequest);
                })
                ->with(['evaluasiPeriodik' => function ($q) use ($periodeRequest) {
                    $q->where('periode_id', $periodeRequest);
                }]);
            },
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiUsername)->first();

        $atasanService = new AtasanService();
        $atasanpejabatpenilai = $atasanService->getAtasanPegawai($pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai->id);

        if($request->query('params') == 'json'){
            return response()->json($pegawai->rencanaKerja);
        }else {
            return view('penilaian::backup-cetak-dokevaluasi-page', compact('pegawai', 'rencana', 'capaianKinerjaOrganisasi', 'atasanpejabatpenilai'));
        }
    }
}
