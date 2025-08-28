<script>
    $(document).ready(function() {
        $("#ambilNomorAdmisi").click(function() {
            $.ajax({
                url: "{{ route("anjungan.admisi.generateNoAntrian") }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Nomor Antrian Anda",
                            text: "Nomor: " + response.nomor_antrian,
                            icon: "success"
                        }).then(() => {
                            // Tutup modal (pastikan ID sesuai dengan modal yang digunakan)
                            $("#exampleModal").modal("hide");

                            // Buka halaman cetak di tab baru
                            let urlCetak =
                                "{{ url("simrs/anjungan/admisi/cetakAntrian") }}/" +
                                response.nomor_antrian;
                            let newWindow = window.open(urlCetak, "_blank");

                            if (newWindow) {
                                // Jalankan cetakan pertama
                                newWindow.print();

                                // Setelah cetakan pertama selesai, muncul opsi untuk cetakan kedua
                                newWindow.onafterprint = function() {
                                    Swal.fire({
                                        title: "Cetak Ulang?",
                                        text: "Apakah Anda ingin mencetak lagi?",
                                        icon: "question",
                                        showCancelButton: true,
                                        confirmButtonText: "Ya, Cetak Lagi",
                                        cancelButtonText: "Tidak"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            newWindow
                                                .print(); // Cetak kedua kalinya
                                        } else {
                                            newWindow
                                                .close(); // Tutup jika tidak ingin mencetak lagi
                                        }
                                    });
                                };
                            } else {
                                alert(
                                    "Popup diblokir! Izinkan pop-up untuk cetak otomatis."
                                );
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Gagal mengambil nomor antrian",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Terjadi Kesalahan",
                        text: "Coba lagi nanti",
                        icon: "error"
                    });
                }
            });
        });
    });
</script>
