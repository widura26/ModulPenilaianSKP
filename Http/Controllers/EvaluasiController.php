<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Pengaturan\Entities\Anggota;
use Illuminate\Support\Facades\DB;
use Modules\Cuti\Services\AtasanService;
use Modules\Pengaturan\Entities\Pejabat;
use Modules\Penilaian\Entities\EvaluasiPeriodik;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\PengajuanRealisasiPeriodik;
use Modules\Penilaian\Entities\PenilaianHasilKerja;
use Modules\Penilaian\Entities\PenilaianPerilakuKerja;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Entities\RealisasiPeriodik;
use Modules\SuratTugas\Entities\DetailSuratTugas;

class EvaluasiController extends Controller {

    protected $penilaianController; protected $periodeController; protected $rencanaController;

    public function __construct( PenilaianController $penilaianController, PeriodeController $periodeController, RencanaController $rencanaController) {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
        $this->rencanaController = $rencanaController;
    }

    public function evaluasi(){
        $tahun = $this->periodeController->periode_aktif_tahun();
        $periodes = Periode::where('tahun', $tahun)->get();
        return view('penilaian::evaluasi.backup-two-evaluasi', compact('periodes'));
    }

    public function daftarBawahan(Request $request, $id){
        try {
            $periode = Periode::findOrFail($id);
            $pegawai = $this->penilaianController->getPegawaiWhoLogin();
            $username = $pegawai->username;
            $periodeId = $this->periodeController->periode_aktif();
            $timKerjaId = $pegawai->timKerjaAnggota[0]->id ?? null;

            if (!$timKerjaId) return redirect()->back()->withErrors('Tim kerja tidak ditemukan.');
            $ketua = Pejabat::where('pegawai_id', $pegawai->id)->first();
            if (!$ketua) return redirect()->back()->withErrors('Anda bukan ketua tim kerja.');

            $query = Anggota::with([
                'timKerja',
                'pegawai.rencanakerja' => function ($query) use ($periodeId, $periode) {
                    $query->where('periode_id', $periodeId)->with([
                        'pengajuanRealisasiPeriodik' => function ($q) use ($periode) {
                            $q->where('periode_id', $periode->id);
                        },
                        'evaluasiPeriodik' => function ($q) use ($periode) {
                            $q->where('periode_id', $periode->id);
                        }
                    ]);
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
                })->whereHas('pegawai', function ($q) use ($username, $periodeId, $periode) {
                    $q->where('username', '!=', $username)
                    ->whereHas('rencanakerja', function ($r) use ($periodeId, $periode) {
                        $r->where('periode_id', $periodeId)
                            ->whereHas('pengajuanRealisasiPeriodik', function ($s) use ($periode) {
                                $s->where('periode_id', $periode->id);
                            });
                    });
                });

            $bawahan = $query->paginate(10);
            if($request->query('params') == 'json'){
                return response()->json($bawahan);
            }
            return view('penilaian::evaluasi.evaluasi-triwulan-detail', compact('bawahan', 'periode'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Terjadi kesalahan saat memuat data evaluasi.');
        }
    }

    public function evaluasiDetail2(Request $request, $periodeId, $username) {
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $rencana = $this->getRencana2($username, $periodeId);
        $pegawai = Pegawai::with(['timKerjaAnggota','rencanaKerja.hasilKerja',
        'timKerjaAnggota.unit', 'timKerjaAnggota.subUnits.unit','timKerjaAnggota.parentUnit.unit',
        ])->where('username', '=', $username)->first();

        $suratTugas = $this->penilaianController->getSuratTugas2($pegawai->id, $periodeId);
        $hasiKerjaRecommendation = $this->hasilKerjaRecommendation($rencana, $pegawaiWhoLogin->id, $suratTugas);
        $perilakuRecommendation = $this->perilakuRecommendation($rencana->perilakuKerja, $pegawaiWhoLogin->id);


        $atasanService = new AtasanService();
        $ketua = $atasanService->getAtasanPegawai($pegawai->id);
        $rekapKehadiran = $this->penilaianController->getRekapKehadiran2($username, $periodeId);

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
        // $hasilKerja = $rencana->hasilKerja()
        // ->whereHas('realisasiPeriodik', function ($q) use ($periodeId) {
        //     $q->where('periode_id', $periodeId);
        // })->with('realisasiPeriodik')->get();

        if($request->query('params') == 'json') return response()->json($suratTugas);
        else return view('penilaian::evaluasi.evaluasi-detail',
            compact('periodeId','suratTugas', 'pegawaiWhoLogin', 'pegawai', 'rencana', 'hasiKerjaRecommendation', 'perilakuRecommendation', 'rekapKehadiran', 'hasilKerjaTambahan')
        );
    }

    public function batalkanEvaluasi(Request $request, $periodeId, $username){
        $pegawai = Pegawai::where('username', $username)->first();
        $rencana = RencanaKerja::where('id', $request->rencana_kerja_id)->where('pegawai_id', $pegawai->id)->first();
        if($rencana->id != $request->rencana_kerja_id) return redirect()->back()->with('failed', 'Rencana kerja tidak valid');
        else {
            try {
                DB::transaction(function () use ($request, $periodeId) {
                    EvaluasiPeriodik::updateOrCreate(
                        [
                            'rencana_kerja_id' => $request->rencana_kerja_id,
                            'periode_id' => $periodeId,
                        ],
                        [
                            'rating_hasil_kerja' => null,
                            'deskripsi_rating_hasil_kerja' => null,
                            'rating_perilaku' => null,
                            'deskripsi_rating_perilaku' => null,
                            'predikat' => null
                        ]
                    );
                    PengajuanRealisasiPeriodik::where([
                        'rencana_id' => $request->rencana_kerja_id,
                        'periode_id' => $periodeId,
                    ])->update([
                        'status' => 'Sudah Dievaluasi'
                    ]);
                });
                return redirect()->back()->with('success', 'Berhasil membatalkan evaluasi');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function ubahUmpanBalik($id){
        $pegawai = Pegawai::where('id', $id)->first();
        $periodeId = $this->periodeController->periode_aktif();
        try {
            RencanaKerja::where('periode_id', $periodeId)->where('pegawai_id', $id)->update([
                'proses_umpan_balik' => False
            ]);
            return response()->json(['success' => true, 'message' => 'Berhasil diubah']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

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

    public function predikatValue($input) {
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

    public function getRencana2($username, $id){
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        $periode = Periode::find($id);
        $pegawai = Pegawai::with([
            'timKerjaAnggota',
            'rencanaKerja.hasilKerja',
            'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit',
            'timKerjaAnggota.parentUnit.unit',
        ])->where('username', '=', $username)->first();

        $rencana = RencanaKerja::with([
            'hasilKerja.parent.rencanakerja',
            'hasilKerja.parent',
            'perilakuKerja',
            'evaluasiPeriodik' => function($query) use ($periode){
                $query->where('periode_id', $periode->id);
            },
            'hasilKerja' => function ($query) use ($periode) {
                $query->whereHas('realisasiPeriodik', function ($q) use ($periode) {
                    $q->whereNotNull('realisasi')
                    ->where('periode_id', $periode->id);
                })->with(['realisasiPeriodik' => function ($q) use ($periode) {
                    $q->whereNotNull('realisasi')
                    ->where('periode_id', $periode->id);
                }]);
            },
            'hasilKerja.penilaian' => function ($query) use ($pegawaiWhoLogin) {
                $query->where('ketua_tim_id', $pegawaiWhoLogin->id);
            },
            'perilakuKerja.rencanaPerilaku.penilaianPerilakuKerja' => function ($query) use ($pegawaiWhoLogin, $periode) {
                $query->where('ketua_tim_id', $pegawaiWhoLogin->id)
                    ->where('periode_id', $periode->id);
            },
            'perilakuKerja' => function ($query) use ($periodeId, $pegawai) {
                $query->with(['rencanaPerilaku' => function ($q) use ($periodeId, $pegawai) {
                    $q->whereHas('rencanakerja', function ($qr) use ($periodeId, $pegawai) {
                        $qr->where('periode_id', $periodeId)
                            ->where('pegawai_id', $pegawai->id);
                    });
                }]);
            }
        ])->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();

        return $rencana;
    }

    public function prosesUmpanBalik2(Request $request, $periodeId, $id){
        $request->validate([
            'feedback.*.umpan_balik_predikat' => 'required|string',
            'feedback.*.umpan_balik_deskripsi' => 'required|string',
            'feedback_hasil_kerja_tambahan.*.umpan_balik_predikat' => 'required|string',
            'feedback_hasil_kerja_tambahan.*.umpan_balik_deskripsi' => 'required|string',
            'feedback_perilaku_kerja.*.perilaku_umpan_balik_predikat' => 'required|string',
            'feedback_perilaku_kerja.*.perilaku_umpan_balik_deskripsi' => 'nullable|string',
        ]);
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
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

            foreach ($request->feedback_hasil_kerja_tambahan ?? [] as $item) {
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
                        'periode_id' => $periodeId
                    ],
                    [
                        'umpan_balik_predikat' => $item['perilaku_umpan_balik_predikat'],
                        'umpan_balik_deskripsi' => $item['perilaku_umpan_balik_deskripsi'] ?? null,
                    ]);
            }

            // RencanaKerja::where('periode_id', $periodeId)->where('pegawai_id', $id)->update([
            //     'proses_umpan_balik' => True
            // ]);
            DB::commit();
            return redirect()->back()->with('success', 'proses umpan balik berhasil');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function simpanHasilEvaluasi2(Request $request, $periodeId, $id) {
        try {
            if($request->rating_hasil_kerja == null && $request->rating_perilaku == null) return redirect()->back()->with('failed', 'Lengkapi rating hasil kerja dan perilaku');
            else {
                DB::transaction(function () use ($request, $periodeId) {
                    EvaluasiPeriodik::updateOrCreate(
                        [
                            'rencana_kerja_id' => $request->rencana_kerja_id,
                            'periode_id' => $periodeId,
                        ],
                        [
                            'rating_hasil_kerja' => $request->rating_hasil_kerja,
                            'deskripsi_rating_hasil_kerja' => $request->deskripsi_rating_hasil_kerja,
                            'rating_perilaku' => $request->rating_perilaku,
                            'deskripsi_rating_perilaku' => $request->deskripsi_rating_perilaku,
                            'predikat' => $this->predikatKinerja($request->rating_hasil_kerja, $request->rating_perilaku)
                        ]
                    );
                    PengajuanRealisasiPeriodik::where([
                        'rencana_id' => $request->rencana_kerja_id,
                        'periode_id' => $periodeId,
                    ])->update([
                        'status' => 'Sudah Dievaluasi'
                    ]);
                });
                return redirect()->back()->with('success', 'Berhasil menyimpan hasil evaluasi');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
