<script>

    setTimeout(() => {
        const alertfailed = document.getElementById('alert-failed');
        const alertpassed = document.getElementById('alert-passed');
        if (alertfailed) {
            alertfailed.style.display = 'none';
        } else if (alertpassed){
            alertpassed.style.display = 'none';
        }
    }, 3000);

    let tableRencana = $('#table-arsip-rencana').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Cari nama pegawai..."
        },
        responsive: true,
        scrollX: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/penilaian/arsip-skp/rencana/pegawai',
            type: 'GET',
            dataSrc: function (response) {
                try {
                    return response.data.map((data, index) => {
                        return {
                            no: index + 1,
                            nama: data.pegawai.nama,
                            jenis_arsip: data.jenis_arsip,
                            periode: data.periode.start_date
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
                data: 'jenis_arsip',
                name: 'jenis_arsip',
                orderable: true
            },
            {
                data: 'periode',
                name: 'periode',
                orderable: true
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: () => {
                    return `<button class="btn btn-primary">Update</button>`
                }
            },
        ],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        processing: true,
        serverSide: true,
        stateSave: true,
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        if (target === "#evaluasi" && !$.fn.dataTable.isDataTable('#table-arsip-evaluasi')) {
            let tableEvaluasi = $('#table-arsip-evaluasi').DataTable({
                language: {
                    search: "",
                    searchPlaceholder: "Cari nama pegawai..."
                },
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/penilaian/arsip-skp/evaluasi/pegawai',
                    type: 'GET',
                    dataSrc: function (response) {
                        try {
                            return response.data.map((data, index) => {
                                return {
                                    no: index + 1,
                                    nama: data.pegawai.nama,
                                    jenis_arsip: data.jenis_arsip,
                                    periode: data.periode.start_date
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
                        data: 'jenis_arsip',
                        name: 'jenis_arsip',
                        orderable: true
                    },
                    {
                        data: 'periode',
                        name: 'periode',
                        orderable: true
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: () => {
                            return `<button class="btn btn-primary">Update</button>`
                        }
                    },
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                processing: true,
                serverSide: true,
                stateSave: true,
            });
        }
        if (target === "#dok" && !$.fn.dataTable.isDataTable('#table-arsip-dokevaluasi')) {
            let dokEvaluasi = $('#table-arsip-dokevaluasi').DataTable({
                language: {
                    search: "",
                    searchPlaceholder: "Cari nama pegawai..."
                },
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/penilaian/arsip-skp/dok-evaluasi/pegawai',
                    type: 'GET',
                    dataSrc: function (response) {
                        try {
                            return response.data.map((data, index) => {
                                return {
                                    no: index + 1,
                                    nama: data.pegawai.nama,
                                    jenis_arsip: data.jenis_arsip,
                                    periode: data.periode.start_date
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
                        data: 'jenis_arsip',
                        name: 'jenis_arsip',
                        orderable: true
                    },
                    {
                        data: 'periode',
                        name: 'periode',
                        orderable: true
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: () => {
                            return `<button class="btn btn-primary">Update</button>`
                        }
                    },
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                processing: true,
                serverSide: true,
                stateSave: true,
            });
        }

        setTimeout(() => {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 300);
    });

    $('#search-input').on('keyup', function () {
        const activeTab = $('.tab-pane.active').attr('id');

        if (activeTab === 'rencana' && $.fn.dataTable.isDataTable('#table-arsip-rencana')) {
            $('#table-arsip-rencana').DataTable().ajax.reload();
        }

        if (activeTab === 'evaluasi' && $.fn.dataTable.isDataTable('#table-arsip-evaluasi')) {
            $('#table-arsip-evaluasi').DataTable().ajax.reload();
        }

        if (activeTab === 'dok' && $.fn.dataTable.isDataTable('#table-arsip-dokevaluasi')) {
            $('#table-arsip-dokevaluasi').DataTable().ajax.reload();
        }
    });
</script>
