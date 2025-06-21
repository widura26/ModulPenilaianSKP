<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\PerilakuKerja;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Entities\RencanaPerilaku;
use Modules\Penilaian\Http\Controllers\PenilaianController;
use Modules\Penilaian\Http\Controllers\PeriodeController;

class PersetujuanController extends Controller
{
    protected $penilaianController;
    protected $periodeController;

    public function __construct(PenilaianController $penilaianController, PeriodeController $periodeController)
    {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
    }
    // public function persetujuanSkp()
    // {
    //     return view('penilaian::persetujuan.persetujuan');
    // }


    // Menampilkan daftar pengajuan rencana kerja
    public function index()
    {
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        $rencana = RencanaKerja::with('pegawai')->get();
        // ->select('pegawai_id', DB::raw('MAX(status_persetujuan) as status'))
        // ->groupBy('pegawai_id')
        // ->get();

        return view('penilaian::persetujuan.persetujuan', compact('pegawai', 'rencana'));
    }

    // Menampilkan detail rencana kerja berdasarkan pegawai
    // public function detail($pegawai_id)
    // {
    //     $pegawai = Pegawai::with(['rencanaKerja'])->findOrFail($pegawai_id);
    //     return view('penilaian::persetujuan.details-persetujuan', compact('pegawai'));
    // }

    public function detail($pegawai_id)
    {
        $pegawai = Pegawai::with([
            'rencanaKerja.hasilKerja.indikator',
            'rencanaKerja.pegawai.timKerjaAnggota'
        ])->findOrFail($pegawai_id);

        $rencana = $pegawai->rencanaKerja->first();
        $perilakuKerja = PerilakuKerja::all();
        $rencanaPerilaku = RencanaPerilaku::where('rencana_id', $rencana->id)
        ->pluck('ekspektasi_pimpinan', 'perilaku_kerja_id');

        return view('penilaian::persetujuan.details-persetujuan', compact('pegawai', 'rencana', 'perilakuKerja', 'rencanaPerilaku'));
    }


    // Input ekspektasi khusus
    // public function simpanEkspektasi(Request $request, $rencana_id)
    // {
    //     $request->validate([
    //         'ekspektasi_khusus' => 'required|string|max:255'
    //     ]);

    //     $rencana = RencanaKerja::findOrFail($rencana_id);
    //     $rencana->ekspektasi_khusus = $request->ekspektasi_khusus;
    //     $rencana->save();

    //     return back()->with('success', 'Ekspektasi khusus berhasil disimpan.');
    // }
    // public function simpanEkspektasiPerilaku(Request $request, $rencana_id)
    // {
    //     $data = $request->input('ekspektasi_pimpinan'); 

    //     foreach ($data as $perilakuKerjaId => $ekspektasi) {
    //         \Modules\Penilaian\Entities\RencanaPerilaku::updateOrCreate(
    //             [
    //                 'rencana_id' => $rencana_id,
    //                 'perilaku_kerja_id' => $perilakuKerjaId,
    //             ],
    //             [
    //                 'ekspektasi_pimpinan' => $ekspektasi,
    //                 'ketua_tim_id' => auth()->id(), 
    //             ]
    //         );
    //     }

    //     return back()->with('success', 'Ekspektasi khusus berhasil disimpan untuk semua perilaku kerja.');
    // }
    public function simpanEkspektasiPerilaku(Request $request, $rencana_id)
    {
        $request->validate([
            'ekspektasi_pimpinan' => 'array',                // harus array
            'ekspektasi_pimpinan.*' => 'nullable|string',   // tiap item boleh kosong
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->ekspektasi_pimpinan ?? [] as $perilakuId => $ekspektasi) {
                RencanaPerilaku::updateOrCreate(
                    [
                        'rencana_id'        => $rencana_id,
                        'perilaku_kerja_id' => $perilakuId,
                    ],
                    [
                        'ekspektasi_pimpinan' => $ekspektasi,
                        'ketua_tim_id'       => auth()->id(),   // atau null jika belum perlu
                    ]
                );
            }
            DB::commit();
            return back()->with('success', 'Ekspektasi khusus berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            // log($e);  // kalau mau debugging
            return back()->with('error', 'Ekspektasi gagal disimpan. Silakan coba lagi.');
        }
    }


    // Setujui rencana kerja
    public function setujui($pegawai_id)
    {
        RencanaKerja::where('pegawai_id', $pegawai_id)->update([
            'status_persetujuan' => 'disetujui'
        ]);

        return back()->with('success', 'Rencana kerja disetujui.');
    }

    // Tolak rencana kerja
    public function tolak($pegawai_id)
    {
        RencanaKerja::where('pegawai_id', $pegawai_id)->update([
            'status_persetujuan' => 'ditolak'
        ]);

        return back()->with('error', 'Rencana kerja ditolak.');
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('penilaian::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('penilaian::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('penilaian::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id) {}
}
