<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'permission']], function () {
    Route::prefix('skp')->group(function () {
        Route::prefix('persetujuan')->group(function () {
            // Route::get('/', 'PersetujuanController@persetujuanSkp');
            Route::get('/', 'PersetujuanController@index');
            Route::get('/detail/{id}', 'PersetujuanController@detail');
            Route::post('/ekspektasi/{rencana_id}', 'PersetujuanController@simpanEkspektasiPerilaku');
            Route::post('/setujui/{id}', 'PersetujuanController@setujui');
            Route::post('/tolak/{id}', 'PersetujuanController@tolak');
            Route::post('/setujui-terpilih', 'PersetujuanController@setujuiTerpilih');
            Route::post('/tolak-terpilih', 'PersetujuanController@tolakTerpilih');
        });
        Route::prefix('periode')->group(function () {
            Route::get('/', 'PeriodeController@index');
            Route::post('/store', 'PeriodeController@store');
            Route::post('/update/{id}', 'PeriodeController@update');
            Route::post('/set', 'PeriodeController@setPeriode');
            Route::get('/{id}', 'PeriodeController@detail');
            Route::post('/delete/{id}', 'PeriodeController@delete');
        });
        Route::prefix('preview')->group(function () {
            Route::get('/evaluasi', 'PreviewController@previewEvaluasi');
            Route::get('/dok-evaluasi', 'PreviewController@previewDokEvaluasi');
            Route::get('/backup-evaluasi', 'PreviewController@backupPreviewEvaluasi');
            Route::get('/backup-dok-evaluasi', 'PreviewController@backupPreviewDokEvaluasi');
        });
        Route::prefix('cetak')->group(function () {
            Route::get('/evaluasi', 'PrintController@cetakEvaluasi');
            Route::get('/dok-evaluasi', 'PrintController@cetakDokEvaluasi');
        });
        Route::prefix('evaluasi')->group(function () {
            Route::get('/', 'EvaluasiController@evaluasi');
            Route::get('/periode/{id}', 'EvaluasiController@daftarBawahan');
            Route::get('/{periodeId}/{username}', 'EvaluasiController@evaluasiDetail2');
            Route::post('{periodeId}/proses-umpan-balik/{id}', 'EvaluasiController@prosesUmpanBalik2');
            Route::post('{periodeId}/simpan-hasil-evaluasi/{id}', 'EvaluasiController@simpanHasilEvaluasi2');
            Route::post('{periodeId}/batalkan-evaluasi/{username}', 'EvaluasiController@batalkanEvaluasi');
            Route::get('/data-pegawai', 'EvaluasiController@index');
            Route::post('ubah-umpan-balik/{id}', 'EvaluasiController@ubahUmpanBalik');
        });
        Route::prefix('realisasi')->group(function () {
            Route::get('/{triwulan}', 'RealisasiController@realisasi');
            Route::post('/{periodeId}/update-realisasi/{hasilKerjaId}', 'RealisasiController@createOrUpdateRealisasi2');
            Route::post('/{periodeId}/ajukan-realisasi/{rencanaId}', 'RealisasiController@ajukanRealisasi2');
            Route::post('{periodeId}/delete/{hasilKerjaId}', 'RealisasiController@deleteRealisasi2');
            Route::post('{periodeId}/batalkan-realisasi/{rencanaId}', 'RealisasiController@batalkanPengajuanRealisasi2');
        });
        Route::prefix('rencana')->group(function () {
            Route::get('/', 'RencanaController@index');
            Route::post('/store', 'RencanaController@store');
            Route::post('/salin-skp', 'RencanaController@salinSKP');
            Route::post('/store-hasil-kerja-utama/{id}', 'RencanaController@storeHasilKerjaUtama');
            Route::post('/store-hasil-kerja-tambahan/{id}', 'RencanaController@storeHasilKerjaTambahan');
            Route::post('/store-manual-indikator-utama/{id}', 'RencanaController@storeManualIndikator');
            Route::get('/edit-hasil-kerja-utama/{id}', 'RencanaController@editHasilKerja');
            Route::put('/update-hasil-kerja-utama/{id}', 'RencanaController@updateHasilKerja');
            Route::put('/edit-indikator-utama/{id}', 'RencanaController@updateIndikator');
            Route::post('/store-lampiran', 'RencanaController@storeLampiran');
            Route::put('/ajukan/{id}', 'RencanaController@ajukanSKP');
            Route::put('/batalkan-pengajuan/{id}', 'RencanaController@batalkanPengajuan');
            Route::delete('/reset/{id}', 'RencanaController@resetSKP');
            Route::get('/backup-cetak/{id}', 'RencanaController@backupCetak');
            Route::get('/cetak/{id}', 'RencanaController@cetak');
            Route::delete('/hasil-kerja/{id}', 'RencanaController@destroyHasilKerja');
            Route::delete('/indikator/{id}', 'RencanaController@destroyIndikator');
        });
        Route::prefix('matriks-peran-hasil')->group(function () {
            Route::get('/', 'MatriksPeranHasilController@matriksperanhasil');
            Route::post('/store/{id}', 'MatriksPeranHasilController@storeCascading');
            Route::get('/anggota', 'MatriksPeranHasilController@getAnggota');
        });
        Route::prefix('kinerja-organisasi')->group(function () {
            Route::get('/', 'CapaianKinerjaOrganisasiController@kinerjaOrganisasi');
            Route::post('/set-tahun', 'CapaianKinerjaOrganisasiController@setTahun');
            Route::post('/set-capaian-kinerja', 'CapaianKinerjaOrganisasiController@setCapaianKinerja');
        });
        Route::prefix('arsip-skp')->group(function () {
            Route::get('/', 'ArsipController@index');
            Route::prefix('rencana')->group(function () {
                Route::get('/', 'ArsipController@index');
                Route::get('/pegawai', 'ArsipController@getArsipRencana');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::prefix('evaluasi')->group(function () {
                Route::get('/', 'ArsipController@evaluasi');
                Route::get('/pegawai', 'ArsipController@getArsipEvaluasi');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::prefix('dok-evaluasi')->group(function () {
                Route::get('/', 'ArsipController@dokevaluasi');
                Route::get('/pegawai', 'ArsipController@getArsipDokEvaluasi');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::post('/store', 'ArsipController@store');
            Route::post('/update/{id}', 'ArsipController@update');
            Route::post('/delete/{id}', 'ArsipController@delete');
        });

        Route::prefix('monitoring')->group(function () {
            Route::get('/', 'MonitoringController@index');
            Route::get('/data', 'MonitoringController@monitoring');
        });
        Route::get('/predikat-kinerja', 'EvaluasiController@predikatKinerja');
        Route::get('/', 'PenilaianController@index');
        Route::prefix('persetujuan-skp')->group(function () {
            Route::get('/', 'PersetujuanController@persetujuanSkp');
        });
        Route::prefix('unggah-skp')->group(function () {
            Route::get('/', 'UnggahController@unggahSkp');
        });

        Route::prefix('intervensi')->group(function () {
            Route::get('/', 'IntervensiController@index');
            Route::post('/store-intervensi/{id}', 'IntervensiController@storeDeskripsiHasilKerja');
        });

        Route::prefix('definisi-operasional')->group(function () {
            Route::get('/', 'DefinisiOperasionalController@index');
            Route::post('/store', 'DefinisiOperasionalController@store');
            Route::put('/update/{id}', 'DefinisiOperasionalController@update');
            Route::delete('/delete/{id}', 'DefinisiOperasionalController@destroy');
        });

        // Route::prefix('lampirans')->group(function () {
        //     Route::get('/', 'DefinisiOperasionalController@index');
        //     Route::post('/store', 'DefinisiOperasionalController@store');
        //     Route::put('/update/{id}', 'DefinisiOperasionalController@update');
        //     Route::delete('/delete/{id}', 'DefinisiOperasionalController@destroy');
        // });
    });
});
