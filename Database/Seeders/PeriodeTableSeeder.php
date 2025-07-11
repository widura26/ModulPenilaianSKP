<?php

namespace Modules\Penilaian\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class PeriodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('periodes')->delete();
        DB::table('periodes')->insert([
            [
                'id' => 1,
                'start_date' => '2025-01-01',
                'end_date' => '2025-03-31',
                'tahun' => 2025,
                'jenis_periode' => 'Triwulan 1',
                'created_at' => Carbon::parse('2025-05-26 07:29:07'),
                'updated_at' => Carbon::parse('2025-05-26 07:29:07'),
            ],
            [
                'id' => 2,
                'start_date' => '2025-04-01',
                'end_date' => '2025-06-30',
                'tahun' => 2025,
                'jenis_periode' => 'Triwulan 2',
                'created_at' => Carbon::parse('2025-05-26 10:54:36'),
                'updated_at' => Carbon::parse('2025-05-26 10:54:36'),
            ],
            [
                'id' => 3,
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'tahun' => 2025,
                'jenis_periode' => 'Triwulan 3',
                'created_at' => Carbon::parse('2025-05-26 10:55:29'),
                'updated_at' => Carbon::parse('2025-05-26 10:55:29'),
            ],
            [
                'id' => 4,
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
                'tahun' => 2025,
                'jenis_periode' => 'Triwulan 4',
                'created_at' => Carbon::parse('2025-05-26 10:55:29'),
                'updated_at' => Carbon::parse('2025-05-26 10:55:29'),
            ],
            [
                'id' => 5,
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'tahun' => 2025,
                'jenis_periode' => 'Tahunan',
                'created_at' => Carbon::parse('2025-05-26 10:55:29'),
                'updated_at' => Carbon::parse('2025-05-26 10:55:29'),
            ],
        ]);
    }
}
