<script>
    const colorStatus = (status) => {
        if(status == 'Belum Ajukan Realisasi') {
            return `danger`
        }else if (status == 'Belum Dievaluasi') {
            return `secondary`
        }else if (status == 'Sudah Dievaluasi'){
            return 'success'
        }
    }

    $(document).ready(function() {
        var tablePegawai = $('#table-pegawai').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Cari nama pegawai..."
            },
            responsive: true,
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/penilaian/evaluasi/data-pegawai',
                type: 'GET',
                dataSrc: function (response) {
                    try {
                        return response.data.map((data, index) => {
                            return {
                                no: index + 1,
                                id: data.pegawai.id,
                                nama: data.pegawai.nama,
                                username: data.pegawai.username,
                                rencanakerja: data.pegawai.rencanakerja,
                                jabatan: data.pegawai.jabat,
                            }
                        })
                    } catch (error) {
                        console.log(response)
                    }
                },
                data: function(d) {
                    d.search_value = $('#search-input').val();
                },
            },
            columns: [
                {
                    data: 'no',
                    name: 'no',
                    orderable: true
                },
                {
                    data: 'nama',
                    name: 'nama',
                    orderable: true
                },
                {
                    data: null,
                    name: 'jabatan',
                    orderable: true,
                    render: (data, type, row) => {
                        return '-'
                    }
                },
                {
                    data: null,
                    name: 'status',
                    orderable: true,
                    render: (data, type, row) => {
                        const arrayRencana = row.rencanakerja
                        if(arrayRencana.length != 0) {
                            return `<span class="badge badge-${colorStatus(row.rencanakerja[0]?.status_realisasi)}" style="width: fit-content">
                                        ${row.rencanakerja[0].status_realisasi}
                                    </span>`
                        }else {
                            return `<span class="badge badge-danger">Belum Mengajukan SKP</span>`
                        }
                    }
                },
                {
                    data: null,
                    name: 'predikatKinerja',
                    orderable: true,
                    render: (data, type, row) => {
                        const arrayRencana = row.rencanakerja
                        if(arrayRencana.length != 0) {
                            return `<span>${row.rencanakerja?.[0]?.predikat_akhir ?? '-'}</span>`
                        }else {
                            return `<span>-</span>`
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const arrayRencana = row.rencanakerja
                        return `
                            <button ${
                                row.rencanakerja[0]?.status_realisasi == 'Belum Diajukan' || row.rencanakerja[0]?.status == 'Belum Ajukan SKP' || row.rencanakerja[0] == null
                                ? 'disabled' : ''
                            } onclick="window.location.href='/penilaian/evaluasi/${row.username}/detail'" type="button" class="btn btn-primary"><i class="nav-icon fas fa-pencil-alt "></i></button>
                        `;
                    }
                },
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            processing: true,
            serverSide: true,
            stateSave: true,
        });

        $('#search-input').on('input', function () {
            tablePegawai.draw();
        });
    });
</script>
