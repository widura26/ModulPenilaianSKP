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

Route::group(['middleware' => ['auth', 'permission']], function() {
    Route::prefix('skp')->group(function() {
        Route::prefix('periode')->group(function() {
            Route::get('/', 'PeriodeController@index');
            Route::post('/store', 'PeriodeController@store');
            Route::post('/update/{id}', 'PeriodeController@update');
            Route::post('/set', 'PeriodeController@setPeriode');
            Route::get('/{id}', 'PeriodeController@detail');
        });
        Route::prefix('preview')->group(function() {
            Route::get('/evaluasi', 'PreviewController@previewEvaluasi');
            Route::get('/dok-evaluasi', 'PreviewController@previewDokEvaluasi');
            Route::get('/backup-evaluasi', 'PreviewController@backupPreviewEvaluasi');
            Route::get('/backup-dok-evaluasi', 'PreviewController@backupPreviewDokEvaluasi');
        });
        Route::prefix('cetak')->group(function() {
            Route::get('/evaluasi', 'PrintController@cetakEvaluasi');
            Route::get('/dok-evaluasi', 'PrintController@cetakDokEvaluasi');
        });
        Route::prefix('evaluasi')->group(function() {
            Route::get('/', 'EvaluasiController@evaluasi');
            Route::get('/data-pegawai', 'EvaluasiController@index');
            Route::get('/{username}/detail', 'EvaluasiController@evaluasiDetail');
            Route::post('/batalkan-evaluasi/{username}', 'EvaluasiController@batalkanEvaluasi');
            Route::post('proses-umpan-balik/{username}', 'EvaluasiController@prosesUmpanBalik');
            Route::post('simpan-hasil-evaluasi/{id}', 'EvaluasiController@simpanHasilEvaluasi');
        });
        Route::prefix('realisasi')->group(function() {
            Route::get('/', 'RealisasiController@realisasi');
            Route::post('/update-realisasi/{id}', 'RealisasiController@updateRealisasi');
            Route::post('/ajukan-realisasi/{id}', 'RealisasiController@ajukanRealisasi');
            Route::post('/delete/{id}', 'RealisasiController@deleteRealisasi');
            Route::post('/batalkan-realisasi/{id}', 'RealisasiController@batalkanPengajuanRealisasi');
        });
        Route::prefix('rencana')->group(function() {
            Route::get('/', 'RencanaController@index');
            Route::post('/store', 'RencanaController@store');
            Route::post('/store-hasil-kerja-utama/{id}', 'RencanaController@storeHasilKerjaUtama');
            Route::post('/store-hasil-kerja-tambahan/{id}', 'RencanaController@storeHasilKerjaTambahan');
        });
        Route::prefix('matriks-peran-hasil')->group(function() {
            Route::get('/', 'MatriksPeranHasilController@matriksperanhasil');
            Route::post('/store/{id}', 'MatriksPeranHasilController@storeCascading');
            Route::get('/anggota', 'MatriksPeranHasilController@getAnggota');
        });
        Route::prefix('kinerja-organisasi')->group(function() {
            Route::get('/', 'CapaianKinerjaOrganisasiController@kinerjaOrganisasi');
            Route::post('/set-tahun', 'CapaianKinerjaOrganisasiController@setTahun');
            Route::post('/set-capaian-kinerja', 'CapaianKinerjaOrganisasiController@setCapaianKinerja');
        });
        Route::prefix('arsip-skp')->group(function() {
            Route::get('/', 'ArsipController@index');
            Route::prefix('rencana')->group(function() {
                Route::get('/', 'ArsipController@index');
                Route::get('/pegawai', 'ArsipController@getArsipRencana');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::prefix('evaluasi')->group(function() {
                Route::get('/', 'ArsipController@evaluasi');
                Route::get('/pegawai', 'ArsipController@getArsipEvaluasi');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::prefix('dok-evaluasi')->group(function() {
                Route::get('/', 'ArsipController@dokevaluasi');
                Route::get('/pegawai', 'ArsipController@getArsipDokEvaluasi');
                Route::get('/detail/{arsip}', 'ArsipController@detail');
                Route::post('/verifikasi/{arsip}', 'ArsipController@verification');
            });
            Route::post('/store', 'ArsipController@store');
            Route::post('/update/{id}', 'ArsipController@update');
            Route::post('/delete/{id}', 'ArsipController@delete');
        });
        Route::prefix('monitoring')->group(function() {
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
    });
});
