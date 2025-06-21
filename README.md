# Modul SKP

Modul ini berisi SKP pegawai berbasis Laravel.

## Fitur
- Mengelola rencana
- Mengelola realisasi
- Mengelola evaluasi
- Mengunggah arsip SKP
- Mencetak file evaluasi dan dokumen evaluasi
- Monitoring (On Progress)

## Struktur Folder
<details>
  <summary>Controllers</summary>
  
  - `Http/Controllers` - logika bisnis
</details>

<details>
  <summary>Views</summary>

  - `resources/views` - tampilan (Blade)
</details>
<details>
    <summary>Routes</summary>
    Sebagian besar Routing web diletakkan di file web.php. berikut penjelasan dari masing-masing route.
    <br></br>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Route (Periode)</th>
            <th>Keterangan</th>
        </tr>
        <tr>
            <td>1</td>
            <td><code>skp/periode/</code></td>
            <td>Route ini digunakan untuk mengarahkan user (Kepegawaian) pada tampilan daftar periode</td>
        </tr>
        <tr>
            <td>2</td>
            <td><code>skp/periode/store</code></td>
            <td>Route untuk menambahkan data periode</td>
        </tr>
        <tr>
            <td>3</td>
            <td><code>skp/periode/update/{id}</code></td>
            <td>Route untuk mengubah data periode</td>
        </tr>
        <tr>
            <td>4</td>
            <td><code>skp/periode/set</code></td>
            <td>Route untuk mengeset periode SKP yang dilakukan oleh pegawai pada saat akan membuat rencana SKP</td>
        </tr>
        <tr>
            <td>5</td>
            <td><code>skp/periode/{id}<code></td>
            <td>Route untuk mengarahkan user pada tampilan detail dari periode</td>
        </tr>
    </table>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Route (Evaluasi)</th>
            <th>Keterangan</th>
        </tr>
        <tr>
            <td>1</td>
            <td><code>skp/evaluasi/</code></td>
            <td>Route untuk menampilkan halaman evaluasi dimana di halaman tersebut terdapat daftar pegawai</td>
        </tr>
        <tr>
            <td>2</td>
            <td><code>skp/evaluasi/data-pegawai</code></td>
            <td>Route untuk mengambil data pegawai dari controller dikarenakan pada halaman evaluasi terdapat datatable yang konfigurasinya menggunakan Javascript</td>
        </tr>
        <tr>
            <td>3</td>
            <td><code>skp/evaluasi/{username}/detail</code></td>
            <td>Route untuk menampilkan halaman detail evaluasi yang memuat data pegawai beserta rencana termasuk hasil kerja</td>
        </tr>
        <tr>
            <td>4</td>
            <td><code>skp/evaluasi/batalkan-evaluasi/{username}</code></td>
            <td>Route untuk mengeset periode SKP yang dilakukan oleh pegawai pada saat akan membuat rencana SKP</td>
        </tr>
        <tr>
            <td>5</td>
            <td><code>skp/evaluasi/proses-umpan-balik/{username}<code></td>
            <td>Route untuk memproses umpan balik</td>
        </tr>
        <tr>
            <td>5</td>
            <td><code>skp/evaluasi/simpan-hasil-evaluasi/{id}<code></td>
            <td>Route untuk menyimpan hasil evaluasi</td>
        </tr>
    </table>
    

</details>





