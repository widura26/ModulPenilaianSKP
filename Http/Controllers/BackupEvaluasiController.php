<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Penilaian\Entities\PenilaianHasilKerja;
use Modules\SuratTugas\Entities\DetailSuratTugas;
use Modules\Penilaian\Entities\PenilaianPerilakuKerja;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Anggota;
use Modules\Pengaturan\Entities\Pejabat;

class BackupEvaluasiController extends Controller
{
    protected $penilaianController; protected $periodeController; protected $rencanaController;

    public function __construct( PenilaianController $penilaianController, PeriodeController $periodeController, RencanaController $rencanaController) {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
        $this->rencanaController = $rencanaController;
    }

    public function evaluasiDetail(Request $request, $username) {
        $params = $request->query('params');
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        $rencana = $this->rencanaController->getRencana($username);
        $pegawai = Pegawai::with(['timKerjaAnggota','rencanaKerja.hasilKerja',
        'timKerjaAnggota.unit', 'timKerjaAnggota.subUnits.unit','timKerjaAnggota.parentUnit.unit',
        ])->where('username', '=', $username)->first();

        $suratTugas = $this->penilaianController->getSuratTugas($pegawai->id);
        $hasiKerjaRecommendation = $this->hasilKerjaRecommendation($rencana, $pegawaiWhoLogin->id, $suratTugas);
        $perilakuRecommendation = $this->perilakuRecommendation($rencana->perilakuKerja, $pegawaiWhoLogin->id);


        $atasanService = new AtasanService();
        $ketua = $atasanService->getAtasanPegawai($pegawai->id);
        $rekapKehadiran = $this->penilaianController->getRekapKehadiran($username);

        $hasilKerjaUtama = collect();
        $hasilKerjaTambahan = collect();

        if (!is_null($rencana) && !is_null($rencana->hasilKerja)) {
            $hasilKerjaUtama = $rencana->hasilKerja->filter(function($item) {
                return $item->jenis === 'utama';
            })->values();

            $hasilKerjaTambahan = $rencana->hasilKerja->filter(function($item) {
                return $item->jenis === 'tambahan';
            })->values()->merge($suratTugas);
        }

        if($params == 'json') return response()->json($hasilKerjaTambahan);
        else return view('penilaian::evaluasi.evaluasi-detail',
            compact('suratTugas', 'pegawaiWhoLogin', 'pegawai', 'rencana', 'hasiKerjaRecommendation', 'perilakuRecommendation', 'rekapKehadiran', 'hasilKerjaTambahan')
        );
    }

