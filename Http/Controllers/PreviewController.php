<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\CapaianKinerjaOrganisasi;
use Modules\Penilaian\Entities\RencanaKerja;

class PreviewController extends Controller {

    protected $penilaianController, $rencanaController, $periodeController;

    public function __construct( PenilaianController $penilaianController, RencanaController $rencanaController, PeriodeController $periodeController) {
        $this->penilaianController = $penilaianController;
        $this->rencanaController = $rencanaController;
        $this->periodeController = $periodeController;
    }

    public function previewEvaluasi(){
        $authUser = Auth::user();
        $authPegawai = $authUser->pegawai;
        $pegawaiUsername = $authPegawai->username;
        $pegawaiId = $authPegawai->id;

        $pegawai = Pegawai::with([
            'pejabat.jabatan',
            'timKerjaAnggota',
            'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiUsername)->first();
        return view('penilaian::cetak-evaluasi-page', compact('pegawai'));
    }

    public function previewDokEvaluasi(Request $request){
        $authUser = Auth::user();
        $authPegawai = $authUser->pegawai;
        $pegawaiUsername = $authPegawai->username;
        $pegawaiId = $authPegawai->id;

        $pegawai = Pegawai::with([
            'pejabat.jabatan',
            'timKerjaAnggota',
            'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiUsername)->first();

        if($request->query('params') == 'json'){
            return response()->json($pegawai);
        }else {
            return view('penilaian::cetak-dokevaluasi-page', compact('pegawai'));
        }
    }

    public function backupPreviewEvaluasi(Request $request){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
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
            return view('penilaian::backup-cetak-evaluasi-page', compact('pegawai', 'rencana', 'rekapKehadiran', 'capaianKinerjaOrganisasi'));
        }
    }

    public function backupPreviewDokEvaluasi(Request $request){
        $authUser = Auth::user();
        $authPegawai = $authUser->pegawai;
        $pegawaiUsername = $authPegawai->username;
        $pegawaiId = $authPegawai->id;
        $capaianKinerjaOrganisasi = CapaianKinerjaOrganisasi::first();
        // $pegawai->timKerjaAnggota[0]->parentUnit?->ketua?->pegawai->nip;
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
            return response()->json($atasanpejabatpenilai);
        }else {
            return view('penilaian::backup-cetak-dokevaluasi-page', compact('pegawai', 'capaianKinerjaOrganisasi', 'atasanpejabatpenilai'));
        }
    }
}
