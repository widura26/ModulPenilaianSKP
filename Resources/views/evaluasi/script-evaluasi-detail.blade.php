<script>
    // $(document).ready(() => {
    //     $('#form-umpan-balik').on('submit', function(event) {
    //         event.preventDefault();

    //         $.ajax({
    //             url: '/penilaian/predikat-kinerja',
    //             type: 'GET',
    //             data: $(this).serialize(),
    //             success: (response) => {
    //                 console.log(response)
    //             },
    //             error: (response) => {
    //                 console.log(response.responseJSON.message)
    //             }
    //         });
    //     });
    // })

    // $('#umpanBalikForm').on('submit', function(e) {
    //     e.preventDefault();

    //     $.ajax({
    //         url: '/penilaian/evaluasi/proses-umpan-balik/{{ $pegawai->username }}',
    //         type: 'POST',
    //         data: $(this).serialize(),
    //         success: function(response) {
    //             try {
    //                 umpanBalikBox.style.display = 'block'
    //                 console.log(response)

    //             } catch (error) {
    //                 console.log(error)
    //             }
    //         },
    //         error: function(xhr) {
    //             $('#result').html('<p style="color: red;">Gagal mengirim umpan balik.</p>');
    //         }
    //     });
    // });

    (function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
    })();

    setTimeout(() => {
        const alertfailed = document.getElementById('alert-failed');
        const alertpassed = document.getElementById('alert-passed');
        if (alertfailed) {
            alertfailed.style.display = 'none';
        } else if (alertpassed){
            alertpassed.style.display = 'none';
        }
    }, 3000);

    const rekomendasiRatingHasilKerja = document.querySelector('#rekomendasi-rating-hasil-kerja');
    const rekomendasiRatingPerilaku = document.querySelector('#rekomendasi-rating-perilaku');
    const selectRatingHasilKerja = document.querySelector('#rating-hasil-kerja-select');
    const selectRatingPerilaku = document.querySelector('#rating-perilaku-select');
    const textareaRatingHasilKerja = document.querySelector('#textarea-rating-hasil-kerja');
    const textareaRatingPerilaku = document.querySelector('#textarea-rating-perilaku');

    const valRatHasilKerja = rekomendasiRatingHasilKerja.value;
    const valRatPerilaku = rekomendasiRatingPerilaku.value;

    selectRatingHasilKerja.addEventListener('change', () => {
        const valSelRatHasilKerja = selectRatingHasilKerja.value;

        if(valSelRatHasilKerja == valRatHasilKerja) {
            textareaRatingHasilKerja.classList.add('d-none')
            textareaRatingHasilKerja.removeAttribute('required');
            textareaRatingHasilKerja.value = '';
        } else {
            textareaRatingHasilKerja.classList.remove('d-none')
            textareaRatingHasilKerja.setAttribute('required', 'required');
        }
    })

    // selectRatingHasilKerja.dispatchEvent(new Event('change'));

    selectRatingPerilaku.addEventListener('change', () => {
        const valSelRatPerilaku = selectRatingPerilaku.value;

        if(valSelRatPerilaku == valRatPerilaku) {
            textareaRatingPerilaku.classList.add('d-none')
            textareaRatingPerilaku.removeAttribute('required');
            textareaRatingPerilaku.value = '';
        } else {
            textareaRatingPerilaku.classList.remove('d-none')
            textareaRatingPerilaku.setAttribute('required', 'required');
        }
    })

</script>
