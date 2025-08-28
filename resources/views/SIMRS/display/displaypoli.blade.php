<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Antrian</title>
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
                animation: scrollAnimation 20s linear infinite;
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
        <div class="header">
            <img src="{{ asset("img/logoarsy.png") }}" alt="Logo RS" class="logo">
            <div class="header-text">
                <h1>ANTRIAN POLIKLINIK</h1>
                <h3>RS ABDURRAHMAN SYAMSYURI</h3>
            </div>
        </div>

        <!-- Container -->
        <div class="container">
            <!-- Display Nomor Antrian -->
            <div class="main-display">
                <div class="queue-box">
                    <h3 class="dokter">-</h3>
                    <h3 class="poli">-</h3>
                    <p class="number">-</p>
                    <h3 class="name">-</h3>
                </div>
                {{-- <div class="video-box">
                    <video controls>
                        <source src="{{ Storage::url("video/bpjs.mp4") }}" type="video/mp4">
                    </video>
                </div> --}}
            </div>

            <!-- Daftar Customer Service -->
            <div class="customer-services-container">
                <div class="customer-services" id="customer-services">
                    <!-- Kotak akan di-generate menggunakan jQuery -->
                </div>
            </div>
            {{-- <button id="enableSpeech">Aktifkan Suara</button> --}}
        </div>

        <!-- Footer -->
        {{-- <footer>
            <p>JAM BUKA LAYANAN KAMI ADALAH PUKUL 07:00 s/d 21:00. TERIMA KASIH ATAS KUNJUNGAN ANDA. KAMI SENANTIASA
                MELAYANI.</p>
        </footer> --}}

        <script>
            $(document).ready(function() {
                let lastQueueNumber = null; // Menyimpan nomor antrean terakhir yang diproses
                let queueList = []; // Antrian untuk suara dan tampilan
                let isSpeaking = false; // Status apakah sedang berbicara

                function fetchQueueData() {
                    $.ajax({
                        url: "{{ route("display.poli.lastdata") }}",
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        success: function(row) {
                            console.log("Data yang diterima:", row);
                            if (row && row.no_reg) {
                                if (row.no_reg !== lastQueueNumber && !queueList.some(q => q.no_reg === row
                                        .no_reg)) {
                                    queueList.push(row);
                                    processQueue(); // Mulai proses jika antrean belum berjalan
                                }
                            } else {
                                console.error("Data tidak valid:", row);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal mengambil antrean terbaru:", error);
                        }
                    });
                }

                function processQueue() {
                    if (isSpeaking || queueList.length === 0) return;

                    isSpeaking = true;
                    let row = queueList.shift(); // Ambil antrean pertama dari daftar
                    lastQueueNumber = row.no_reg;
                    updateDisplay(row);
                    speakQueue(row.nm_dokter, row.nm_poli, row.no_reg, row.nm_pasien);
                }

                function updateDisplay(row) {
                    $(".queue-box .dokter").text(row.nm_dokter || "N/A");
                    $(".queue-box .poli").text(row.nm_poli || "N/A");
                    $(".queue-box .number").text(row.no_reg);
                    $(".queue-box .name").text(row.nm_pasien);
                }

                function formatPronunciation(dokter) {
                    let replacements = {
                        "Sp. PD": "espe, pede",
                        "Sp. A": "espe, a",
                        "Sp. B": "espe, be",
                        "Sp. KK": "espe, ka, ka",
                        "dr.": "dokter",
                        "drg.": "dokter gigi"
                    };

                    for (let key in replacements) {
                        dokter = dokter.replace(new RegExp(key, "gi"), replacements[key]);
                    }

                    return dokter;
                }

                function formatPatientName(pasien) {
                    let replacements = {
                        "Tn": "Tuan",
                        "Ny": "Nyonya"
                    };

                    // Ganti "Tn" atau "Ny" jika ada dalam nama
                    for (let key in replacements) {
                        pasien = pasien.replace(new RegExp(`\\b${key}\\.?\\b`, "gi"), replacements[key]);
                    }

                    // Jika formatnya "Nama, Tn" atau "Nama, Ny", ubah menjadi "Tn. Nama" atau "Ny. Nama"
                    let match = pasien.match(/(.*),\s*(Tuan|Nyonya)/i);
                    if (match) {
                        pasien = `${match[2]}. ${match[1]}`;
                    }

                    return pasien;
                }

                $("#enableSpeech").on("click", function() {
                    window.speechSynthesis.resume(); // Mengaktifkan kembali Text-to-Speech jika terblokir
                    if (speechQueue.length > 0) {
                        processQueue();
                    }
                });


                function speakQueue(dokter, poli, nomor, pasien) {
                    if ('speechSynthesis' in window) {
                        let formattedDokter = formatPronunciation(dokter);
                        let formattedPasien = formatPatientName(pasien);
                        let text =
                            `Nomor antrian: ${nomor}, atas nama: ${formattedPasien}, di: ${poli}`;
                        let speech = new SpeechSynthesisUtterance(text);
                        speech.lang = "id-ID";
                        speech.rate = 0.8;
                        speech.pitch = 1;

                        speech.onend = function() {
                            isSpeaking = false;
                            processQueue();
                        };

                        window.speechSynthesis.cancel();
                        window.speechSynthesis.speak(speech);
                    } else {
                        console.error("Browser tidak mendukung Text-to-Speech!");
                    }
                }


                function updateCustomerServices() {
                    $.ajax({
                        url: "{{ route("display.poli.data") }}",
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        success: function(data) {
                            let container = $('#customer-services');
                            container.empty();

                            const colors = ['#109191'];

                            let serviceBoxes = data.map((item, index) => {
                                let color = colors[index % colors.length];
                                return `
                        <div class="service-box" style="background-color: ${color};">
                            <h3 class="service-doctor">${item.nm_dokter || 'N/A'}</h3>
                            <h3>${item.nm_poli || 'UNKNOWN SERVICE'}</h3>
                            <h3 class="service-number">${item.no_reg || 'N/A'}</h3>
                            <h3 class="service-number">${item.nm_pasien || 'N/A'}</h3>
                        </div>`;
                            }).join('');

                            container.html(serviceBoxes + serviceBoxes);
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal mengambil data:", error);
                        }
                    });
                }

                fetchQueueData();
                updateCustomerServices();

                setInterval(fetchQueueData, 1000);
                setInterval(updateCustomerServices, 1000);
            });
        </script>

        {{-- <script>
            $(document).ready(function() {
                let lastQueueNumber = null;
                let queueList = [];
                let isSpeaking = false;
                let isSpeechEnabled = false; // Status apakah suara sudah diaktifkan

                // Aktifkan TTS saat pengguna pertama kali mengklik di mana saja dalam tampilan
                $(document).one("click", function() {
                    isSpeechEnabled = true;
                    window.speechSynthesis.resume();
                    if (!isSpeaking && queueList.length > 0) {
                        processQueue();
                    }
                });

                function fetchQueueData() {
                    $.ajax({
                        url: "{{ route("display.poli.lastdata") }}",
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        success: function(row) {
                            console.log("Data diterima:", row);
                            if (row && row.no_reg) {
                                if (row.no_reg !== lastQueueNumber && !queueList.some(q => q.no_reg === row
                                        .no_reg)) {
                                    queueList.push(row);
                                    if (isSpeechEnabled) processQueue();
                                }
                            } else {
                                console.error("Data tidak valid:", row);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal mengambil antrean:", error);
                        }
                    });
                }

                function processQueue() {
                    if (isSpeaking || queueList.length === 0) return;

                    isSpeaking = true;
                    let row = queueList.shift();
                    lastQueueNumber = row.no_reg;
                    updateDisplay(row);
                    speakQueue(row.nm_dokter, row.nm_poli, row.no_reg, row.nm_pasien);
                }

                function updateDisplay(row) {
                    $(".queue-box .dokter").text(row.nm_dokter || "N/A");
                    $(".queue-box .poli").text(row.nm_poli || "N/A");
                    $(".queue-box .number").text(row.no_reg);
                    $(".queue-box .name").text(row.nm_pasien);
                }

                function speakQueue(dokter, poli, nomor, pasien) {
                    if (!isSpeechEnabled) return; // Pastikan suara hanya berjalan jika diaktifkan

                    if ('speechSynthesis' in window) {
                        let text = `Nomor antrian: ${nomor}, atas nama: ${pasien}, di: ${poli}`;
                        let speech = new SpeechSynthesisUtterance(text);
                        speech.lang = "id-ID";
                        speech.rate = 0.8;
                        speech.pitch = 1;

                        speech.onend = function() {
                            isSpeaking = false;
                            processQueue();
                        };

                        window.speechSynthesis.cancel();
                        window.speechSynthesis.speak(speech);
                    } else {
                        console.error("Browser tidak mendukung Text-to-Speech!");
                    }
                }

                function updateCustomerServices() {
                    $.ajax({
                        url: "{{ route("display.poli.data") }}",
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        success: function(data) {
                            let container = $('#customer-services');
                            container.empty();

                            const colors = ['purple', 'green', 'red', 'blue', 'orange', 'yellow'];

                            let serviceBoxes = data.map((item, index) => {
                                let color = colors[index % colors.length];
                                return `
                            <div class="service-box" style="background-color: ${color};">
                                <h3 class="service-doctor">${item.nm_dokter || 'N/A'}</h3>
                                <h3>${item.nm_poli || 'UNKNOWN SERVICE'}</h3>
                                <h3 class="service-number">${item.no_reg || 'N/A'}</h3>
                                <h3 class="service-number">${item.nm_pasien || 'N/A'}</h3>
                            </div>`;
                            }).join('');

                            container.html(serviceBoxes);
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal mengambil data:", error);
                        }
                    });
                }

                fetchQueueData();
                updateCustomerServices();

                setInterval(fetchQueueData, 1000);
                setInterval(updateCustomerServices, 1000);
            });
        </script> --}}
    </body>

</html>
