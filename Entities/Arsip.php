<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pengaturan\Entities\Pegawai;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'skp_arsip';
    protected $guarded = ['id'];

    public function periode(){
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }
}
