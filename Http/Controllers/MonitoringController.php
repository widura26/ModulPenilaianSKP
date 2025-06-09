<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\RencanaKerja;

class MonitoringController extends Controller
{
    public function index() {
        return view('penilaian::monitoring.index');
    }

    public function monitoring(Request $request){
        try {
            $recanas = RencanaKerja::with('pegawai')->paginate(10);
            return response()->json([
                'status' => 'success',
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $recanas->total(),
                'recordsFiltered' => $recanas->total(),
                'data' => $recanas->items()
            ]);
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }
}
