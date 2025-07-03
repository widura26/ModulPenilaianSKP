<?php

namespace Modules\Penilaian\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Penilaian\Http\Controllers\PenilaianController;

class RataRataKehadiranTest extends TestCase
{
    public function test_rerata_kehadiran_dengan_data_valid(){
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(2, 20, 15, 3);
        $this->assertEquals([
            'rerata_alpa' => 10.0,
            'rerata_kehadiran_sesuai_ketentuan' => 75.0,
            'rerata_kehadiran_tidak_sesuai_ketentuan' => 15.0
        ], $result);
    }

    public function test_rerata_kehadiran_dengan_hari_kerja_nol(){
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(2, 0, 15, 3);
        $this->assertEquals([
            'rerata_alpa' => 0,
            'rerata_kehadiran_sesuai_ketentuan' => 0,
            'rerata_kehadiran_tidak_sesuai_ketentuan' => 0
        ], $result);
    }

    public function test_rerata_kehadiran_total_should_be_100_percent(){
        $penilaianController = new PenilaianController();
        $alpa = 2;
        $hariKerja = 20;
        $sesuai = 15;
        $tidakSesuai = 3;

        $result = $penilaianController->rerataKehadiran($alpa, $hariKerja, $sesuai, $tidakSesuai);

        $total = $result['rerata_alpa'] +
                 $result['rerata_kehadiran_sesuai_ketentuan'] +
                 $result['rerata_kehadiran_tidak_sesuai_ketentuan'];

        $this->assertEquals(100, round($total, 2), "Total tidak 100%");
    }

    public function test_rerata_kehadiran_with_zero_hari_kerja_should_be_zero_all(){
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(0, 0, 0, 0);
        $this->assertEquals(0, $result['rerata_alpa']);
        $this->assertEquals(0, $result['rerata_kehadiran_sesuai_ketentuan']);
        $this->assertEquals(0, $result['rerata_kehadiran_tidak_sesuai_ketentuan']);
    }

    public function test_rerata_kehadiran_with_alpa(){
        $penilaianController = new PenilaianController();

        $result = $penilaianController->rerataKehadiran(100, 100, 0, 0);

        $this->assertEquals(100, $result['rerata_alpa']);
        $this->assertEquals(0, $result['rerata_kehadiran_sesuai_ketentuan']);
        $this->assertEquals(0, $result['rerata_kehadiran_tidak_sesuai_ketentuan']);
    }
}
