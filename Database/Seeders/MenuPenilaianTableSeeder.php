<?php

namespace Modules\Penilaian\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\Menu;
use Illuminate\Database\Eloquent\Model;

class MenuPenilaianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        Menu::where('modul', 'Penilaian')->delete();
        $menu =  Menu::create([
            'modul' => 'Penilaian',
            'label' => 'SKP',
            'url' => '',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['terdaftar', 'operator', 'admin', 'pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => 0,
            'active' => '',
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Rencana',
            'url' => 'penilaian/rencana',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['terdaftar', 'operator', 'pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/rencana', 'penilaian/rencana*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Matriks Peran Hasil',
            'url' => 'penilaian/matriks-peran-hasil',
            'can' => serialize(['terdaftar']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/matriks-peran-hasil', 'penilaian/matriks-peran-hasil*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Realisasi',
            'url' => 'penilaian/realisasi',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['terdaftar', 'operator','pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/realisasi', 'penilaian/realisasi*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Evaluasi',
            'url' => 'penilaian/evaluasi',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['terdaftar', 'kajur', 'wadir1', 'wadir2', 'wadir3']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/evaluasi', 'penilaian/evaluasi*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Arsip',
            'url' => 'penilaian/arsip-skp',
            'can' => serialize(['terdaftar', 'admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/arsip-skp', 'penilaian/arsip-skp*']),
        ]);
        // Menu::create([
        //     'modul' => 'Penilaian',
        //     'label' => 'Kinerja Organisasi',
        //     'url' => 'penilaian/kinerja-organisasi',
        //     'can' => serialize(['admin']),
        //     'icon' => 'far fa-circle',
        //     'urut' => 1,
        //     'parent_id' => $menu->id,
        //     'active' => serialize(['penilaian/kinerja-organisasi', 'penilaian/kinerja-organisasi*']),
        // ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Periode',
            'url' => 'penilaian/periode',
            'can' => serialize(['admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/periode', 'penilaian/periode*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Monitoring',
            'url' => 'penilaian/monitoring',
            'can' => serialize(['admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['penilaian/monitoring', 'penilaian/monitoring*']),
        ]);
    }
}
