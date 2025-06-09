<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Penilaian\Entities\Arsip;

class ArsipController extends Controller
{
   public function index() {
        $penilaianController = new PenilaianController();
        $pegawaiWhoLogin = $penilaianController->getPegawaiWhoLogin();
        $arsips = Arsip::where('pegawai_id', $pegawaiWhoLogin->id)->get();
        return view('penilaian::arsip.index', compact('arsips'));
   }

   public function detail(Arsip $arsip) {
        $arsipData = Arsip::where('id', $arsip->id)
        ->where('pegawai_id', $arsip->pegawai->id)
        ->where('jenis_arsip', $arsip->jenis_arsip)->first();
        return view('penilaian::arsip.detail', compact('arsipData'));
   }

    public function store(Request $request){
        $periodeController = new PeriodeController();
        $penilaianController = new PenilaianController();
        $periodeAktif = $periodeController->periode_aktif();
        $pegawaiWhoLogin = $penilaianController->getPegawaiWhoLogin();
        $request->validate([
            'jenis_arsip' => 'required|string',
            'file_arsip' => 'required|mimes:pdf|max:10240'
        ]);
        $path = $request->file('file_arsip')->store('arsip', 'public');
        try {
            $arsips = Arsip::where('pegawai_id', $pegawaiWhoLogin->id)
                            ->where('periode_id', $periodeAktif)
                            ->where('jenis_arsip', $request->jenis_arsip)->first();
            if(!$arsips){
                Arsip::create([
                    'pegawai_id' => $pegawaiWhoLogin->id,
                    'periode_id' => $periodeAktif,
                    'jenis_arsip' => $request->jenis_arsip,
                    'file' => $path,
                ]);
                return redirect()->back()->with('success', 'Berhasil menambahkan arsip');
            } else {
                return redirect()->back()->with('failed', 'Arsip sudah ada');
            }
        } catch (\Throwable $th) {
            Storage::disk('public')->delete($path);
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $arsip = Arsip::findOrFail($id);

        $request->validate([
            'file_arsip' => 'required|mimes:pdf|max:10240',
            'jenis_arsip' => 'required'
        ]);

        try {
            if ($request->hasFile('file_arsip')) {
                if ($arsip->file && Storage::disk('public')->exists($arsip->file)) {
                    Storage::disk('public')->delete($arsip->file);
                }

                $newPath = $request->file('file_arsip')->store('arsip', 'public');
                $arsip->file = $newPath;
            }

            $arsip->jenis_arsip = $request->jenis_arsip;
            $arsip->save();

            return redirect()->back()->with('success', 'Berhasil memperbarui arsip.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete($id) {
        try {
            $arsip = Arsip::findOrFail($id);
            if ($arsip->file && Storage::disk('public')->exists($arsip->file)) {
                Storage::disk('public')->delete($arsip->file);
            }
            $arsip->delete();
            return redirect()->back()->with('success', 'Arsip berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus arsip: ' . $th->getMessage());
        }
    }

    public function getArsips(Request $request, $jenisArsip) {
        try {
            $searchValue = $request->input('search.value');
            $query = Arsip::with(['pegawai', 'periode'])->where('jenis_arsip', $jenisArsip);

            if ($searchValue) {
                $query->whereHas('pegawai', function ($q) use ($searchValue) {
                    $q->where('nama', 'like', '%' . $searchValue . '%');
                });
            }

            $arsips = $query->paginate( $request->input('length', 10),
                ['*'], 'page', floor($request->input('start', 0) / $request->input('length', 10)) + 1);

            return response()->json([
                'status' => 'success',
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $arsips->total(),
                'recordsFiltered' => $arsips->total(),
                'data' => $arsips->items()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getArsipRencana(Request $request){
        $arsipsRencana = $this->getArsips($request, 'Rencana');
        return $arsipsRencana;
    }

    public function getArsipEvaluasi(Request $request){
        $arsipsEvaluasi = $this->getArsips($request, 'Evaluasi');
        return $arsipsEvaluasi;
    }

    public function getArsipDokEvaluasi(Request $request){
        $arsipsDokEvaluasi = $this->getArsips($request, 'Dokumen Evaluasi');
        return $arsipsDokEvaluasi;
    }

    public function verification(Request $request, Arsip $arsip) {
        $request->validate([
            'status' => 'required|string'
        ]);

        try {
            $arsipData = Arsip::where('id', $arsip->id)
                        ->where('pegawai_id', $arsip->pegawai->id)
                        ->where('jenis_arsip', $arsip->jenis_arsip)->first();

            $arsipData->update([ 'status' => $request->status ]);
            return redirect()->back()->with('success', 'Arsip berhasil diverifikasi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
