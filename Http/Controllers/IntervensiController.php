<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Http\Controllers\PenilaianController;
use Modules\Penilaian\Http\Controllers\PeriodeController;

class IntervensiController extends Controller
{
    protected $penilaianController;
    protected $periodeController;

    public function __construct(PenilaianController $penilaianController, PeriodeController $periodeController)
    {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
    }

    public function index()
    {
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $rencana = RencanaKerja::with('hasilKerja')->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();
        return view('penilaian::intervensi.intervensi', compact('pegawai', 'rencana'));
    }

    public function storeDeskripsiHasilKerja(Request $request, $id)
    {
        $request->validate([
            'rencana_id' => 'required|integer',
            'deskripsi' => 'required|string'
        ]);

        try {
            HasilKerja::create([
                'rencana_id' => $request->rencana_id,
                'deskripsi' => $request->deskripsi,
                'jenis' => 'utama',
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $th->getMessage());
        }
    }
}
