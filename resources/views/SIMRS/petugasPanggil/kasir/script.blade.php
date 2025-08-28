<script>
    $(document).ready(function() {
        var table = $('#fixed-header-datatable').DataTable({
            destroy: true,
            searching: true,
            ordering: true,
            paging: true,
            responsive: true
        });

        function loadData(startDate = '', endDate = '', jenisRawat = '') {
            $.ajax({
                url: '{{ route("petugasPanggil.kasir.kasirPanggil.dataPasien") }}',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    jenis_rawat: jenisRawat
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    table.clear().draw();

                    $.each(response, function(index, item) {
                        var button =
                            '<button class="btn btn-info panggil-pasienkasir" ' +
                            'data-nama="' + item.nm_pasien + '" ' +
                            'data-poli="' + item.nm_poli + '" ' +
                            'data-dokter="' + item.nm_dokter + '" ' +
                            'data-tgl="' + item.tgl_lahir + '" ' +
                            'data-alamat="' + item.alamat + '" ' +
                            'data-jk="' + item.jk + '" ' +
                            'data-rm="' + item.no_rkm_medis + '" ' +
                            'data-rawat="' + item.no_rawat + '" ' +
                            'data-reg="' + item.no_reg + '">' +
                            '<i class="bi bi-soundwave"></i> Panggil</button>';

                        table.row.add([
                            item.no_rawat,
                            item.no_rkm_medis,
                            item.nm_pasien,
                            button
                        ]).draw(false);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching patients: ", error);
                }
            });
        }

        // Default Load Data
        loadData();

        // Date Range Picker
        $("#date-range").daterangepicker({
            locale: {
                format: "YYYY-MM-DD"
            },
            autoUpdateInput: true
        });

        // Tangani perubahan pada filter
        $('#date-range, #jenis-rawat').on('change', function() {
            var dateRange = $('#date-range').val().split(" - ");
            var startDate = dateRange[0] || '';
            var endDate = dateRange[1] || '';
            var jenisRawat = $('#jenis-rawat').val();
            loadData(startDate, endDate, jenisRawat);
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Tangani klik tombol Panggil
        $('#fixed-header-datatable tbody').on('click', '.panggil-pasienkasir', function() {
            var namaPasien = $(this).data('nama');
            var poli = $(this).data('poli');
            var dokter = $(this).data('dokter');
            var tglLahir = $(this).data('tgl');
            var alamat = $(this).data('alamat');
            var jnsKelamin = $(this).data('jk');
            var noRm = $(this).data('rm');
            var noRawat = $(this).data('rawat');
            var reg = $(this).data('reg');

            // tampilan di card-body panggil pasien
            $('#nama-pasien').text(namaPasien);
            $('#poli').text(poli);
            $('#dokter').text(dokter);
            $('#reg').text(reg);
            $('#detail-nama').text(namaPasien);
            $('#detail-tgl').text(tglLahir);
            $('#detail-alamat').text(alamat);
            $('#detail-jk').text(jnsKelamin);
            $('#detail-rm').text(noRm);
            $('#detail-rawat').text(noRawat);

            $.ajax({
                url: '{{ route("petugasPanggil.kasir.kasirPanggil.panggilKasir") }}',
                method: 'POST',
                data: {
                    nama: namaPasien,
                    poli: poli,
                    dokter: dokter,
                    no_reg: reg,
                    noRawat: noRawat,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        // date picker
        $("#date-range").daterangepicker({
            locale: {
                format: "YYYY-MM-DD"
            },
            autoUpdateInput: true,
        });
    });
</script>
