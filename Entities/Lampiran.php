<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lampiran extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Penilaian\Database\factories\LampiranFactory::new();
    }
}
