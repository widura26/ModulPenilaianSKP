<?php

namespace Modules\Penilaian\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Modules\Penilaian\Entities\RencanaKerja;
use Modules\Penilaian\Entities\PerilakuKerja;
use Modules\Penilaian\Entities\RencanaPerilaku;
use Modules\Penilaian\Entities\HasilKerja;
use Modules\Penilaian\Entities\Indikator;
use Modules\Penilaian\Entities\DefinisiOperasional;
use Modules\Penilaian\Entities\Lampiran;

class RencanaTest extends TestCase
{
    // use RefreshDatabase;

    // public function test_ajukan_skp()
    // {
    //     $rencana = RencanaKerja::factory()->create([
    //         'status_pengajuan' => 'Belum Diajukan'
    //     ]);

    //     $response = $this->get('/skp/rencana/ajukan/' . $rencana->id);

    //     $response->assertRedirect();
    //     $this->assertEquals('Sudah Diajukan', $rencana->fresh()->status_pengajuan);
    // }

    // public function test_batalkan_pengajuan_skp()
    // {
    //     $rencana = RencanaKerja::factory()->create([
    //         'status_pengajuan' => 'Sudah Diajukan',
    //         'status_persetujuan' => 'Sudah Disetujui'
    //     ]);

    //     $response = $this->get('/skp/rencana/batalkan/' . $rencana->id);

    //     $response->assertRedirect();
    //     $this->assertEquals('Belum Diajukan', $rencana->fresh()->status_pengajuan);
    //     $this->assertEquals('Belum Disetujui', $rencana->fresh()->status_persetujuan);
    // }

    // public function test_reset_skp()
    // {
    //     $rencana = RencanaKerja::factory()->create();
    //     $hasil = HasilKerja::factory()->create(['rencana_id' => $rencana->id]);
    //     $indikator = Indikator::factory()->create(['hasil_kerja_id' => $hasil->id]);
    //     $lampiran = Lampiran::factory()->create(['hasil_kerja_id' => $hasil->id]);
    //     DefinisiOperasional::factory()->create([
    //         'hasil_kerja_id' => $hasil->id,
    //         'indikator_id' => $indikator->id
    //     ]);
    //     RencanaPerilaku::factory()->create(['rencana_id' => $rencana->id]);

    //     $response = $this->get('/skp/rencana/reset/' . $rencana->id);
    //     $response->assertRedirect();

    //     $this->assertDatabaseMissing('skp_rencana_kerja', ['id' => $rencana->id]);
    //     $this->assertDatabaseMissing('skp_hasil_kerja', ['id' => $hasil->id]);
    //     $this->assertDatabaseMissing('skp_indikator', ['id' => $indikator->id]);
    //     $this->assertDatabaseMissing('skp_rencana_perilaku', ['rencana_id' => $rencana->id]);
    // }

    // public function test_store_manual_indikator()
    // {
    //     $hasil = HasilKerja::factory()->create();
    //     $indikator = Indikator::factory()->create(['hasil_kerja_id' => $hasil->id]);

    //     $data = [
    //         'hasil_kerja_id' => $hasil->id,
    //         'indikator_id' => $indikator->id,
    //         'topik' => 'Topik Uji',
    //         'sub_topik' => 'Subtopik Uji',
    //         'deskripsi' => 'Deskripsi Uji'
    //     ];

    //     $response = $this->post('/skp/rencana/manual-indikator/' . $hasil->id, $data);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('definisi_operasionals', [
    //         'topik' => 'Topik Uji',
    //         'sub_topik' => 'Subtopik Uji'
    //     ]);
    // }

    // public function test_store_hasil_kerja_utama()
    // {
    //     $rencana = RencanaKerja::factory()->create();
    //     $data = [
    //         'deskripsi' => 'Hasil Kerja Utama',
    //         'indikators' => 'Indikator 1;Indikator 2'
    //     ];

    //     $response = $this->post('/skp/rencana/store-hasil-kerja-utama/' . $rencana->id, $data);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('skp_hasil_kerja', [
    //         'rencana_id' => $rencana->id,
    //         'deskripsi' => 'Hasil Kerja Utama'
    //     ]);
    //     $this->assertDatabaseCount('skp_indikator', 2);
    // }

    // public function test_store_hasil_kerja_tambahan()
    // {
    //     $rencana = RencanaKerja::factory()->create();
    //     $data = [
    //         'deskripsi' => 'Hasil Tambahan',
    //         'indikators' => 'Indikator Tambahan 1;Indikator Tambahan 2'
    //     ];

    //     $response = $this->post('/skp/rencana/store-hasil-kerja-tambahan/' . $rencana->id, $data);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('skp_hasil_kerja', [
    //         'rencana_id' => $rencana->id,
    //         'deskripsi' => 'Hasil Tambahan',
    //         'jenis' => 'tambahan'
    //     ]);
    //     $this->assertDatabaseCount('skp_indikator', 2);
    // }
}
