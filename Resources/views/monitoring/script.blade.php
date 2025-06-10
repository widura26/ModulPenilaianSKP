<script>
    $(document).ready(function() {
        var tableMonitoring = $('#table-monitoring').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Cari nama pegawai..."
            },
            responsive: true,
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/penilaian/monitoring/data',
                type: 'GET',
                dataSrc: function (response) {
                    try {
                        // console.log(response.data);
                        return response.data.map((data, index) => {
                            return {
                                no: index + 1,
                                nip: data.pegawai.nip,
                                nama: data.pegawai.nama,
                                status: data.status_persetujuan
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
                    data: 'nip',
                    name: 'nip',
                    orderable: false
                },
                {
                    data: 'nama',
                    name: 'nama',
                    orderable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        return row.status
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary">Aksi</button>`
                    }
                }
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            processing: true,
            serverSide: true,
            stateSave: true,
        });

        $('#search-input').on('input', function () {
            tableMonitoring.draw();
        });
    });
</script>
