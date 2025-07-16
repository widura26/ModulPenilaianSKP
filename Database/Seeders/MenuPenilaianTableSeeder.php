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
            'can' => serialize(['terdaftar', 'operator', 'admin', 'pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen', 'direktur']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => 0,
            'active' => '',
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Rencana',
            'url' => 'skp/rencana',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['terdaftar', 'operator', 'pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen', 'direktur']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/rencana', 'skp/rencana*']),
        ]);
        // Menu::create([
        //     'modul' => 'Penilaian',
        //     'label' => 'Intervensi',
        //     'url' => 'skp/intervensi',
        //     'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
        //     'can' => serialize(['wadir1', 'wadir2', 'wadir3']),
        //     'icon' => 'far fa-circle',
        //     'urut' => 1,
        //     'parent_id' => $menu->id,
        //     'active' => serialize(['skp/intervensi', 'skp/intervensi*']),
        // ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Matriks Peran Hasil',
            'url' => 'skp/matriks-peran-hasil',
            'can' => serialize(['kajur', 'wadir1', 'wadir2', 'wadir3', 'direktur', 'kaprodi', 'kaunit']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/matriks-peran-hasil', 'skp/matriks-peran-hasil*']),
        ]);
        // Menu::create([
        //     'modul' => 'Penilaian',
        //     'label' => 'Realisasi',
        //     'url' => 'skp/realisasi',
        //     // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
        //     'can' => serialize(['terdaftar', 'operator','pegawai', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'dosen']),
        //     'icon' => 'far fa-circle',
        //     'urut' => 1,
        //     'parent_id' => $menu->id,
        //     'active' => serialize(['skp/realisasi', 'skp/realisasi*']),
        // ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Persetujuan',
            'url' => 'skp/persetujuan',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['kajur', 'wadir1', 'wadir2', 'wadir3', 'direktur', 'kaunit']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/persetujuan', 'skp/persetujuan*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Evaluasi',
            'url' => 'skp/evaluasi',
            // 'can' => serialize(['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen']),
            'can' => serialize(['direktur','terdaftar', 'kajur', 'wadir1', 'wadir2', 'wadir3', 'pegawai']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/evaluasi', 'skp/evaluasi*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Arsip',
            'url' => 'skp/arsip-skp',
            'can' => serialize(['terdaftar', 'admin', 'pegawai']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/arsip-skp', 'skp/arsip-skp*']),
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
            'url' => 'skp/periode',
            'can' => serialize(['admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/periode', 'skp/periode*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Monitoring',
            'url' => 'skp/monitoring',
            'can' => serialize(['admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/monitoring', 'skp/monitoring*']),
        ]);
        Menu::create([
            'modul' => 'Penilaian',
            'label' => 'Definisi Operasional',
            'url' => 'skp/definisi-operasional',
            'can' => serialize(['admin']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => $menu->id,
            'active' => serialize(['skp/definisi-operasional', 'skp/definisi-operasional*']),
        ]);
    }
}
