<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CapaianKinerjaOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'skp_capaian_kinerja_organisasi';
    protected $guarded = ['id'];
}
