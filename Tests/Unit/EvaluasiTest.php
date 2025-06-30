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

    public function test_predikatKinerja_passed(){
        $this->assertEquals('Sangat Baik', $this->evaluasicontroller->predikatKinerja('Diatas Ekspektasi', 'Diatas Ekspektasi'));
        $this->assertEquals('Baik', $this->evaluasicontroller->predikatKinerja('Sesuai Ekspektasi', 'Diatas Ekspektasi'));
        $this->assertEquals('Kurang', $this->evaluasicontroller->predikatKinerja('Diatas Ekspektasi', 'Dibawah Ekspektasi'));
        $this->assertEquals('Butuh Perbaikan', $this->evaluasicontroller->predikatKinerja('Dibawah Ekspektasi', 'Diatas Ekspektasi'));
    }

    public function test_predikatKinerja_failed(){
        $this->assertEquals('Data tidak valid', $this->evaluasicontroller->predikatKinerja(null, null));
        $this->assertEquals('Data tidak valid', $this->evaluasicontroller->predikatKinerja('Dibawah Ekspektasi', null));
        $this->assertEquals('Data tidak valid', $this->evaluasicontroller->predikatKinerja(null, 'Dibawah Ekspektasi'));
    }

    public function test_predikatValue_passed(){
        $this->assertEquals(1, $this->evaluasicontroller->predikatValue('Dibawah Ekspektasi'));
        $this->assertEquals(2, $this->evaluasicontroller->predikatValue('Sesuai Ekspektasi'));
        $this->assertEquals(3, $this->evaluasicontroller->predikatValue('Diatas Ekspektasi'));
        $this->assertEquals(0, $this->evaluasicontroller->predikatValue(null));
    }

}
