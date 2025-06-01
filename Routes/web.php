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
    Route::prefix('penilaian')->group(function() {
        Route::prefix('periode')->group(function() {
            Route::get('/', 'PeriodeController@index');
            Route::post('/store', 'PeriodeController@store');
            Route::post('/set', 'PeriodeController@setPeriode');
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
            Route::post('/store-hasil-kerja/{id}', 'RencanaController@storeHasilKerja');
        });
        Route::prefix('matriks-peran-hasil')->group(function() {
            Route::get('/', 'MatriksPeranHasilController@matriksperanhasil');
            Route::post('/store/{id}', 'MatriksPeranHasilController@storeCascading');
            Route::get('/anggota', 'MatriksPeranHasilController@getAnggota');
        });
        Route::get('/kinerja-organisasi', 'PenilaianController@kinerjaOrganisasi');
        Route::get('/predikat-kinerja', 'EvaluasiController@predikatKinerja');
        Route::get('/', 'PenilaianController@index');
    });
});
