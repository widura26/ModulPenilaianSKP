<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\PeriodeAktif;

class PeriodeController extends Controller {

    public function periode_aktif(){
        $penilaianController = new PenilaianController();
        $pegawai = $penilaianController->getPegawaiWhoLogin();
        $periodeAktif = PeriodeAktif::with('periode')->where('pegawai_id', $pegawai->id)->first();
        $periodeId = $periodeAktif?->periode_id;

        return $periodeId;
    }

    public function index(){
        $periodes = Periode::all();
        return view('penilaian::periode.index', compact('periodes'));
    }

    public function detail(Request $request, $id){
        $periode = Periode::find($id);
        return view('penilaian::periode.detail', compact('periode'));
    }

    public function store(Request $request){
        try {
            Periode::create([
                'start_date' => $request->periode_awal,
                'end_date' => $request->periode_akhir,
                'tahun' => $request->tahun,
                'jenis_periode' => $request->jenis_periode
            ]);

            return redirect()->back()->with('success', 'tambah periode berhasil');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function update(Request $request, $id){
        $periode = Periode::find($id);

        $request->validate([
            'capaian_kinerja' => 'required|string',
            'kurva' => 'required|file|mimes:jpg,png|max:10240'
        ]);
        $path = $request->file('kurva')->store('kurva', 'public');

        $periode->update([
            'capaian_kinerja' => $request->capaian_kinerja,
            'kurva' => $path
        ]);
        return redirect()->back()->with('success', 'berhasil');
    }

    public function setPeriode(Request $request){
        $request->validate([
            'periodetahun' => 'required|exists:periodes,id',
            'periode_range' => 'required|exists:periodes,id',
        ], [
            'periodetahun.required' => 'Periode tahun belum di-set.',
            'periode_range.required' => 'Periode belum dipilih.',
        ]);
        $penilaianController = new PenilaianController();
        $pegawai = $penilaianController->getPegawaiWhoLogin();

        try {
            $hasil = PeriodeAktif::updateOrCreate(
                ['pegawai_id' => $pegawai->id],
                ['periode_id' => $request->periode_range]
            );
            return redirect()->to(url()->previous());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
