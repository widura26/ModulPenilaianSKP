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
use Modules\Penilaian\Entities\DefinisiOperasional;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\Indikator;
use Modules\Penilaian\Entities\Lampiran;
use Modules\Penilaian\Entities\PerilakuKerja;
use Modules\Penilaian\Entities\PeriodeAktif;
use Modules\Penilaian\Entities\RencanaPerilaku;

class RencanaController extends Controller
{

    protected $penilaianController;
    protected $periodeController;

    public function __construct(PenilaianController $penilaianController, PeriodeController $periodeController)
    {
        $this->penilaianController = $penilaianController;
        $this->periodeController = $periodeController;
    }

    public function getRencana($username)
    {
        $pegawaiWhoLogin = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

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
            'hasilKerja.penilaian' => function ($query) use ($pegawaiWhoLogin) {
                $query->where('ketua_tim_id', $pegawaiWhoLogin->id);
            },
            'perilakuKerja.rencanaPerilaku.penilaianPerilakuKerja' => function ($query) use ($pegawaiWhoLogin) {
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
            }
        ])
            ->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();

        return $rencana;
    }

    public function getAnggota(Request $request)
    {
        try {
            $pegawai = $this->penilaianController->getPegawaiWhoLogin();

            $timKerjaId = $pegawai->timKerjaAnggota[0]->id;

            $bawahan = Anggota::with(['timKerja', 'pegawai'])
                ->where(function ($query) use ($timKerjaId) {
                    $query->whereHas(
                        'timKerja',
                        function ($q) use ($timKerjaId) {
                            $q->where('parent_id', $timKerjaId);
                        }
                    )->orWhere(
                        function ($q) use ($timKerjaId) {
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

    public function index(Request $request)
    {

        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $rencana = RencanaKerja::with('hasilKerja.lampirans')->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();
        $dataLengkap = false;
        $statusTombol = 'buat'; // default

        if ($rencana) {
            $jumlahHasil = $rencana->hasilKerja->count();
            $jumlahLampiran = $rencana->hasilKerja->flatMap->lampirans->count();

            // Cek kelengkapan indikator & definisi operasional
            $dataLengkap = $rencana->hasilKerja->filter(fn($hasil) => $hasil->jenis === 'utama')
                ->every(function ($hasil) {
                    return $hasil->indikator->count() > 0 &&
                        $hasil->indikator->every(fn($indikator) => $indikator->definisiOperasional->count() > 0);
                });

            // Ganti status tombol
            if ($rencana->status_persetujuan === 'Sudah Diajukan') {
                $statusTombol = 'batalkan';
            } elseif ($dataLengkap && $jumlahLampiran > 0) {
                $statusTombol = 'ajukan';
            } else {
                $statusTombol = 'reset';
            }
        }


        $indikatorIntervensi = Cascading::with('indikator.hasilKerja.rencanakerja.pegawai.timKerjaAnggota')->where('pegawai_id', $pegawai->id)->get();
        $parentHasilKerja = $indikatorIntervensi->pluck('indikator.hasilKerja')->unique('id')->values();

        $atasanService = new AtasanService();
        $ketua = $atasanService->getAtasanPegawai($pegawai->id);
        $definisiOperasional = DefinisiOperasional::all();
        // $definisiOperasional = \Modules\Penilaian\Entities\DefinisiOperasional::all();
        // Ambil seluruh kombinasi topik-sub_topik yang unik
        $dataUnik = DefinisiOperasional::select('topik', 'sub_topik')
            ->distinct()
            ->orderBy('topik')
            ->orderBy('sub_topik')
            ->get();

        // Atau jika hanya ingin sub_topik-nya unik saja:
        $subTopikUnik = DefinisiOperasional::select('sub_topik')
            ->distinct()
            ->orderBy('sub_topik')
            ->get();

        if ($request->query('params') == 'json') {
            return response()->json([
                'parent_hasil_kerja' => $parentHasilKerja
            ]);
        } else {
            return view('penilaian::rencana.rencana', compact('rencana', 'pegawai', 'parentHasilKerja', 'definisiOperasional', 'dataUnik', 'subTopikUnik', 'dataLengkap', 'statusTombol'));
            // return view('penilaian::rencana.rencana-skp', compact('pegawai', 'rencana', 'parentHasilKerja'));
        }
    }

    public function ajukanSKP($id)
    {
        $rencana = RencanaKerja::findOrFail($id);
        $rencana->update(['status_persetujuan' => 'Sudah Diajukan']);
        return redirect()->back()->with('success', 'SKP berhasil diajukan.');
    }

    public function batalkanPengajuan($id)
    {
        $rencana = RencanaKerja::findOrFail($id);
        $rencana->update(['status_persetujuan' => 'Belum Ajukan SKP']);
        return redirect()->back()->with('success', 'Pengajuan SKP dibatalkan.');
    }

    public function resetSKP($id)
    {
        DB::transaction(function () use ($id) {
            $rencana = RencanaKerja::findOrFail($id);
            foreach ($rencana->hasilKerja as $hasil) {
                foreach ($hasil->indikator as $indikator) {
                    $indikator->definisiOperasional()->delete();
                    $indikator->delete();
                }
                $hasil->lampirans()->delete();
                $hasil->delete();
            }

            $rencana->perilakuKerja()->delete();
            $rencana->delete();
        });

        return redirect()->back()->with('success', 'SKP berhasil direset.');
    }


    public function store()
    {
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        $perilakuList = PerilakuKerja::all();
        if (is_null($periodeId)) {
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

    public function storeHasilKerjaUtama(Request $request, $id)
    {
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

    public function storeHasilKerjaTambahan(Request $request, $id)
    {
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

    public function editHasilKerja($id)
    {
        // $hasilKerja = HasilKerja::with('deskripsi')->findOrFail($id);
        // return response()->json($hasilKerja);
        $hasilKerja = HasilKerja::findOrFail($id); // benar
        return response()->json($hasilKerja);
    }

    public function updateHasilKerja(Request $request, $id)
    {
        try {
            $hasilKerja = HasilKerja::findOrFail($id);

            $hasilKerja->update([
                'deskripsi' => $request->deskripsi, // dari field 'deskripsi' baru
            ]);

            return redirect()->back()->with('success', 'Berhasil mengubah deskripsi hasil kerja');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeManualIndikator(Request $request, $id)
    {
        try {
            // DefinisiOperasional::create([
            //     'hasil_kerja_id' => $request->hasil_kerja_id,
            //     'topik' => $request->topik,
            //     'sub_topik' => $request->sub_topik,
            //     'deskripsi' => $request->deskripsi
            // ]);

            DefinisiOperasional::create([
                'hasil_kerja_id' => $request->hasil_kerja_id,
                'indikator_id' => $request->indikator_id,
                'topik' => $request->topik,
                'sub_topik' => $request->sub_topik,
                'deskripsi' => $request->deskripsi
            ]);


            return redirect()->back()->with('success', 'Indikator berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeLampiran(Request $request)
    {
        $request->validate([
            'jenis_lampiran' => 'required|in:Dukungan Sumber Daya,Skema Pertanggung Jawaban,Konsekuensi',
            'deskripsi_lampiran' => 'required',
            'hasil_kerja_id' => 'required|exists:skp_hasil_kerja,id'
        ]);

        $deskripsiArray = array_map('trim', explode(';', $request->deskripsi_lampiran));

        foreach ($deskripsiArray as $deskripsi) {
            Lampiran::create([
                'jenis_lampiran' => $request->jenis_lampiran,
                'deskripsi_lampiran' => $deskripsi,
                'hasil_kerja_id' => $request->hasil_kerja_id,
            ]);
        }

        return redirect()->back()->with('success', 'Lampiran berhasil ditambahkan.');
    }

    public function salinSKP()
    {
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();

        $rencanaLama = RencanaKerja::where('pegawai_id', $pegawai->id)
            ->where('periode_id', '<', $periodeId)
            ->latest('periode_id')
            ->first();

        if (!$rencanaLama || $rencanaLama->tim_kerja_id !== session('tim_kerja_id')) {
            return redirect()->back()->with('failed', 'Tidak dapat menyalin SKP karena tim kerja berbeda atau tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $rencanaBaru = RencanaKerja::create([
                'tim_kerja_id' => session('tim_kerja_id'),
                'periode_id' => $periodeId,
                'status_persetujuan' => 'Belum Ajukan SKP',
                'status_realisasi' => 'Belum Ajukan Realisasi',
                'pegawai_id' => $pegawai->id
            ]);

            foreach ($rencanaLama->hasilKerja as $hasil) {
                $hasilBaru = HasilKerja::create([
                    'rencana_id' => $rencanaBaru->id,
                    'deskripsi' => $hasil->deskripsi,
                    'jenis' => $hasil->jenis
                ]);

                foreach ($hasil->indikator as $indikator) {
                    $indikatorBaru = Indikator::create([
                        'hasil_kerja_id' => $hasilBaru->id,
                        'deskripsi' => $indikator->deskripsi
                    ]);

                    foreach ($indikator->definisiOperasional as $definisi) {
                        DefinisiOperasional::create([
                            'hasil_kerja_id' => $hasilBaru->id,
                            'indikator_id' => $indikatorBaru->id,
                            'topik' => $definisi->topik,
                            'sub_topik' => $definisi->sub_topik,
                            'deskripsi' => $definisi->deskripsi
                        ]);
                    }
                }

                foreach ($hasil->lampirans as $lampiran) {
                    Lampiran::create([
                        'hasil_kerja_id' => $hasilBaru->id,
                        'jenis_lampiran' => $lampiran->jenis_lampiran,
                        'deskripsi_lampiran' => $lampiran->deskripsi_lampiran
                    ]);
                }
            }

            $perilakuList = PerilakuKerja::all();
            foreach ($perilakuList as $perilaku) {
                RencanaPerilaku::create([
                    'rencana_id' => $rencanaBaru->id,
                    'perilaku_kerja_id' => $perilaku->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'SKP berhasil disalin.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Gagal menyalin SKP: ' . $th->getMessage());
        }
    }

    public function updateIndikator(Request $request, $id)
    {
        try {
            $indikator = Indikator::findOrFail($id);
            $indikator->deskripsi = $request->indikator;
            $indikator->save();

            return redirect()->back()->with('success', 'Indikator berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui indikator: ' . $e->getMessage());
        }
    }
}
