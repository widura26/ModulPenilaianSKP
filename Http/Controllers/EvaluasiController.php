<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Pengaturan\Entities\Anggota;
use Illuminate\Support\Facades\DB;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Pejabat;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\PenilaianHasilKerja;
use Modules\Penilaian\Entities\PenilaianPerilakuKerja;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\SuratTugas\Entities\DetailSuratTugas;

class EvaluasiController extends Controller {

    protected $penilaianController; protected $periodeController; protected $rencanaController;

    public function __construct( PenilaianController $penilaianController, PeriodeController $periodeController, RencanaController $rencanaController) {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
        $this->rencanaController = $rencanaController;
    }

    public function evaluasi() {
        return view('penilaian::evaluasi');
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


        if($params == 'json') return response()->json($rekapKehadiran);
        else return view(
            'penilaian::evaluasi-detail',
            compact('suratTugas', 'pegawaiWhoLogin', 'pegawai', 'rencana', 'hasiKerjaRecommendation', 'perilakuRecommendation', 'rekapKehadiran')
        );
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

    public function prosesUmpanBalik(Request $request){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        DB::beginTransaction();
        try {
            // return response()->json([
            //     'feedback_hasil_kerja_utama' => $request->feedback,
            //     'feedback_hasil_kerja_tambahan' => $request->feedback_hasil_kerja_tambahan,
            //     'feedback_perilaku_kerja' => $request->feedback_perilaku_kerja,
            // ]);

            // foreach ($request->feedback as $item) {
            //     PenilaianHasilKerja::updateOrCreate([
            //             'hasil_kerja_id' => $item['hasil_kerja_id'],
            //             'ketua_tim_id' => $pegawaiWhoLogin->id,
            //         ],
            //         [
            //             'umpan_balik_predikat' => $item['umpan_balik_predikat'],
            //             'umpan_balik_deskripsi' => $item['umpan_balik_deskripsi'] ?? null,
            //         ]
            //     );
            // }

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
                $hasilKerjaTambahan = DetailSuratTugas::where('id', $item['surat_tugas_id'])->first();

                if ($hasilKerjaTambahan) {
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

    public function ubahUmpanBalik(){}

    private function hasilKerjaRecommendation($rencana, $ketuaId, $suratTugas = null){
        $arrSuratTugas = $suratTugas->map(function ($item) use ($ketuaId) {
            $penilaian = $item->detail->penilaian->firstWhere('ketua_tim_id', $ketuaId);
            return $this->predikatValue($penilaian->umpan_balik_predikat ?? null);
        });
        $arr = $rencana->hasilKerja->map(function ($item) use ($ketuaId) {
            $penilaian = $item->penilaian->firstWhere('ketua_tim_id', $ketuaId);
            return $this->predikatValue($penilaian->umpan_balik_predikat ?? null);
        });

        $filtered = $arr->merge($arrSuratTugas)->filter();
        if ($filtered->isEmpty()) return null;

        $value = $filtered->sum();
        $average = $value / $filtered->count();

        return $this->predikatValue($average);
    }

    private function perilakuRecommendation($perilakuKerja, $ketuaId){
        $arr = $perilakuKerja->map(function ($item) use ($ketuaId) {
            $penilaian = $item->rencanaPerilaku->penilaianPerilakuKerja->firstWhere('ketua_tim_id', $ketuaId);
            return $this->predikatValue($penilaian->umpan_balik_predikat ?? null);
        });

        $filtered = $arr->filter();
        if ($filtered->isEmpty()) return null;

        $value = $filtered->sum();
        $average = $value / $filtered->count();

        return $this->predikatValue($average);
    }

    private function predikatValue($input){
        $map = [
            'Dibawah Ekspektasi' => 1,
            'Sesuai Ekspektasi' => 2,
            'Diatas Ekspektasi' => 3,
        ];

        if (is_string($input)) return $map[$input] ?? 0;
        if (is_numeric($input)) {
            $intValue = round($input);
            $result = array_search(intval($intValue), $map, true);
            return $result ?: 0;
        }

        return 0;
    }

    public function predikatKinerja($hasilKerja, $perilaku) {
        $hasilKerjaMap = [ 'Dibawah Ekspektasi' => 1, 'Sesuai Ekspektasi' => 2, 'Diatas Ekspektasi' => 3 ];
        $perilakuMap = [ 'Dibawah Ekspektasi' => 1, 'Sesuai Ekspektasi' => 2, 'Diatas Ekspektasi' => 3 ];

        $hasilKerjaValue = $hasilKerjaMap[$hasilKerja] ?? null;
        $perilakuValue = $perilakuMap[$perilaku] ?? null;

        $matrix = [
            1 => [
                1 => 'Sangat Kurang',
                2 => 'Butuh Perbaikan',
                3 => 'Butuh Perbaikan',
            ],
            2 => [
                1 => 'Kurang',
                2 => 'Baik',
                3 => 'Baik',
            ],
            3 => [
                1 => 'Kurang',
                2 => 'Baik',
                3 => 'Sangat Baik',
            ],
        ];

        $result = ($hasilKerjaValue && $perilakuValue) ? ($matrix[$hasilKerjaValue][$perilakuValue] ?? 'Data tidak valid') : 'Data tidak valid';

        return $result;
    }
}
