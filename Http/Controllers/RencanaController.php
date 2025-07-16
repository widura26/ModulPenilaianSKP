<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\RencanaKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
use Barryvdh\DomPDF\Facade\Pdf;

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
        // $data = $this->getDataSkpLengkap($pegawai, $periodeId);


        $rencana = RencanaKerja::with(['lampirans', 'hasilKerja'])->where('periode_id', $periodeId)->where('pegawai_id', '=', $pegawai->id)->first();
        $dataLengkap = false;

        $statusTombol = 'buat';

        if ($rencana) {
            $jumlahHasilUtama = $rencana->hasilKerja->where('jenis', 'utama')->count();
            $jumlahLampiran = $rencana->lampirans->count();

            $dataLengkap = $rencana->hasilKerja->where('jenis', 'utama')->every(function ($hasil) {
                return $hasil->indikator->count() > 0;
            });

            if ($rencana->status_pengajuan === 'Sudah Diajukan') {
                if ($rencana->status_persetujuan === 'Sudah Disetujui') {
                    $statusTombol = 'cetak';
                } elseif ($rencana->status_persetujuan === 'Tolak') {
                    $statusTombol = 'ditolak';
                } else {
                    $statusTombol = 'batalkan';
                }
            } else {
                if ($jumlahHasilUtama > 0) {
                    $statusTombol = 'ajukan';
                } else {
                    $statusTombol = 'reset';
                }
            }
        }

        // dd($statusTombol);


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
            // Log::info('Rencana ditemukan:', ['rencana_id' => optional($rencana)->id]);
            // Log::info('STATUS TOMBOL:', [$data['statusTombol']]);
            // Log::info('HASIL KERJA COUNT:', [$data['rencana']?->hasilKerja->count()]);

            // return view('penilaian::rencana.rencana', [
            //     'rencana' => $data['rencana'],
            //     'statusTombol' => $data['statusTombol'],
            //     'dataLengkap' => $data['dataLengkap'],
            //     'pegawai' => $pegawai,
            //     'parentHasilKerja' => $parentHasilKerja,
            //     'definisiOperasional' => $definisiOperasional,
            //     'dataUnik' => $dataUnik,
            //     'subTopikUnik' => $subTopikUnik
            // ]);

            return view('penilaian::rencana.rencana', compact('rencana', 'pegawai', 'parentHasilKerja', 'definisiOperasional', 'dataUnik', 'subTopikUnik', 'dataLengkap', 'statusTombol'));
            // return view('penilaian::rencana.rencana-skp', compact('pegawai', 'rencana', 'parentHasilKerja'));
        }
    }

    public function ajukanSKP($id)
    {
        // $rencana = RencanaKerja::findOrFail($id);
        // $rencana->update(['status_pengajuan' => 'Sudah Diajukan']);
        // return redirect()->back()->with('success', 'SKP berhasil diajukan.');
        // throw $th;

        try {
            $rencana = RencanaKerja::findOrFail($id);
            $rencana->update(['status_pengajuan' => 'Sudah Diajukan']);
            return redirect()->back()->with('success', 'SKP berhasil diajukan.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal mengajukan SKP: ' . $th->getMessage());
        }
    }

    public function batalkanPengajuan($id)
    {

        // return redirect()->back()->with('success', 'Pengajuan SKP disetujui.');
        // try {
        //     $rencana = RencanaKerja::findOrFail($id);
        //     $rencana->update(['status_persetujuan' => 'Sudah Disetujui']);
        //     return redirect()->back()->with('success', 'SKP berhasil dibatalkan.');
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with('error', 'Gagal batalkan SKP: ' . $th->getMessage());
        // }
        try {
            $rencana = RencanaKerja::findOrFail($id);
            $rencana->update([
                'status_pengajuan' => 'Belum Diajukan',
                'status_persetujuan' => 'Belum Disetujui'
            ]);
            return redirect()->back()->with('success', 'Pengajuan SKP berhasil dibatalkan.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal membatalkan pengajuan SKP: ' . $th->getMessage());
        }
    }

    public function resetSKP($id)
    {
        // DB::transaction(function () use ($id) {
        //     $rencana = RencanaKerja::findOrFail($id);
        //     foreach ($rencana->hasilKerja as $hasil) {
        //         foreach ($hasil->indikator as $indikator) {
        //             $indikator->definisiOperasional()->delete();
        //             $indikator->delete();
        //         }
        //         $hasil->lampirans()->delete();
        //         $hasil->delete();
        //     }

        //     $rencana->perilakuKerja()->delete();
        //     $rencana->delete();
        // });

        // return redirect()->back()->with('success', 'SKP berhasil direset.');

        try {
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

                // $rencana->perilakuKerja()->delete();
                $rencana->perilakuKerja()->detach();
                $rencana->delete();
            });

            return redirect()->back()->with('success', 'SKP berhasil direset.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal mereset SKP: ' . $th->getMessage());
        }
    }


    public function store()
    {
        // dd('store masuk');
        $pegawai = $this->penilaianController->getPegawaiWhoLogin();
        $periodeId = $this->periodeController->periode_aktif();
        // $timKerjaId = session('tim_kerja_id');
        // if (is_null($timKerjaId)) {
        //     return redirect()->back()->with('error', 'Tim kerja tidak ditemukan. Silakan login ulang atau hubungi admin.');
        // }

        $perilakuList = PerilakuKerja::all();
        if (is_null($periodeId)) {
            return redirect()->back()->with('failed', 'Periode belum diset');
        }
        DB::beginTransaction();
        try {
            // $data = [
            //     'tim_kerja_id' => session('tim_kerja_id'),
            //     'periode_id' => $periodeId,
            //     'status_persetujuan' => 'Belum Ajukan SKP',
            //     'status_realisasi' =>  'Belum Ajukan Realisasi',
            //     'pegawai_id' => $pegawai->id
            // ];

            // dd($data);
            $rencana = RencanaKerja::create([
                'tim_kerja_id' => session('tim_kerja_id'),
                'periode_id' => $periodeId,
                'status_persetujuan' => 'Belum Disetujui',
                'status_pengajuan'  => 'Belum Diajukan',
                'status_realisasi' =>  'Belum Ajukan Realisasi',
                'pegawai_id' => $pegawai->id
            ]);

            foreach ($perilakuList as $perilaku) {
                RencanaPerilaku::create([
                    'rencana_id' => $rencana->id,
                    'perilaku_kerja_id' => $perilaku->id,
                ]);
            }

            // session()->flash('buat_skp', true);
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil Buat SKP');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            // return redirect()->back()->with('error', $th->getMessage());
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
            throw $th;
            // return response()->json($th->getMessage());
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
            throw $th;
            // return response()->json($th->getMessage());
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
            throw $th;
            // return redirect()->back()->with('error', $th->getMessage());
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
            throw $th;
            // return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeLampiran(Request $request)
    {

        $request->validate([
            'jenis_lampiran' => 'required|in:Dukungan Sumber Daya,Skema Pertanggung Jawaban,Konsekuensi',
            'deskripsi_lampiran' => 'required',
            'rencana_id' => 'required|exists:skp_rencana_kerja,id'
        ]);

        $deskripsiArray = array_filter(array_map('trim', explode(';', $request->deskripsi_lampiran)));

        foreach ($deskripsiArray as $deskripsi) {
            Lampiran::create([
                'jenis_lampiran' => $request->jenis_lampiran,
                'deskripsi_lampiran' => $deskripsi,
                'rencana_id' => $request->rencana_id,
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
                'status_persetujuan' => 'Belum Disetujui',
                'status_pengajuan'  => 'Belum Diajukan',
                'status_realisasi' =>  'Belum Ajukan Realisasi',
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

                // foreach ($rencanaLama->lampirans as $lampiran) {
                //     Lampiran::create([
                //         'rencana_id' => $rencanaBaru->id,
                //         'jenis_lampiran' => $lampiran->jenis_lampiran,
                //         'deskripsi_lampiran' => $lampiran->deskripsi_lampiran
                //     ]);
                // }
            }
            foreach ($rencanaLama->lampirans as $lampiran) {
                Lampiran::create([
                    'rencana_id' => $rencanaBaru->id,
                    'jenis_lampiran' => $lampiran->jenis_lampiran,
                    'deskripsi_lampiran' => $lampiran->deskripsi_lampiran
                ]);
            }

            $perilakuList = PerilakuKerja::all();
            foreach ($perilakuList as $perilaku) {
                RencanaPerilaku::create([
                    'rencana_id' => $rencanaBaru->id,
                    'perilaku_kerja_id' => $perilaku->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menyalin data SKP.');

            // return redirect('/skp/rencana')->with('success', 'Berhasil menyalin SKP');

            // return redirect()->route('skp.rencana')->with('success', 'SKP berhasil disalin.');
            // return redirect()->back()->with('success', 'SKP berhasil disalin.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            // return redirect()->back()->with('failed', 'Gagal menyalin SKP: ' . $th->getMessage());
        }
    }

    public function updateIndikator(Request $request, $id)
    {
        try {
            $indikator = Indikator::findOrFail($id);
            $indikator->deskripsi = $request->indikator;
            $indikator->save();

            return redirect()->back()->with('success', 'Indikator berhasil diperbarui.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal memperbarui indikator: ' . $th->getMessage());
        }
    }

    public function backupCetak($id)
    {
        try {
            $rencana = RencanaKerja::with([
                'hasilKerja.indikator.definisiOperasional',
                'lampirans',
                'perilakuKerja.rencanaPerilaku.perilakuKerja'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('penilaian::rencana.backup-cetak-rencana-page', compact('rencana'));

            return $pdf->stream('backup-cetak-rencana.pdf');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal mencetak backup: ' . $th->getMessage());
        }
    }

    public function cetak($id)
    {
        $rencana = RencanaKerja::with([
            'pegawai.timKerjaAnggota.unit',
            'pegawai.timKerjaAnggota.parentUnit.ketua.pegawai',
            'hasilKerja.indikator.definisiOperasional',
            'lampirans',
            'perilakuKerja.rencanaPerilaku.penilaianPerilakuKerja'
        ])->findOrFail($id);

        $pegawai = $rencana->pegawai;
        return view('penilaian::rencana.backup-cetak-rencana-page', compact('rencana', 'pegawai'));
    }

    public function destroyIndikator($id)
    {
        try {
            $indikator = Indikator::findOrFail($id);

            // Hapus manual indikator yang terkait
            $indikator->definisiOperasional()->delete();

            // Hapus indikator
            $indikator->delete();

            return redirect()->back()->with('success', 'Indikator dan data manual berhasil dihapus.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal menghapus indikator: ' . $e->getMessage());
        }
    }

    public function destroyHasilKerja($id)
    {
        try {
            $hasilKerja = HasilKerja::findOrFail($id);

            foreach ($hasilKerja->indikator as $indikator) {
                // Hapus semua manual indikator dulu
                $indikator->definisiOperasional()->delete();

                // Hapus indikator
                $indikator->delete();
            }

            // Hapus hasil kerja utama
            $hasilKerja->delete();

            return redirect()->back()->with('success', 'Hasil kerja utama dan seluruh indikator berhasil dihapus.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->with('error', 'Gagal menghapus hasil kerja utama: ' . $e->getMessage());
        }
    }
}
