<script>
    const allOptionsHasilKerja = $('#hasil-kerja-diintervensi option').clone();

    // $('#peran-select').on('change', function () {
    //     const selectedPeranId = $(this).val();
    //     const hasilKerjaSelect = $('#hasil-kerja-diintervensi');

    //     hasilKerjaSelect.empty().append('<option value="">-- Pilih Hasil Kerja yang Diintervensi --</option>');

    //     allOptionsHasilKerja.each(function () {
    //         const peranId = $(this).data('peran-id');
    //         if (selectedPeranId === peranId?.toString()) {
    //             hasilKerjaSelect.append($(this));
    //         }
    //     });
    // });
    $('#peran-select').on('change', function() {
        const selectedPeranId = $(this).val();
        const hasilKerjaSelect = $('#hasil-kerja-diintervensi');

        // Reset opsi dropdown hasil kerja
        hasilKerjaSelect.empty().append('<option value="">-- Pilih Hasil Kerja yang Diintervensi --</option>');

        // Jika peran kosong, tampilkan semua hasil kerja
        if (!selectedPeranId) {
            allOptionsHasilKerja.each(function() {
                hasilKerjaSelect.append($(this).clone());
            });
            return;
        }

        // Filter dan tampilkan hanya hasil kerja yang sesuai peran
        allOptionsHasilKerja.each(function() {
            const peranId = $(this).data('peran-id')?.toString() || '';
            if (peranId === selectedPeranId) {
                hasilKerjaSelect.append($(this).clone());
            }
        });
    });

    setTimeout(() => {
        const alertfailed = document.getElementById('alert-failed');
        const alertpassed = document.getElementById('alert-passed');
        if (alertfailed) {
            alertfailed.style.display = 'none';
        } else if (alertpassed) {
            alertpassed.style.display = 'none';
        }
    }, 3000);
</script>

<!-- <script>
    Edit Hasil Kerja Utama
    $('.btn-edit-utama').on('click', function() {
        const id = $(this).data('id');
        $.get(`/edit-hasil-kerja-utama/${id}`, function(data) {
            $('#edit-utama-id').val(data.id);
            $('#edit-utama-deskripsi').val(data.deskripsi);
            $('#edit-utama-indikators').val(data.indikator);
            $('#formEditUtama').attr('action', `/update-hasil-kerja-utama/${id}`);
            $('#editModalUtama').modal('show');
        });
    });

    Edit Hasil Kerja Tambahan
    $('.btn-edit-tambahan').on('click', function() {
        const id = $(this).data('id');
        $.get(`/edit-hasil-kerja-tambahan/${id}`, function(data) {
            $('#edit-tambahan-id').val(data.id);
            $('#edit-tambahan-deskripsi').val(data.deskripsi);
            $('#edit-tambahan-indikators').val(data.indikator);
            $('#formEditTambahan').attr('action', `/update-hasil-kerja-tambahan/${id}`);
            $('#editModalTambahan').modal('show');
        });
    });
</script> -->

<!-- Edit Hasil Kerja Utama -->
<script>
    $('.btn-edit-utama').on('click', function() {
        const id = $(this).data('id');

        // AJAX GET untuk ambil data hasil kerja
        $.get(`/skp/rencana/edit-hasil-kerja-utama/${id}`, function(data) {
            // Isi nilai input setelah data didapat
            $('#edit-utama-id').val(data.id);
            $('#edit-utama-deskripsi-lama').val(data.deskripsi);
            $('#edit-utama-deskripsi-baru').val(''); // kosongkan form input baru

            // Atur form action-nya
            $('#formEditUtama').attr('action', `/skp/rencana/update-hasil-kerja-utama/${id}`);

            // Tampilkan modal setelah data siap
            $('#editModalUtama').modal('show');
        });
    });
</script>

<!-- Tambah data indikator manual utama -->
<script>
    const definisiList = window.definisiList || [];
    document.addEventListener('DOMContentLoaded', function() {
        const definisiList = window.definisiList || [];

        document.querySelectorAll('.topik-dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('change', function() {
                const hasilKerjaId = this.id.split('-')[1]; // ambil id hasil kerja dari ID dropdown
                const selectedTopik = this.value;

                const subTopikSelect = document.getElementById('sub_topik-' + hasilKerjaId);
                subTopikSelect.innerHTML = '<option value="">-- Pilih Sub Topik --</option>';

                const filtered = definisiList.filter(item => item.topik === selectedTopik);

                filtered.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.sub_topik;
                    opt.text = item.sub_topik;
                    subTopikSelect.appendChild(opt);
                });
            });
        });
    });
</script>

<!-- Edit Indikator Hasil Kerja Utama -->
<script>
    $('.btn-edit-indikator-utama').on('click', function() {
        const id = $(this).data('id');
        const deskripsi = $(this).data('deskripsi');

        $('#edit-indikator-id').val(id);
        $('#indikator-lama').val(deskripsi);

        // Set form action dengan ID indikator
        // const action = `/skp/rencana/edit-indikator-utama/${id}`;
        // $('#formEditIndikatorUtama').attr('action', action);

        $('#formEditIndikatorUtama').attr('action', `/skp/rencana/edit-indikator-utama/${id}`);

        // Tampilkan modal setelah data siap
        $('#editIndikatorhasilKerjaModalUtama').modal('show');
    });
</script>

<!-- Delete Hasil Kerja Utama dan Indikator Hasil Kerja Utama -->
<script>
    $('.btn-hapus-hasil-kerja').on('click', function () {
        const id = $(this).data('id');
        $('#formHapusHasilKerja').attr('action', `/skp/rencana/hasil-kerja/${id}`);
    });

    $('.btn-hapus-indikator').on('click', function () {
        const id = $(this).data('id');
        $('#formHapusIndikator').attr('action', `/skp/rencana/indikator/${id}`);
    });
</script>
