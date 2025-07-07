<script>
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

    const valRatHasilKerja = rekomendasiRatingHasilKerja?.value;
    const valRatPerilaku = rekomendasiRatingPerilaku?.value;

    selectRatingHasilKerja?.addEventListener('change', () => {
        const valSelRatHasilKerja = selectRatingHasilKerja.value;

        if(valSelRatHasilKerja == valRatHasilKerja || (valSelRatHasilKerja === "")) {
            textareaRatingHasilKerja.classList.add('d-none')
            textareaRatingHasilKerja.removeAttribute('required');
            textareaRatingHasilKerja.value = '';
        } else {
            textareaRatingHasilKerja.classList.remove('d-none')
            textareaRatingHasilKerja.setAttribute('required', 'required');
        }
    })

    // selectRatingHasilKerja.dispatchEvent(new Event('change'));

    selectRatingPerilaku?.addEventListener('change', () => {
        const valSelRatPerilaku = selectRatingPerilaku.value;
        // console.log(typeof valSelRatPerilaku);
        if((valSelRatPerilaku == valRatPerilaku) || (valSelRatPerilaku === "")) {
            textareaRatingPerilaku.classList.add('d-none')
            textareaRatingPerilaku.removeAttribute('required');
            textareaRatingPerilaku.value = '';
        } else if(valSelRatPerilaku != valRatPerilaku) {
            textareaRatingPerilaku.classList.remove('d-none')
            textareaRatingPerilaku.setAttribute('required', 'required');
        }
    })

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.modal .hasil-kerja[data-dismiss="modal"]').forEach(button => {
            button.addEventListener('click', function () {
                const modal = this.closest('.modal');
                const selectedTemplate = modal.querySelector('.template-select');
                const selectedOption = selectedTemplate.options[selectedTemplate.selectedIndex];
                const optgroup = selectedOption.closest('optgroup');
                const label = optgroup ? optgroup.label : '';
                document.querySelectorAll('.feedback-template').forEach(select => {
                    select.value = label;
                });

                document.querySelectorAll('.feedback-text').forEach(textarea => {
                    textarea.value = selectedTemplate.value;
                });
            });
        });

        document.querySelectorAll('.modal .perilaku-kerja[data-dismiss="modal"]').forEach(button => {
            button.addEventListener('click', function () {
                const modal = this.closest('.modal');
                const selectedTemplate = modal.querySelector('.template-perilaku-select');
                const selectedOption = selectedTemplate.options[selectedTemplate.selectedIndex];
                const optgroup = selectedOption.closest('optgroup');
                const label = optgroup ? optgroup.label : '';
                document.querySelectorAll('.feedback-perilaku-template').forEach(select => {
                    select.value = label;
                });

                document.querySelectorAll('.feedback-perilaku-text').forEach(textarea => {
                    textarea.value = selectedTemplate.value;
                });
            });
        });
    });

     $('#ubah-umpan-balik').click(function (e) {
        var pegawaiId = $(this).data('pegawai-id');
        e.preventDefault();
        $.ajax({
            url: `/skp/evaluasi/ubah-umpan-balik/${pegawaiId}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("Gagal mengubah umpan balik: " + xhr.responseText);
            }
        });
    });

</script>
