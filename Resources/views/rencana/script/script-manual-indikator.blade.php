<!-- <script>
    const definisiList = @json($definisiOperasional->toArray());

    document.querySelectorAll('.topik-dropdown').forEach(function(dropdown) {
        dropdown.addEventListener('change', function () {
            const hasilKerjaId = this.id.split('-')[1];
            const topikVal = this.value;

            const subTopikSelect = document.getElementById('sub_topik-' + hasilKerjaId);
            subTopikSelect.innerHTML = '<option value="">-- Pilih Sub Topik --</option>';
            subTopikSelect.disabled = true;

            const filtered = definisiList.filter(item => item.topik === topikVal);

            filtered.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.sub_topik;
                opt.text = item.sub_topik;
                subTopikSelect.appendChild(opt);
            });

            if (filtered.length > 0) subTopikSelect.disabled = false;
        });
    });
</script> -->
