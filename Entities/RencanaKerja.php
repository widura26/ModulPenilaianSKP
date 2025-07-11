<?php

namespace Modules\Penilaian\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pengaturan\Entities\Jabatan;
use Modules\Pengaturan\Entities\Pegawai;

class RencanaKerja extends Model
{
    use HasFactory;
    protected $table = 'skp_rencana_kerja';
    protected $guarded = ['id'];

    public function hasilkerja()
    {
        return $this->hasMany(HasilKerja::class, 'rencana_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }

    public function perilakuKerja()
    {
        return $this->belongsToMany(PerilakuKerja::class, 'skp_rencana_perilaku', 'rencana_id', 'perilaku_kerja_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    public function lampirans()
    {
        return $this->hasMany(Lampiran::class, 'rencana_id');
    }
}
