<?php
namespace Modules\Penilaian\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RencanaKerjaFactory extends Factory
{
    protected $model = \Modules\Penilaian\Entities\RencanaKerja::class;
    public function definition()
    {
        return [
            'tim_kerja_id' => null,
            'status_persetujuan' => 'Belum Ajukan SKP',
            'status_realisasi' => 'Sudah Dievaluasi',
            'rating_hasil_kerja' => 'Diatas Ekspektasi',
            'deskripsi_rating_hasil_kerja' => null,
            'rating_perilaku' => 'Diatas Ekspektasi',
            'deskripsi_rating_perilaku' => null,
            'predikat_akhir' => 'Sangat Baik',
            'periode_id' => 1,
            'pegawai_id' => 45,
            'lampiran_id' => null
        ];
    }
}

