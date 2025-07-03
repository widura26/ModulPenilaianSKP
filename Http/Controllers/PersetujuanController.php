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
    public function index(Request $request)
    {
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $query = RencanaKerja::with('pegawai')
            ->where('periode_id', $periodeId); // ✅ filter berdasarkan periode aktif

        if ($request->has('filter_status') && $request->filter_status !== '') {
            $query->where('status_persetujuan', $request->filter_status);
        }

        $rencana = $query->get();

        return view('penilaian::persetujuan.persetujuan', compact('pegawai', 'rencana'));
    }



    // Menampilkan detail rencana kerja berdasarkan pegawai

    public function detail($pegawai_id)
    {
        $pegawai = Pegawai::with([
            'rencanaKerja.hasilKerja.indikator',
            'rencanaKerja.pegawai.timKerjaAnggota',
            'rencanaKerja.hasilKerja.lampirans',
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
    public function setujui($id)
    {
        $rencana = RencanaKerja::where('pegawai_id', $id)->first();

        if ($rencana) {
            $rencana->status_persetujuan = 'Sudah Disetujui'; // ✅ enum match
            $rencana->save();

            return back()->with('success', 'Rencana kerja disetujui.');
        } else {
            return back()->with('error', 'Rencana kerja tidak ditemukan.');
        }
    }


    // Tolak rencana kerja
    public function tolak($id)
    {
        $rencana = RencanaKerja::where('pegawai_id', $id)->first();

        if ($rencana) {
            $rencana->status_persetujuan = 'Belum Disetujui'; // ✅ enum match
            $rencana->save();

            return back()->with('error', 'Rencana kerja ditolak.');
        } else {
            return back()->with('error', 'Rencana kerja tidak ditemukan.');
        }
    }


    public function setujuiTerpilih(Request $request)
    {
        $ids = $request->input('rencana_id', []);
        RencanaKerja::whereIn('id', $ids)->update(['status_persetujuan' => 'Sudah Disetujui']); // ✅ enum match

        return back()->with('success', 'Rencana kerja yang dipilih telah disetujui.');
    }


    public function tolakTerpilih(Request $request)
    {
        $ids = $request->input('rencana_id', []);
        RencanaKerja::whereIn('id', $ids)->update(['status_persetujuan' => 'Belum Disetujui']); // ✅ enum match

        return back()->with('error', 'Rencana kerja yang dipilih telah ditolak.');
    }
}
