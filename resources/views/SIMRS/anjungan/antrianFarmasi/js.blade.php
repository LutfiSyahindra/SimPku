<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $(document).ready(function() {
        let scanner;

        function setInitialNoRawat() {
            let inputField = document.getElementById("no_rawat");
            if (inputField) {
                let today = new Date();
                let year = today.getFullYear();
                let month = String(today.getMonth() + 1).padStart(2, '0');
                let day = String(today.getDate()).padStart(2, '0');
                let initialFormat = `${year}/${month}/${day}/000`;
                inputField.value = initialFormat; // Set nilai awal
            }
        }

        // Panggil saat pertama kali halaman dimuat
        setInitialNoRawat();

        // Fungsi untuk menambahkan angka ke input
        function updateInput(value) {
            let inputField = document.getElementById("no_rawat");
            let fixedLength = 14; // Panjang format awal YYYY/MM/DD/000
            if (inputField.value.length < fixedLength + 3) {
                inputField.value += value;
            }
        }

        // Fungsi untuk menghapus angka yang diinput
        function deleteNumber() {
            let inputField = document.getElementById("no_rawat");
            let fixedLength = 14;
            if (inputField.value.length > fixedLength) {
                inputField.value = inputField.value.slice(0, -1);
            }
        }

        // Fungsi untuk menangani submit AJAX
        function submitFormFarmasi(e) {
            if (e) e.preventDefault();

            $.ajax({
                url: "/simrs/anjungan/antrianFarmasi/generateNoAntrianFarmasi",
                type: "POST",
                data: $("#ambilNomorFarmasi").serialize(),
                success: function(response) {
                    if (response.status === "success" && response.nomor_antrian.length > 0) {
                        let nomorAntrian = response.nomor_antrian[0];

                        if (nomorAntrian.no_resep_potong) {
                            Swal.fire({
                                title: "Nomor Antrian Anda",
                                text: "Nomor: " + nomorAntrian.no_resep_potong,
                                icon: "success"
                            }).then(() => {
                                $("#farmasiModal").modal("hide");

                                // Cetak antrian setelah modal ditutup
                                let urlCetak =
                                    "/simrs/anjungan/antriFarmasi/cetakAntrian/" +
                                    nomorAntrian.no_resep;
                                window.open(urlCetak, "_blank");
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Data nomor resep tidak ditemukan.",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Gagal mengambil nomor antrian",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    console.error("Terjadi kesalahan:", xhr.responseText);
                    Swal.fire({
                        title: "Terjadi Kesalahan",
                        text: "Silakan coba lagi atau hubungi admin.",
                        icon: "error"
                    });
                }
            });
        }

        // Reset form saat modal tertutup, lalu isi ulang format no_rawat
        $("#farmasiModal").on("hidden.bs.modal", function() {
            $(this).find("form")[0].reset();
            setTimeout(setInitialNoRawat, 50);
        });

        // Menangani event submit pada form
        $("#ambilNomorFarmasi").on("submit", function(e) {
            submitFormFarmasi(e);
        });

        // Fungsi tambahan untuk tombol virtual keyboard
        window.inputNumber = updateInput;
        window.deleteNumber = deleteNumber;
        window.toggleKeyboard = function() {
            document.getElementById("customKeyboard").classList.toggle("d-none");
        };
        window.submitFormFarmasi = submitFormFarmasi;

        // === FUNGSI SCAN BARCODE ===
        window.barcodeScan = function() {
            $("#scanBarcodeModal").modal("show"); // Tampilkan modal scanner

            // Pastikan scanner dibersihkan sebelum memulai kembali
            if (window.scanner) {
                window.scanner.clear();
            }

            // Inisialisasi scanner baru
            window.scanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: 250
            });

            window.scanner.render(onScanSuccess, onScanError);

            function onScanSuccess(decodedText) {
                $("#no_rawat").val(decodedText);
                $("#scanBarcodeModal").modal("hide"); // Tutup modal setelah berhasil scan

                if (window.scanner) {
                    window.scanner.clear(); // Hentikan scanner setelah sukses
                }

                // Tunggu modal tertutup sebelum submit
                setTimeout(() => {
                    submitFormFarmasi(); // Jalankan fungsi submitFormFarmasi
                }, 500);
            }

            function onScanError(errorMessage) {
                console.warn("Scan error: ", errorMessage);
            }

            // Hentikan scanner jika modal ditutup
            $("#scanBarcodeModal").on("hidden.bs.modal", function() {
                if (window.scanner) {
                    window.scanner.clear();
                }
            });
        };


        // Hentikan scanner saat modal ditutup
        $("#scanBarcodeModal").on("hidden.bs.modal", function() {
            if (scanner) {
                scanner.clear();
            }
        });
    });
</script>
