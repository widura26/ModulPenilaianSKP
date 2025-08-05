<?php

namespace Modules\Penilaian\Tests\Unit;

use App\Models\Core\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\Periode;
use Modules\Penilaian\Entities\RealisasiPeriodik;
use Modules\Penilaian\Entities\RencanaKerja;

class RealisasiTest extends TestCase
{

    public function test_store_realisasi_data_valid(){
        $user = User::where('username', 'devit')->firstOrFail();
        $this->actingAs($user);
        $hasilKerja = HasilKerja::findOrFail(5);
        $periode = Periode::findOrFail(1);
        $payload = [
            'realisasi' => 'Unit Test Realisasi',
            'bukti_dukung' => 'https://drive.google.com/drive/folders/1RSiIW8jY6q46TGlcYP4Ee14kNteghjPH',
        ];
        $this->post("/skp/realisasi/{$periode->id}/update-realisasi/{$hasilKerja->id}", $payload)
            ->assertRedirect()
            ->assertSessionHas('success', 'Realisasi berhasil diperbarui.');
        $this->assertDatabaseHas('skp_realisasi_periodik', array_merge([
            'periode_id' => $periode->id,
            'hasil_kerja_id' => $hasilKerja->id,
        ], $payload));
    }

    // public function test_tambah_realisasi_data_duplikat(){
    //     $user = User::where('username', 'devit')->first();
    //     $this->assertNotNull($user);
    //     $this->actingAs($user);

    //     $hasilKerjaId = 11;
    //     $periodeId = 4;

    //     $existing = RealisasiPeriodik::firstOrCreate([
    //         'hasil_kerja_id' => $hasilKerjaId,
    //         'periode_id' => $periodeId,
    //     ], [
    //         'realisasi' => 'Data Sebelumnya',
    //         'bukti_dukung' => 'https://example.com/old.pdf',
    //     ]);

    //     $this->assertDatabaseHas('skp_realisasi_periodik', [
    //         'hasil_kerja_id' => $hasilKerjaId,
    //         'periode_id' => $periodeId,
    //     ]);

    //     $payload = [
    //         'realisasi' => 'Coba Tambah Duplikat',
    //         'bukti_dukung' => 'https://example.com/duplikat.pdf',
    //     ];

    //     $response = $this->post("/skp/realisasi/{$periodeId}/update-realisasi/{$hasilKerjaId}", $payload);

    //     $response->assertRedirect();
    //     $response->assertSessionHas('failed');

    //     $this->assertEquals(1, RealisasiPeriodik::where([
    //         'hasil_kerja_id' => $hasilKerjaId,
    //         'periode_id' => $periodeId,
    //     ])->count());
    // }

    public function test_store_realisasi_data_non_valid(){
        $user = User::where('username', 'devit')->firstOrFail();
        $this->actingAs($user);

        $hasilKerja = HasilKerja::findOrFail(5);
        $periode = Periode::findOrFail(1);

        $response = $this->post("/skp/realisasi/{$periode->id}/update-realisasi/{$hasilKerja->id}", [
            'realisasi' => 1,
            'bukti_dukung' => 2,
        ]);
        $response->assertSessionHasErrors(['realisasi', 'bukti_dukung']);
    }

}
