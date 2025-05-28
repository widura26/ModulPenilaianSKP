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
    $('#peran-select').on('change', function () {
        const selectedPeranId = $(this).val();
        const hasilKerjaSelect = $('#hasil-kerja-diintervensi');

        // Reset opsi dropdown hasil kerja
        hasilKerjaSelect.empty().append('<option value="">-- Pilih Hasil Kerja yang Diintervensi --</option>');

        // Jika peran kosong, tampilkan semua hasil kerja
        if (!selectedPeranId) {
            allOptionsHasilKerja.each(function () {
                hasilKerjaSelect.append($(this).clone());
            });
            return;
        }

        // Filter dan tampilkan hanya hasil kerja yang sesuai peran
        allOptionsHasilKerja.each(function () {
            const peranId = $(this).data('peran-id')?.toString() || '';
            if (peranId === selectedPeranId) {
                hasilKerjaSelect.append($(this).clone());
            }
        });
    });

</script>
