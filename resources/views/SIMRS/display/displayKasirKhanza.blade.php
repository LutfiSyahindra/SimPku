<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Antrian</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset("dist/assets/images/favicon.ico") }}">

        <!-- Daterangepicker css -->
        <link rel="stylesheet" href="{{ asset("dist/assets/vendor/daterangepicker/daterangepicker.css") }}">

        <!-- Vector Map css -->
        <link rel="stylesheet"
            href="{{ asset("dist/assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css") }}">

        <!-- Theme Config Js -->
        <script src="{{ asset("dist/assets/js/config.js") }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- App css -->
        <link href="{{ asset("dist/assets/css/app.min.css") }}" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="{{ asset("dist/assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .header {
                position: relative;
                background-color: #097c99;
                color: white;
                text-align: center;
                padding: 15px 20px;
            }

            .header .logo {
                position: absolute;
                left: 20px;
                /* Sesuaikan posisi logo */
                top: 50%;
                transform: translateY(-50%);
                width: 100px;
                /* Sesuaikan ukuran logo */
                height: auto;
            }

            .header-text {
                display: inline-block;
            }

            .fullscreen-btn {
                position: absolute;
                top: 10px;
                right: 20px;
                /* Geser ke kanan atas */
                background-color: #097c99;
                border: none;
                padding: 8px 12px;
                cursor: pointer;
            }

            .fullscreen-btn i {
                font-size: 60px;
                color: white;
            }

            .container {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                max-width: 1500px;
                margin: 0 auto;
                padding: 10px;
                width: 100%;
                box-sizing: border-box;
            }

            .main-display {
                display: flex;
                gap: 20px;
                flex-grow: 1;
            }

            .queue-box,
            .video-box {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background-color: #109191;
                color: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
            }

            .queue-box .number {
                font-size: 64px;
                font-weight: bold;
            }

            .video-box video {
                width: 100%;
                border-radius: 10px;
            }

            .customer-services-container {
                width: 100%;
                position: relative;
                padding-top: 10px;
            }

            .customer-services {
                display: flex;
                gap: 5px;
                width: max-content;
                /* animation: scrollAnimation 20s linear infinite; */
            }

            .scroll-active {
                display: flex;
                gap: 5px;
                width: max-content;
                animation: scrollAnimation 20s linear infinite;
            }

            /* Jika tidak ada kelas scroll-active, animasi berhenti */
            .customer-services {
                display: flex;
                gap: 5px;
                width: max-content;
            }

            .service-box {
                flex: 0 0 12%;
                padding: 10px;
                border-radius: 8px;
                text-align: center;
                font-size: 14px;
                color: white;
                white-space: nowrap;
                background-color: #003366;
            }

            @keyframes scrollAnimation {
                from {
                    transform: translateX(0);
                }

                to {
                    transform: translateX(-50%);
                }
            }

            @media (max-width: 768px) {
                .main-display {
                    flex-direction: column;
                }
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <!-- Header -->
        <div class="header">
            <img src="{{ asset("img/logoarsy.png") }}" alt="Logo RS" class="logo">

            <div class="header-text">
                <h1>ANTRIAN LOKET KASIR</h1>
                <h3>RS ABDURRAHMAN SYAMSYURI</h3>
            </div>

            <!-- Tombol Fullscreen -->
            <button class="fullscreen-btn btn btn-primary" id="fullscreen-btn">
                <i class="ri-fullscreen-line fs-22"></i>
            </button>
        </div>

        <!-- Container -->
        <div class="container">
            <!-- Display Nomor Antrian -->
            <div class="main-display">
                <div class="queue-box">
                    <p class="number">-</p>
                    <h3 class="name">-</h3>
                </div>
            </div>
        </div>

        <script src="https://code.responsivevoice.org/responsivevoice.js"></script>
        <script>
            $(document).ready(function() {
                let lastDisplayedPatient = "";
                let isSpeaking = false;
                let isUserInteracted = false;

                // Event pertama kali user berinteraksi
                document.addEventListener("click", function() {
                    isUserInteracted = true;
                    Swal.fire({
                        icon: "success",
                        title: "Antrian kasir siap dipanggil",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    console.log("✅ Pengguna pertama kali berinteraksi. Antrian kasir siap dipanggil.");
                }, {
                    once: true
                });

                // Fullscreen toggle
                $("#fullscreen-btn").on("click", function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                });

                // Cek apakah responsiveVoice tersedia
                window.onload = function() {
                    if (typeof responsiveVoice !== "undefined") {
                        console.log("✅ responsiveVoice terdeteksi!");
                    } else {
                        console.error("❌ responsiveVoice tidak terdeteksi!");
                    }
                };

                // Fetch antrean dengan async/await untuk meningkatkan performa
                async function fetchQueueData() {
                    if (isSpeaking || !isUserInteracted) return;

                    try {
                        let response = await $.ajax({
                            url: "{{ route("display.khanza.kasir.panggil") }}",
                            method: "GET",
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });

                        console.log("Data yang diterima:", response);

                        if (response.length > 0) {
                            let data = response[0]; // Ambil pasien pertama dalam antrean
                            let formattedName = toTitleCase(data.nm_pasien);

                            if (lastDisplayedPatient !== formattedName) {
                                $(".queue-box .number").text(formattedName);
                                $(".queue-box .name").text(data.no_rawat);
                                lastDisplayedPatient = formattedName;
                            }

                            callPatient(formattedName, data.no_rawat, data.panggil);
                        }
                    } catch (error) {
                        console.error("❌ Gagal mengambil antrean:", error);
                    }
                }

                // Memanggil pasien dengan suara
                function callPatient(name, number, panggil) {
                    let modifiedName = name;

                    // Cek apakah ada koma dalam nama (misalnya "Siti,Ny")
                    if (name.includes(",")) {
                        let parts = name.split(",").map(part => part.trim()); // Pisahkan dan hapus spasi ekstra
                        let title = "";
                        let realName = "";

                        // Cek apakah salah satu bagian adalah gelar yang harus dipindahkan ke depan
                        parts.forEach(part => {
                            if (/^Ny$/i.test(part)) title = "Nyonya";
                            else if (/^Tn$/i.test(part)) title = "Tuan";
                            else if (/^An$/i.test(part)) title = "Anak";
                            else realName = part; // Jika bukan gelar, anggap sebagai nama asli
                        });

                        // Jika ada gelar, format ulang namanya
                        if (title && realName) {
                            modifiedName = `${title} ${realName}`;
                        }
                    } else {
                        // Format nama biasa (jika tidak ada koma)
                        modifiedName = name
                            .replace(/^Ny\b/i, "Nyonya")
                            .replace(/^An\b/i, "Anak")
                            .replace(/^Nn\b/i, "Nona")
                            .replace(/^Tn\b/i, "Tuan");
                    }

                    let text = `Pasien atas nama ${modifiedName}, silakan menuju loket kasir.`;
                    let bellSound = new Audio(
                        "{{ asset("plugins/audio/Airport_Bell.mp3") }}"); // Ganti dengan path suara bel

                    isSpeaking = true;

                    // Putar suara bel terlebih dahulu
                    bellSound.play();

                    // Setelah bel selesai, panggil pasien
                    bellSound.onended = function() {
                        responsiveVoice.speak(text, "Indonesian Female", {
                            pitch: 1,
                            rate: 0.9,
                            volume: 1,
                            onend: async function() {
                                isSpeaking = false;
                                try {
                                    let response = await $.ajax({
                                        url: "{{ route("display.khanza.kasir.update") }}",
                                        method: "PUT",
                                        data: {
                                            panggil: panggil
                                        },
                                        headers: {
                                            "X-Requested-With": "XMLHttpRequest",
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        }
                                    });
                                    console.log("✅ Status antrean diperbarui:", response);
                                } catch (error) {
                                    console.error("❌ Gagal memperbarui status antrean:", error);
                                }
                            }
                        });
                    };
                }


                // Fungsi untuk mengubah teks ke Title Case
                function toTitleCase(str) {
                    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
                }

                fetchQueueData();
                setInterval(fetchQueueData, 2000);
            });
        </script>
    </body>

</html>
