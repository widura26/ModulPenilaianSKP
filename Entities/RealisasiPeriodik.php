<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RealisasiPeriodik extends Model
{
    use HasFactory;
    protected $table = 'skp_realisasi_periodik';
    protected $guarded = ['id'];

    public function hasilKerja(){
        return $this->belongsTo(HasilKerja::class, 'hasil_kerja_id');
    }
}
