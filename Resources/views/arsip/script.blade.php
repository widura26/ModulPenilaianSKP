<script>

    const colorStatus = (status) => {
        switch (status) {
            case 'Belum Verifikasi':
                return 'danger'
                break;
            default:
                return 'primary'
                break;
        }
    }

    setTimeout(() => {
        const alertfailed = document.getElementById('alert-failed');
        const alertpassed = document.getElementById('alert-passed');
        if (alertfailed) {
            alertfailed.style.display = 'none';
        } else if (alertpassed){
            alertpassed.style.display = 'none';
        }
    }, 3000);



    const templateDatatable = (name, tableName, url, urlDetail) => {
        $(tableName).DataTable({
            language: {
                search: "",
                searchPlaceholder: "Cari nama pegawai..."
            },
            responsive: true,
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: 'GET',
                dataSrc: function (response) {
                    try {
                        return response.data.map((data, index) => {
                            return {
                                id: data.id,
                                no: index + 1,
                                nama: data.pegawai.nama,
                                jenis_arsip: data.jenis_arsip,
                                periode: data.periode.start_date,
                                status: data.status
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
                    data: 'periode',
                    name: 'periode',
                    orderable: true
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    render: (data, type, row) => {
                        return `<span class='badge badge-${colorStatus(row.status)}'>${row.status}</span>`
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: (data,type, row) => {
                        return `<button type="button" class="btn btn-primary" onclick="window.location.href='${urlDetail}/${row.id}'">
                            <i class="nav-icon fas fa-pencil-alt"></i>
                        </button>`
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

    templateDatatable('rencana', '#table-arsip-rencana',
    '/skp/arsip-skp/rencana/pegawai', '/skp/arsip-skp/rencana/detail')

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        if (target === "#evaluasi" && !$.fn.dataTable.isDataTable('#table-arsip-evaluasi')) {
            templateDatatable('evaluasi', '#table-arsip-evaluasi',
            '/skp/arsip-skp/evaluasi/pegawai', '/skp/arsip-skp/evaluasi/detail')
        }
        if (target === "#dok" && !$.fn.dataTable.isDataTable('#table-arsip-dokevaluasi')) {
            templateDatatable('dok-evaluasi', '#table-arsip-dokevaluasi',
            '/skp/arsip-skp/dok-evaluasi/pegawai', '/skp/arsip-skp/dok-evaluasi/detail')
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
