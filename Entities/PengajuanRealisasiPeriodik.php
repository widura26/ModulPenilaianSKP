<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanRealisasiPeriodik extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'skp_pengajuan_realisasi_periodik';

    public function rencana(){
        return $this->belongsTo(RencanaKerja::class);
    }
}
