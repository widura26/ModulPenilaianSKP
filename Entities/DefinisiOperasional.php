<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefinisiOperasional extends Model
{
    use HasFactory;
    protected $table = 'definisi_operationals';
    protected $guarded = ['id'];
}
