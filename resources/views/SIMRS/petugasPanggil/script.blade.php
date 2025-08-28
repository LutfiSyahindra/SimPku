<script>
    $(document).ready(function() {
        $('#select-poli, #select-dokter').select2({
            placeholder: "-- Pilih --",
            allowClear: true
        });

        var table = $('#fixed-header-datatable').DataTable({
            destroy: true,
            searching: true,
            ordering: true,
            paging: true,
            responsive: true
        });

        // Ambil daftar Poli dari server
        $.ajax({
            url: '{{ route("petugasPanggil.getPoli") }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#select-poli').empty().append('<option value="">-- Pilih Poli --</option>');
                $.each(response, function(index, item) {
                    $('#select-poli').append(new Option(item.nm_poli, item.kd_poli));
                });
                $('#select-poli').trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching Poli: ", error);
            }
        });

        // Saat Poli dipilih, ambil daftar Dokter
        $('#select-poli').on('change', function() {
            var poliId = $(this).val();
            $('#select-dokter').empty().append('<option value="">-- Pilih Dokter --</option>');

            if (poliId) {
                $.ajax({
                    url: '{{ route("petugasPanggil.getDokter") }}',
                    method: 'GET',
                    data: {
                        PoliId: poliId
                    },
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, item) {
                            $('#select-dokter').append(new Option(item.nm_dokter,
                                item.kd_dokter));
                        });
                        $('#select-dokter').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching doctors: ", error);
                    }
                });
            }

            // Reset daftar pasien
            table.clear().draw();
        });

        // Saat Poli atau Dokter dipilih, ambil daftar pasien
        $('#select-poli, #select-dokter').on('change', function() {
            var poliId = $('#select-poli').val();
            var dokterId = $('#select-dokter').val();
            console.log(poliId, dokterId);

            if (poliId && dokterId) {
                $.ajax({
                    url: '{{ route("petugasPanggil.getPasien") }}',
                    method: 'GET',
                    data: {
                        PoliId: poliId,
                        DokterId: dokterId
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        table.clear().draw();

                        $.each(response, function(index, item) {
                            var button =
                                '<button class="btn btn-info panggil-pasien" ' +
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
                                item.no_reg,
                                item.nm_pasien,
                                // item.nm_poli,
                                button
                            ]).draw(false);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching patients: ", error);
                    }
                });
            } else {
                table.clear().draw();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Tangani klik tombol Panggil
        $('#fixed-header-datatable tbody').on('click', '.panggil-pasien', function() {
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
                url: '{{ route("petugasPanggil.panggilPasien") }}',
                method: 'POST',
                data: {
                    nama: namaPasien,
                    poli: poli,
                    dokter: dokter,
                    no_reg: reg,
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

        // **Tangani Panggil Ulang**
        $('#panggil-ulang').on('click', function() {
            var namaPasien = $('#nama-pasien').text();
            var poli = $('#poli').text();

            if (namaPasien && poli) {
                alert("Memanggil ulang: " + namaPasien + " dari Poli " + poli);
            } else {
                alert("Tidak ada pasien yang dipilih untuk dipanggil ulang.");
            }
        });
    });
</script>
