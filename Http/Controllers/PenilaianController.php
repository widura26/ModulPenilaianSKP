<?php

namespace Modules\Penilaian\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pengaturan\Entities\Anggota;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\PeriodeAktif;
use Modules\SuratTugas\Entities\SuratTugas;
use Carbon\Carbon;
use Modules\Cuti\Entities\Cuti;
use Modules\RekapKehadiran\Entities\KehadiranII;
use Modules\Setting\Entities\Libur;

class PenilaianController extends Controller {

    public function getPegawaiWhoLogin($filterTimKerjaId = null){
        $authUser = Auth::user();
        $username = $authUser->pegawai->username;
        $pegawai = Pegawai::with([
            'timKerjaAnggota' => function ($query) use ($filterTimKerjaId) {
                if ($filterTimKerjaId) {
                    $query->where('tim_kerja_anggota.tim_kerja_id', $filterTimKerjaId);
                }
            },
            'timKerjaAnggota.ketua',
            'rencanaKerja.hasilKerja', 'timKerjaAnggota.unit',
            'timKerjaAnggota.subUnits.unit','timKerjaAnggota.parentUnit.unit',
        ])->where('username', $username)->first();

        return $pegawai;
    }

    public function getBawahan(){
        $periodeController = new PeriodeController();
        $pegawai = $this->getPegawaiWhoLogin();
        $periodeId = $periodeController->periode_aktif();
        $timKerjaId = $pegawai->timKerjaAnggota[0]->id;
        $username = $pegawai->username;
        $bawahan = Anggota::with(['timKerja', 'pegawai.rencanakerja' => function ($query) use ($periodeId) {
                        $query->where('periode_id', $periodeId);
                    }])->where(function ($query) use ($timKerjaId) {
                        $query->where(function ($q) use ($timKerjaId) {
                            $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                                $sub->where('parent_id', $timKerjaId);
                            })->where('peran', 'Ketua');
                        })
                        ->orWhere(function ($q) use ($timKerjaId) {
                            $q->whereHas('timKerja', function ($sub) use ($timKerjaId) {
                                $sub->where('id', $timKerjaId);
                            })->where('peran', 'Anggota');
                        });
                    })->whereHas('pegawai', function ($q) use ($username) {
                        $q->where('username', '!=', $username);
                    })->paginate(10);

        return $bawahan;
    }

    public function getBawahan_backup(){
        $periodeController = new PeriodeController();
        $pegawai = $this->getPegawaiWhoLogin();
        $periodeId = $periodeController->periode_aktif();
        $timKerjaId = $pegawai->timKerjaAnggota[0]->id;
        $username = $pegawai->username;
        $bawahan = Anggota::with(['timKerja', 'pegawai.rencanakerja' => function ($query) use ($periodeId) {
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
                })->whereHas('pegawai', function ($q) use ($username) {
                    $q->where('username', '!=', $username);
                })->paginate(10);

        return $bawahan;
    }

    public function getSuratTugas($pegawai_id){
        $periodeAktif = PeriodeAktif::with('periode')->where('pegawai_id', $pegawai_id)->first();
        $start_date = $periodeAktif?->periode->start_date;
        $end_date = $periodeAktif?->periode->end_date;

        $query = SuratTugas::with(['pejabat','detail','detail.pegawai','anggota.pegawai','laporan', 'detail.penilaian']);
        $surat_tugas =  $query->where(function ($q) use ($pegawai_id, $start_date, $end_date) {
                            $q->where('jenis', 'individu')
                                ->whereHas('detail', function ($q2) use ($pegawai_id, $start_date, $end_date) {
                                    $q2->where('pegawai_id', $pegawai_id)
                                    ->whereDate('tanggal_mulai', '>=', $start_date)
                                    ->whereDate('tanggal_selesai', '<=', $end_date);
                                });
                            $q->orWhere(function ($q3) use ($pegawai_id, $start_date, $end_date) {
                                $q3->where('jenis', 'tim')
                                    ->whereHas('detail', function ($q4) use ($pegawai_id, $start_date, $end_date) {
                                        $q4->where('pegawai_id', $pegawai_id)
                                        ->whereDate('tanggal_mulai', '>=', $start_date)
                                        ->whereDate('tanggal_selesai', '<=', $end_date);
                                    });
                            });
                        })->get();

        return $surat_tugas;
    }

    public function getRekapKehadiran($username){
        $pegawai = Pegawai::with(['timKerjaAnggota','rencanaKerja.hasilKerja',
        'timKerjaAnggota.unit', 'timKerjaAnggota.subUnits.unit','timKerjaAnggota.parentUnit.unit',
        ])->where('username', '=', $username)->first();

        $roles = $pegawai->user->getRoleNames()->toArray();

        //get periode aktif
        $periodeAktif = PeriodeAktif::with(['periode', 'pegawai'])->where('pegawai_id', $pegawai->id)->first();
        $periode = $periodeAktif->periode;

        $startDate = Carbon::parse($periode->start_date)->startOfDay();
        $endDate = Carbon::parse($periode->end_date)->endOfDay();
        $jumlahHari = $startDate->diffInDays($endDate) + 1;

        $pegawaiQuery = Pegawai::query();
        if (!in_array('admin', $roles) && !in_array('super', $roles)) {
            if (in_array('mahasiswa', $roles) && !now()->between($startDate, $endDate)) {
                $pegawaiQuery->whereNull('id');
            } elseif (in_array('pegawai', $roles) || in_array('dosen', $roles)) {
                $pegawaiQuery->where('username', $pegawai->user->username);
            }
        }

        $pegawaiList = $pegawaiQuery->select('id', 'nama', 'nip', 'username')->get();
        $pegawaiIDs = $pegawaiList->pluck('id')->toArray();

        $kehadiran = KehadiranII::whereBetween('checktime', [$startDate, $endDate])
        ->when(!in_array('admin', $roles), fn($q) => $q->whereIn('user_id', $pegawaiIDs))->get()
        ->groupBy(fn($item) => "{$item->user_id}|" . Carbon::parse($item->checktime)->format('Y-m-d'));

        $liburTanggal = Libur::whereBetween('tanggal', [$startDate, $endDate])
            ->pluck('tanggal')
            ->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('Y-m-d'))
            ->toArray();

        $tanggalHari = collect();
        $liburIndex = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formatted = $date->format('Y-m-d');
            $tanggalHari->push($formatted);
            $liburIndex[] = $date->isWeekend() || in_array($formatted, $liburTanggal);
        }

        // Ambil data cuti
        $cutiData = Cuti::where('status', 'Selesai')
            ->whereIn('pegawai_id', $pegawaiIDs)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('tanggal_mulai', '<=', $endDate)
                    ->whereDate('tanggal_selesai', '>=', $startDate);
            })
            ->get();

        // Mapping cuti per pegawai dan tanggal
        $cutiByPegawai = [];
        foreach ($cutiData as $cuti) {
            $mulai = Carbon::parse($cuti->tanggal_mulai);
            $selesai = Carbon::parse($cuti->tanggal_selesai);
            for ($date = $mulai->copy(); $date->lte($selesai); $date->addDay()) {
                if ($date->between($startDate, $endDate)) {
                    $cutiByPegawai[$cuti->pegawai_id][] = $date->format('Y-m-d');
                }
            }
        }

        $data = $pegawaiList->map(function ($pegawai) use ($kehadiran, $tanggalHari, $liburIndex, $cutiByPegawai, $jumlahHari) {
            $presensi = [];
            $total = ['D' => 0, 'T' => 0, 'TM' => 0, 'C' => 0, 'DL' => 0];

            foreach ($tanggalHari as $idx => $tanggal) {
                if ($liburIndex[$idx]) {
                    $presensi[] = 'L';
                    continue;
                }

                if (in_array($tanggal, $cutiByPegawai[$pegawai->id] ?? [])) {
                    $presensi[] = 'C';
                    $total['C']++;
                    continue;
                }

                $key = $pegawai->id . '|' . $tanggal;

                if ($kehadiran->has($key)) {
                    $checktypes = $kehadiran[$key]->pluck('checktype')->unique();
                    $hasI = $checktypes->contains('I');
                    $hasO = $checktypes->contains('O');

                    if ($hasI && $hasO) {
                        $presensi[] = 'D';
                        $total['D']++;
                    } elseif ($hasI || $hasO) {
                        $presensi[] = 'T';
                        $total['T']++;
                    } else {
                        $presensi[] = 'TM';
                        $total['TM']++;
                    }
                } else {
                    $presensi[] = 'TM';
                    $total['TM']++;
                }
            }

            $jumlahHariLibur = collect($presensi)->filter(fn($v) => $v === 'L')->count();
            $jumlahHariKehadiran_sesuai_ketentuan = collect($presensi)->filter(fn($v) => $v === 'D')->count();
            $jumlahHariKehadiran_tidak_sesuai_ketentuan = collect($presensi)->filter(fn($v) => $v === 'T')->count();
            $dinasLuar = collect($presensi)->filter(fn($v) => $v === 'DL')->count();
            $cuti = collect($presensi)->filter(fn($v) => $v === 'C')->count();
            $alpa = collect($presensi)->filter(fn($v) => $v === 'TM')->count();

            $hariKerja = $jumlahHari - $jumlahHariLibur - $cuti - $dinasLuar;
            $kehadiran = $this->rerataKehadiran($alpa, $hariKerja, $jumlahHariKehadiran_sesuai_ketentuan, $jumlahHariKehadiran_tidak_sesuai_ketentuan);

            return [
                'pegawai' => $pegawai->nama,
                'jumlahHari_dalam_periode' => $jumlahHari,
                'hari_libur_dalam_periode' => $jumlahHariLibur,
                'dinas_luar' => $dinasLuar,
                'cuti' => $cuti,
                'alpa' => $hariKerja - $jumlahHariKehadiran_sesuai_ketentuan - $jumlahHariKehadiran_tidak_sesuai_ketentuan,
                'hari_kerja_dalam_periode' => $hariKerja,
                'jumlahHariKehadiran_sesuai_ketentuan' => $jumlahHariKehadiran_sesuai_ketentuan,
                'jumlahHariKehadiran_tidak_sesuai_ketentuan' => $jumlahHariKehadiran_tidak_sesuai_ketentuan,
                'rerata_alpa' => $kehadiran['rerata_alpa'],
                'rerata_kehadiran_sesuai_ketentuan' => $kehadiran['rerata_kehadiran_sesuai_ketentuan'],
                'rerata_kehadiran_tidak_sesuai_ketentuan' => $kehadiran['rerata_kehadiran_tidak_sesuai_ketentuan'],
                'total' => $kehadiran['rerata_kehadiran_sesuai_ketentuan'] + $kehadiran['rerata_kehadiran_tidak_sesuai_ketentuan']
            ];
        });

        return $data[0];
    }

    public function rerataKehadiran($alpa, $hariKerja, $jumlahHariKehadiran_sesuai_ketentuan, $jumlahHariKehadiran_tidak_sesuai_ketentuan){
        $rerataKehadiranSesuaiKetentuan = $hariKerja != 0 ? ($jumlahHariKehadiran_sesuai_ketentuan * 100) / $hariKerja : 0;
        $rerataKehadiranTidakSesuaiKetentuan = $hariKerja != 0 ? ($jumlahHariKehadiran_tidak_sesuai_ketentuan * 100) / $hariKerja : 0;
        $rerataAlpa = $hariKerja != 0 ? ($alpa * 100) / $hariKerja : 0;

        return [
            'rerata_alpa' => $rerataAlpa,
            'rerata_kehadiran_sesuai_ketentuan' => $rerataKehadiranSesuaiKetentuan,
            'rerata_kehadiran_tidak_sesuai_ketentuan' => $rerataKehadiranTidakSesuaiKetentuan
        ];
    }
}
