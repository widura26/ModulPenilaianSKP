<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PenilaianHasilKerja extends Model
{
    use HasFactory;

    protected $table = 'skp_penilaian_hasil_kerja';
    protected $guarded = [];

    // public function hasilKerja(){
    //     return $this->belongsTo(HasilKerja::class);
    // }

    public function penilaianable(): MorphTo {
        return $this->morphTo();
    }
}
