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
    // public function test_a_basic_request(){
    //     $response = $this->get('/');
    //     $response->assertStatus(200);
    // }

    // public function test_store_realisasi_data_valid() {
    //     $user = User::where('username', 'devit')->first();
    //     $this->assertNotNull($user, 'User tidak ditemukan');
    //     $this->actingAs($user);
    //     $hasilKerja = HasilKerja::where('id', 11)->first();
    //     $periode = Periode::where('id', 4)->first();

    //     $this->assertNotNull($hasilKerja, 'Hasil kerja tidak ditemukan');
    //     $this->assertNotNull($periode, 'Periode tidak ditemukan');

    //     $payload = [
    //         'realisasi' => 'Unit Test Realisasi',
    //         'bukti_dukung' => 'https://chatgpt.com/c/6872148d-596c-8006-9ac7-c7e30a875de4'
    //     ];

    //     $response = $this->post('/skp/realisasi/' . $periode->id . '/update-realisasi/' . $hasilKerja->id, $payload);

    //     $response->assertRedirect();
    //     $response->assertSessionHas('success', 'Realisasi berhasil diperbarui.');

    //     $this->assertDatabaseHas('skp_realisasi_periodik', [
    //         'hasil_kerja_id' => $hasilKerja->id,
    //         'periode_id' => $periode->id,
    //         'realisasi' => $payload['realisasi'],
    //         'bukti_dukung' => $payload['bukti_dukung']
    //     ]);
    // }

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
    //         'created_by' => $user->id,
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

    public function test_store_realisasi_data_nonvalid() {
        $user = User::where('username', 'devit')->first();
        $this->assertNotNull($user, 'User tidak ditemukan');
        $this->actingAs($user);
        $hasilKerja = HasilKerja::where('id', 11)->first();
        $periode = Periode::where('id', 4)->first();

        $this->assertNotNull($hasilKerja, 'Hasil kerja tidak ditemukan');
        $this->assertNotNull($periode, 'Periode tidak ditemukan');

        $payload = [
            'realisasi' => 1,
            'bukti_dukung' => 2
        ];

        $response = $this->post('/skp/realisasi/' . $periode->id . '/update-realisasi/' . $hasilKerja->id, $payload);
        $response->assertSessionHasErrors(['realisasi', 'bukti_dukung']);
    }
}