    public function prosesUmpanBalik(Request $request, $id){
        $periodeId = $this->periodeController->periode_aktif();
        $request->validate([
            'feedback.*.umpan_balik_predikat' => 'required|string',
            'feedback.*.umpan_balik_deskripsi' => 'required|string',
            'feedback_hasil_kerja_tambahan.*.umpan_balik_predikat' => 'required|string',
            'feedback_hasil_kerja_tambahan.*.umpan_balik_deskripsi' => 'required|string',
            'feedback_perilaku_kerja.*.perilaku_umpan_balik_predikat' => 'required|string',
            'feedback_perilaku_kerja.*.perilaku_umpan_balik_deskripsi' => 'nullable|string',
        ]);
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        // return response()->json($request);
        DB::beginTransaction();
        try {
            foreach ($request->feedback as $item) {
                PenilaianHasilKerja::updateOrCreate(
                    [
                        'target_id' => $item['hasil_kerja_id'],
                        'target_type' => 'Modules\Penilaian\Entities\HasilKerja',
                        'ketua_tim_id' => $pegawaiWhoLogin->id,
                    ],
                    [
                        'umpan_balik_predikat' => $item['umpan_balik_predikat'],
                        'umpan_balik_deskripsi' => $item['umpan_balik_deskripsi'],
                    ]
                );
            }

            foreach ($request->feedback_hasil_kerja_tambahan as $item) {

                if (isset($item['surat_tugas_id'])) {
                    $hasilKerjaTambahan = DetailSuratTugas::where('id', $item['surat_tugas_id'])->first();
                    PenilaianHasilKerja::updateOrCreate(
                        [
                            'target_id' => $hasilKerjaTambahan->id,
                            'target_type' => 'Modules\SuratTugas\Entities\DetailSuratTugas',
                            'ketua_tim_id' => $pegawaiWhoLogin->id,
                        ],
                        [
                            'umpan_balik_predikat' => $item['umpan_balik_predikat'],
                            'umpan_balik_deskripsi' => $item['umpan_balik_deskripsi'],
                        ]
                    );
                } else if($item['hasil_kerja_id']) {
                    PenilaianHasilKerja::updateOrCreate(
                        [
                            'target_id' => $item['hasil_kerja_id'],
                            'target_type' => 'Modules\Penilaian\Entities\HasilKerja',
                            'ketua_tim_id' => $pegawaiWhoLogin->id,
                        ],
                        [
                            'umpan_balik_predikat' => $item['umpan_balik_predikat'],
                            'umpan_balik_deskripsi' => $item['umpan_balik_deskripsi'],
                        ]
                    );
                }
            }

            foreach ($request->feedback_perilaku_kerja as $item) {
                PenilaianPerilakuKerja::updateOrCreate([
                        'rencana_perilaku_id' => $item['perilaku_kerja_id'],
                        'ketua_tim_id' => $pegawaiWhoLogin->id,
                    ],
                    [
                        'umpan_balik_predikat' => $item['perilaku_umpan_balik_predikat'],
                        'umpan_balik_deskripsi' => $item['perilaku_umpan_balik_deskripsi'] ?? null,
                    ]);
            }

            RencanaKerja::where('periode_id', $periodeId)->where('pegawai_id', $id)->update([
                'proses_umpan_balik' => True
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'proses umpan balik berhasil');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
        }
    }

    public function simpanHasilEvaluasi(Request $request, $id) {
        $periodeId = $this->periodeController->periode_aktif();
        $requestEvaluasi = [
            'status_realisasi' => 'Sudah Dievaluasi',
            'rating_hasil_kerja' => $request->rating_hasil_kerja,
            'deskripsi_rating_hasil_kerja' => $request->deskripsi_rating_hasil_kerja,
            'rating_perilaku' => $request->rating_perilaku,
            'deskripsi_rating_perilaku' => $request->deskripsi_rating_perilaku,
            'predikat_akhir' => $this->predikatKinerja($request->rating_hasil_kerja, $request->rating_perilaku)
        ];

        try {
            if($request->rating_hasil_kerja == null && $request->rating_perilaku == null) return redirect()->back()->with('failed', 'Lengkapi rating hasil kerja dan perilaku');
            else {
                RencanaKerja::where('pegawai_id', $id)->where('periode_id', $periodeId)->update($requestEvaluasi);
                return redirect()->back()->with('success', 'Berhasil menyimpan hasil evaluasi');
            }
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }

    public function index(Request $request){
        $columns = [
            1 => 'nama'
        ];
        $search = $request->input('search.value');
        try {
            $pegawai = $this->penilaianController->getPegawaiWhoLogin();
            $periodeId = $this->periodeController->periode_aktif();
            $ketua = Pejabat::where('pegawai_id', '=', $pegawai->id)->first();
            $timKerjaId = $pegawai->timKerjaAnggota[0]->id;
            $username = $pegawai->username;

            if($ketua != null) {
                $query = Anggota::with(['timKerja', 'pegawai.rencanakerja' => function ($query) use ($periodeId) {
                    $query->where('periode_id', $periodeId);
                }])->where(function ($query) use ($timKerjaId) {
                    $query->where(function ($q) use ($timKerjaId) {
                        $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                            $sub->where('parent_id', $timKerjaId);
                        })->where('peran', 'Ketua');
                    })->orWhere(function ($q) use ($timKerjaId) {
                        $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                            $sub->where('id', $timKerjaId);
                        })->where('peran', 'Anggota');
                    });
                })->whereHas('pegawai', function ($q) use ($username, $search, $periodeId) {
                    $q->where('username', '!=', $username)
                    ->whereHas('rencanakerja', function ($r) use ($periodeId) {
                        $r->where('periode_id', $periodeId);
                    });
                    if ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    }
                });

                $bawahan = $query->paginate($request->input('length', 10), ['*'], 'page', floor($request->input('start', 0) / $request->input('length', 10)) + 1);

                return response()->json([
                    'status' => 'success',
                    'draw' => intval($request->input('draw')),
                    'recordsTotal' => $bawahan->total(),
                    'recordsFiltered' => $bawahan->total(),
                    'data' => $bawahan->items()
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'draw' => $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'message' => 'No data available'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }

    public function batalkanEvaluasi($username){
        $pegawai = Pegawai::where('username', $username)->first();
        $periodeId = $this->periodeController->periode_aktif();
        try {
            RencanaKerja::where('pegawai_id', $pegawai->id)->where('periode_id', $periodeId)->update([
                'status_realisasi' => 'Belum Dievaluasi',
                'rating_hasil_kerja' => null,
                'rating_perilaku' => null,
                'predikat_akhir' => null
            ]);
            return redirect()->back()->with('success', 'Evaluasi berhasil dibatalkan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
