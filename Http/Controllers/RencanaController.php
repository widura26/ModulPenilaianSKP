<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\RencanaKerja;
use Illuminate\Support\Facades\Auth;
use Modules\Pengaturan\Entities\Anggota;
use Illuminate\Support\Facades\DB;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\Cascading;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\Indikator;
use Modules\Penilaian\Entities\PerilakuKerja;
use Modules\Penilaian\Entities\PeriodeAktif;
use Modules\Penilaian\Entities\RencanaPerilaku;

class RencanaController extends Controller {

    protected $penilaianController;
    protected $periodeController;

    public function __construct(PenilaianController $penilaianController, PeriodeController $periodeController) {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
    }

    public function getRencana($username){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $pegawai = Pegawai::with(['timKerjaAnggota','rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit', 'timKerjaAnggota.subUnits.unit','timKerjaAnggota.parentUnit.unit',
        ])->where('username', '=', $username)->first();

        $rencana = RencanaKerja::with([
            'hasilKerja.parent.rencanakerja',
            'hasilKerja.parent',
            'perilakuKerja',
            'hasilKerja.penilaian' => function ($query) use ($pegawaiWhoLogin){
                $query->where('ketua_tim_id', $pegawaiWhoLogin->id);
            },
            'perilakuKerja.rencanaPerilaku.penilaianPerilakuKerja' => function ($query) use ($pegawaiWhoLogin){
                $query->where('ketua_tim_id', $pegawaiWhoLogin->id);
            },
            'hasilKerja', // aktifkan ini jika hasilKerja ingin ditampilkan walaupun dari intervensi yang berbeda
            // 'hasilKerja' => function ($query) use ($pegawaiWhoLogin) {
            //     $query->whereHas('parent.rencanakerja', function ($q) use ($pegawaiWhoLogin) {
            //         $q->where('pegawai_id', $pegawaiWhoLogin->id);
            //     })->orWhereNull('parent_hasil_kerja_id');
            // }, // aktifkan ini jika hasilKerja ingin ditampilkan berdasarkan parent hasil kerja milik pejabat penilai
            'perilakuKerja' => function ($query) use ($periodeId, $pegawai) {
                $query->with(['rencanaPerilaku' => function ($q) use ($periodeId, $pegawai) {
                    $q->whereHas('rencanakerja', function ($qr) use ($periodeId, $pegawai) {
                        $qr->where('periode_id', $periodeId)
                        ->where('pegawai_id', $pegawai->id);
                    });
                }]);
            }])
        ->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();

        return $rencana;
    }

    public function getAnggota(Request $request) {
        try {
            $pegawai = $this->penilaianController->getPegawaiWhoLogin();

            $timKerjaId = $pegawai->timKerjaAnggota[0]->id;

            $bawahan = Anggota::with(['timKerja', 'pegawai'])
            ->where(function ($query) use ($timKerjaId) {
                $query->whereHas('timKerja', function ($q) use ($timKerjaId) {
                        $q->where('parent_id', $timKerjaId);
                    }
                )->orWhere(function ($q) use ($timKerjaId) {
                        $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                            $sub->where('id', $timKerjaId);
                        })
                        ->where('peran', '!=', 'Ketua');
                    }
                );
            })->paginate(10);

            return response()->json([
                'status' => 'success',
                'draw' => $request->draw,
                'recordsTotal' => $bawahan->total(),
                'recordsFiltered' => $bawahan->total(),
                'data' => $bawahan->items()
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function index(Request $request){

        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $rencana = RencanaKerja::with('hasilKerja')->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();
        $indikatorIntervensi = Cascading::with('indikator.hasilKerja.rencanakerja.pegawai.timKerjaAnggota')->where('pegawai_id', $pegawai->id)->get();
        $parentHasilKerja = $indikatorIntervensi->pluck('indikator.hasilKerja')->unique('id')->values();

        $atasanService = new AtasanService();
        $ketua = $atasanService->getAtasanPegawai($pegawai->id);

        if($request->query('params') == 'json'){
            return response()->json([
                'parent_hasil_kerja' => $parentHasilKerja
            ]);
        }else {
            return view('penilaian::rencana.rencana', compact('rencana', 'pegawai', 'parentHasilKerja'));
            // return view('penilaian::rencana.rencana-skp', compact('pegawai', 'rencana', 'parentHasilKerja'));
        }
    }

    public function store(){
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        $perilakuList = PerilakuKerja::all();
        if(is_null($periodeId)) {
            return redirect()->back()->with('failed', 'Periode belum diset');
        }
        DB::beginTransaction();
        try {

            $rencana = RencanaKerja::create([
                'tim_kerja_id' => session('tim_kerja_id'),
                'periode_id' => $periodeId,
                'status_persetujuan' => 'Belum Ajukan SKP',
                'status_realisasi' =>  'Belum Ajukan Realisasi',
                'pegawai_id' => $pegawai->id
            ]);

            foreach ($perilakuList as $perilaku) {
                RencanaPerilaku::create([
                    'rencana_id' => $rencana->id,
                    'perilaku_kerja_id' => $perilaku->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil Buat SKP');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeHasilKerjaUtama(Request $request, $id) {
        try {
            $indikators = $request->indikators;
            $arrayIndikators = array_filter(array_map('trim', explode(';', $indikators)));

            $requestHasilKerja = [
                'rencana_id' => $id,
                'parent_hasil_kerja_id' => $request->parent_hasil_kerja_id ?? null,
                'deskripsi' => $request->deskripsi,
                'indikator' => $indikators
            ];

            DB::transaction(function () use ($requestHasilKerja, $arrayIndikators) {
                $hasilKerja = HasilKerja::create($requestHasilKerja);
                foreach ($arrayIndikators as $indikator) {
                    Indikator::create([
                        'hasil_kerja_id' => $hasilKerja->id,
                        'deskripsi' => $indikator
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Berhasil menambahkan hasil kerja');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function storeHasilKerjaTambahan(Request $request, $id) {
        try {
            $indikators = $request->indikators;
            $arrayIndikators = array_filter(array_map('trim', explode(';', $indikators)));

            $requestHasilKerja = [
                'rencana_id' => $id,
                'deskripsi' => $request->deskripsi,
                'indikator' => $indikators,
                'jenis' => 'tambahan'
            ];

            // return response()->json($requestHasilKerja);

            DB::transaction(function () use ($requestHasilKerja, $arrayIndikators) {
                $hasilKerja = HasilKerja::create($requestHasilKerja);
                foreach ($arrayIndikators as $indikator) {
                    Indikator::create([
                        'hasil_kerja_id' => $hasilKerja->id,
                        'deskripsi' => $indikator
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Berhasil menambahkan hasil kerja');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
