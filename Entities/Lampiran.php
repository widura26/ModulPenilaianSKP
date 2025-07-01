<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'hasil_kerja_id',
        'jenis_lampiran',
        'deskripsi_lampiran'
    ];
    protected $guarded = ['id'];
}
