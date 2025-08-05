<?php

namespace Modules\Penilaian\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Penilaian\Http\Controllers\PenilaianController;

class RataRataKehadiranTest extends TestCase
{
    public function test_rerata_kehadiran_output_matches_expected_result() {
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(2, 20, 15, 3);
        $this->assertEquals([
            'rerata_alpa' => 10.0,
            'rerata_kehadiran_sesuai_ketentuan' => 75.0,
            'rerata_kehadiran_tidak_sesuai_ketentuan' => 15.0
        ], $result);
    }

    public function test_rerata_kehadiran_returns_zero_when_hari_kerja_is_zero(){
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(2, 0, 15, 3);
        $this->assertEquals([
            'rerata_alpa' => 0,
            'rerata_kehadiran_sesuai_ketentuan' => 0,
            'rerata_kehadiran_tidak_sesuai_ketentuan' => 0
        ], $result);
    }

    public function test_rerata_kehadiran_returns_total_of_100_percent(){
        $result = (new PenilaianController())->rerataKehadiran(2, 20, 15, 3);
        $total = array_sum([
            $result['rerata_alpa'],
            $result['rerata_kehadiran_sesuai_ketentuan'],
            $result['rerata_kehadiran_tidak_sesuai_ketentuan']
        ]);
        $this->assertEquals(100, round($total, 2), "Total tidak 100%");
    }

    public function test_rerata_kehadiran_all_zero_data(){
        $result = (new PenilaianController())->rerataKehadiran(0, 0, 0, 0);
        $array = ['rerata_alpa', 'rerata_kehadiran_sesuai_ketentuan',
        'rerata_kehadiran_tidak_sesuai_ketentuan'];
        foreach ( $array as $key) {
            $this->assertEquals(0, $result[$key]);
        }
    }

    public function test_rerata_kehadiran_returns_100_percent_alpa_with_alpa_data_only(){
        $penilaianController = new PenilaianController();
        $result = $penilaianController->rerataKehadiran(100, 100, 0, 0);
        $this->assertEquals(100, $result['rerata_alpa']);
        $this->assertEquals(0, $result['rerata_kehadiran_sesuai_ketentuan']);
        $this->assertEquals(0, $result['rerata_kehadiran_tidak_sesuai_ketentuan']);
    }
}
