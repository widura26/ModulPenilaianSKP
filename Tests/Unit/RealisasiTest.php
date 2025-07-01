<?php

namespace Modules\Penilaian\Tests\Unit;

use App\Models\Core\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pengaturan\Entities\Pegawai;
use Modules\Penilaian\Entities\RencanaKerja;

class RealisasiTest extends TestCase
{
    public function test_a_basic_request(){
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
