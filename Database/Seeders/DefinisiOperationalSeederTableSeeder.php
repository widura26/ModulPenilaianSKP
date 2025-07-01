<?php

namespace Modules\Penilaian\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DefinisiOperationalSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run(): void
    {
        // $data = [
        //     'Cakupan' => [
        //         'Definisi dan cakupan lulusan',
        //         'Definisi D4',
        //         'Definisi D3/D2/D1',
        //         'Penjelasan periode waktu',
        //         'Penjelasan masa tunggu <=12 bulan',
        //     ],
        //     'Kriteria pekerjaan' => [
        //         'Kriteria bekerja di perusahaan swasta',
        //         'Kriteria bekerja di perusahaan nirlaba',
        //         'Kriteria bekerja di institusi atau organisasi multilateral',
        //         'Kriteria lembaga pemerintah',
        //         'Badan usaha milik negara (BUMN)/daerah (BUMD)',
        //     ],
        //     'Kriteria kelanjutan studi' => [
        //         'Definisi program studi profesi',
        //         'Definisi S1/S1 terapan',
        //         'Definisi S2/S2 terapan',
        //         'Definisi dalam negeri',
        //         'Definisi luar negeri',
        //     ],
        //     'Kriteria kewiraswastaan' => [
        //         'Definisi pendiri',
        //         'Definisi pasangan pendiri',
        //         'Kriteria menjadi pekerja lepas',
        //     ],
        //     'Formula' => [
        //         'Formula',
        //         'Variabel n',
        //         'Variabel t',
        //     ]
        // ];

        // foreach ($data as $topik => $subTopiks) {
        //     foreach ($subTopiks as $sub) {
        //         DB::table('definisi_operationals')->insert([
        //             'topik' => $topik,
        //             'sub_topik' => $sub,
        //             'deskripsi' => null,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }
    }
}
