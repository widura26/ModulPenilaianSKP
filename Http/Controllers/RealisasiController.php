<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Entities\HasilKerja;
use Illuminate\Support\Facades\Auth;
use Modules\Penilaian\Entities\Cascading;
use Modules\Pengaturan\Entities\Anggota;


class RealisasiController extends Controller {

    protected $rencanaController, $penilaianController;

    public function __construct(RencanaController $rencanaController, PenilaianController $penilaianController) {
        $this->rencanaController = $rencanaController;
        $this->penilaianController = $penilaianController;
    }

    public function realisasi(Request $request){
        $authUser = Auth::user();
        $periodeController = new PeriodeController();
        $pegawai = $authUser->pegawai;
        $pegawaiUsername = $pegawai->username;
        $pegawaiId = $pegawai->id;
        $periodeId = $periodeController->periode_aktif();

        $pegawai = Pegawai::with([
            'timKerjaAnggota',
            'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', $pegawaiUsername)->first();

        if($pegawai->timKerjaAnggota[0]->parentUnit != null){
            $atasan = $pegawai->timKerjaAnggota[0]->parentUnit->ketua->pegawai;
        }

        $timKerjaId = $pegawai->timKerjaAnggota[0]->id;
        $bawahan = Anggota::with(['timKerja', 'pegawai'])
        ->where(function ($query) use ($timKerjaId) {
            $query->where(function ($q) use ($timKerjaId) {
                    $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                        $sub->where('parent_id', $timKerjaId);
                    })->where('peran', 'Ketua');
                })
                ->orWhere(function ($q) use ($timKerjaId) {
                    $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                        $sub->where('id', $timKerjaId);
                    })->where('peran', 'Anggota');
                });
        })
        ->whereHas('pegawai', function ($q) use ($pegawaiUsername) {
            $q->where('username', '!=', $pegawaiUsername);
        })
        ->get();

        $rencana = RencanaKerja::with('hasilKerja')->where('pegawai_id', '=', $pegawaiId)->where('periode_id', $periodeId)->first();
        $indikatorIntervensi = Cascading::with('indikator.hasilKerja.rencanakerja')->where('pegawai_id', $pegawaiId)->get();
        if($request->query('params') == 'json') return response()->json($rencana);
        return view('penilaian::realisasi', compact('rencana', 'pegawai', 'indikatorIntervensi'));
    }

    public function ajukanRealisasi($id){
        try {
            $rencana = RencanaKerja::find($id);
            foreach($rencana->hasilKerja as $item){
                if (is_null($item->realisasi) || $item->realisasi === '') {
                    return redirect()->back()->with('failed', 'Semua realisasi harus diisi sebelum diajukan.');
                }
            }
            $rencana->update([
                'status_realisasi' => 'Belum Dievaluasi'
            ]);
            return redirect()->back()->with('success', 'Realiasi berhasil diajukan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function updateRealisasi(Request $request, $id) {
        try {
            HasilKerja::findOrFail($id)->update([
                'realisasi' => $request->realisasi
            ]);
            return redirect()->back()->with('success', 'Realisasi berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteRealisasi($id){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawaiWhoLogin->username);

        try {
            $rencana->hasilKerja()->where('id', $id)->update(['realisasi' => null]);
            return redirect()->back()->with('success', 'realisasi berhasil dikosongkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function batalkanPengajuanRealisasi($id){
        try {
            $rencana = RencanaKerja::find($id);
            $rencana->update([ 'status_realisasi' => 'Belum Ajukan Realisasi' ]);
            return redirect()->back()->with('success', 'Pengajuan Realiasi berhasil dibatalkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }
}
