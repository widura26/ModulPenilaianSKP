<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluasiPeriodik extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'skp_evaluasi_periodik';

    public function rencana_kerja(){
        return $this->belongsTo(RencanaKerja::class, 'rencana_kerja_id');
    }
}
