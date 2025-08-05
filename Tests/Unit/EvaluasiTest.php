<?php

namespace Modules\Penilaian\Tests\Unit;

use Tests\TestCase;
use Modules\Penilaian\Http\Controllers\EvaluasiController;

class EvaluasiTest extends TestCase {

    protected $evaluasicontroller;
    protected function setUp(): void
    {
        parent::setUp();
        $this->evaluasicontroller = app(EvaluasiController::class);
    }

    public function test_predikatKinerja_with_data_valid(){
        $cases = [
            ['Diatas Ekspektasi', 'Diatas Ekspektasi', 'Sangat Baik'],
            ['Sesuai Ekspektasi', 'Diatas Ekspektasi', 'Baik'],
            ['Diatas Ekspektasi', 'Dibawah Ekspektasi', 'Kurang'],
            ['Dibawah Ekspektasi', 'Diatas Ekspektasi', 'Butuh Perbaikan'],
        ];
        foreach ($cases as [$perilaku, $hasil, $expected]) {
            $this->assertEquals(
                $expected, $this->evaluasicontroller->predikatKinerja($perilaku, $hasil),
                "Failed asserting that ({$perilaku}, {$hasil}) returns {$expected}");
        }
    }

    public function test_predikatKinerja_with_data_non_valid(){
        $cases = [
            [null, null], ['Dibawah Ekspektasi', null], ['', ''],
            [null, 'Diatas Ekspektasi'], [null, 'Sesuai Ekspektasi'],
        ];
        foreach ($cases as [$perilaku, $hasil]) {
            $this->assertEquals(
                'Data tidak valid',
                $this->evaluasicontroller->predikatKinerja($perilaku, $hasil),
                "Expected 'Data tidak valid' for input ({$perilaku}, {$hasil})"
            );
        }
    }

    public function test_predikatValue_with_data_valid(){
        $this->assertEquals(1, $this->evaluasicontroller->predikatValue('Dibawah Ekspektasi'));
        $this->assertEquals(2, $this->evaluasicontroller->predikatValue('Sesuai Ekspektasi'));
        $this->assertEquals(3, $this->evaluasicontroller->predikatValue('Diatas Ekspektasi'));
    }

    public function test_predikatValue_with_data_non_valid(){
        $this->assertEquals(0, $this->evaluasicontroller->predikatValue('Hello World'));
        $this->assertEquals(0, $this->evaluasicontroller->predikatValue(null));
    }

}
