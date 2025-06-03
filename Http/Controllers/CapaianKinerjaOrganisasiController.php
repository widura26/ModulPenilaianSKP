<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\CapaianKinerjaOrganisasi;

class CapaianKinerjaOrganisasiController extends Controller
{
    public function kinerjaOrganisasi() {
        $capaianKinerja = CapaianKinerjaOrganisasi::first();
        return view('penilaian::kinerjaOrganisasi', compact('capaianKinerja'));
    }

    public function setTahun(Request $request) {
        try {
            $record = CapaianKinerjaOrganisasi::first();
            if ($record) {
                $record->update(['tahun' => $request->tahun]);
            } else {
                CapaianKinerjaOrganisasi::create(['tahun' => $request->tahun]);
            }
            return redirect()->back()->with('success', 'Tahun berhasil diset');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function setCapaianKinerja(Request $request) {
        try {
            $record = CapaianKinerjaOrganisasi::first();
            if ($record) {
                $record->update(['capaian_kinerja' => $request->capaian_kinerja]);
            } else {
                CapaianKinerjaOrganisasi::create(['capaian_kinerja' => $request->capaian_kinerja]);
            }
            return redirect()->back()->with('success', 'Capaian kinerja organisasi berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }
}
