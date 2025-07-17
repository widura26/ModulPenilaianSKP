<?php

namespace Modules\Penilaian\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Entities\HasilKerja;
use Illuminate\Support\Facades\Auth;
use Modules\Penilaian\Entities\Cascading;
use Modules\Pengaturan\Entities\Anggota;
use Modules\Penilaian\Entities\PengajuanRealisasiPeriodik;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\RealisasiPeriodik;
use Modules\Penilaian\Entities\RealisasiTriwulan;

class RealisasiController extends Controller {

    protected $rencanaController, $penilaianController;

    public function __construct(RencanaController $rencanaController, PenilaianController $penilaianController) {
        $this->rencanaController = $rencanaController;
        $this->penilaianController = $penilaianController;
    }

    public function realisasi(Request $request, $triwulan){
        $authUser = Auth::user();
        $periodeController = new PeriodeController();
        $pegawai = $authUser->pegawai;
        $pegawaiUsername = $pegawai->username;
        $pegawaiId = $pegawai->id;
        $periodeId = $periodeController->periode_aktif();
        $periode = Periode::where('jenis_periode', str_replace('-', ' ', $triwulan))->first();

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

        $rencana = RencanaKerja::with([
            'hasilKerja',
            // 'hasilKerja.realisasiPeriodik' => function($query) use ($periode){
            //     $query->where('periode_id', $periode->id);
            // },
            'evaluasiPeriodik' => function ($query) use ($periode) {
                $query->where('periode_id', $periode->id);
            },
            'pengajuanRealisasiPeriodik' => function ($query) use ($periode) {
                $query->where('periode_id', $periode->id);
            }])->where('pegawai_id', '=', $pegawaiId)->where('periode_id', $periodeId)->first();
        $indikatorIntervensi = Cascading::with('indikator.hasilKerja.rencanakerja')->where('pegawai_id', $pegawaiId)->get();
        if($request->query('params') == 'json') return response()->json($rencana);
        return view('penilaian::realisasi.realisasi', compact('rencana', 'pegawai', 'indikatorIntervensi', 'periode'));
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

    public function ajukanRealisasi2($periodeId, $rencanaId){
        try {
            PengajuanRealisasiPeriodik::updateOrCreate(
                ['rencana_id' => $rencanaId, 'periode_id' => $periodeId],
                ['status' => 'Belum Dievaluasi']
            );
            return redirect()->back()->with('success', 'Realiasi berhasil diajukan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function updateRealisasi(Request $request, $id) {
        try {
            HasilKerja::findOrFail($id)->update([
                'realisasi' => $request->realisasi,
                'bukti_dukung' => $request->bukti_dukung
            ]);
            return redirect()->back()->with('success', 'Realisasi berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function createOrUpdateRealisasi2(Request $request, $periodeId, $hasilKerjaId) {
        $validated = $request->validate([
            'realisasi' => 'string',
            'bukti_dukung' => 'string'
        ]);
        // $existing = RealisasiPeriodik::where('periode_id', $periodeId)->where('hasil_kerja_id', $hasilKerjaId)->first();
        // if ($existing) return redirect()->back()->with('failed', 'Realisasi untuk hasil kerja dan periode ini sudah ada.');
        try {
            RealisasiPeriodik::updateOrCreate(
                [
                    'hasil_kerja_id' => $hasilKerjaId,
                    'periode_id' => $periodeId
                ],
                [
                    'realisasi' => $validated['realisasi'],
                    'bukti_dukung' => $validated['bukti_dukung']
                ]
            );
            return redirect()->back()->with('success', 'Realisasi berhasil diperbarui.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteRealisasi($id){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->rencanaController->getRencana($pegawaiWhoLogin->username);

        try {
            $rencana->hasilKerja()->where('id', $id)->update(['realisasi' => null]);
            return redirect()->back()->with('success', 'realisasi berhasil dikosongkan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteRealisasi2($periodeId, $hasilKerjaId){
        $realisasiPeriodik = RealisasiPeriodik::where('hasil_kerja_id', $hasilKerjaId)->where('periode_id', $periodeId)->first();
        if(!$realisasiPeriodik) return redirect()->back()->with('failed', 'realiasi yang dimaksud tidak tersedia');

        try {
            $realisasiPeriodik->delete();
            return redirect()->back()->with('success', 'realisasi berhasil dikosongkan');
        } catch (\Throwable $th) {
            throw $th;
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

    public function batalkanPengajuanRealisasi2($periodeId, $rencanaId){

        try {
            $pengajuanRealisasi = PengajuanRealisasiPeriodik::where('rencana_id', $rencanaId)->where('periode_id', $periodeId)->first();
            if($pengajuanRealisasi) $pengajuanRealisasi->delete();
            return redirect()->back()->with('success', 'Pengajuan Realiasi berhasil dibatalkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }
}
