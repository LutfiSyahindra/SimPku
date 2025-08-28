<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Antrian</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Tambahkan Vite -->
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
            </div>

            <!-- Daftar Customer Service -->
            <div class="customer-services-container">
                <div class="customer-services" id="customer-services">
                    <!-- Kotak akan di-generate menggunakan jQuery -->
                </div>
            </div>
        </div>
        {{-- <script type="module">
            import Reverb from "@laravel/reverb";

            const reverb = new Reverb();

            reverb.channel("display-antrian").listen("panggil-pasien", (event) => {
                console.log("Pasien dipanggil:", event.pasien);

                document.getElementById("nomor-antrian").innerText = event.pasien.nomor_antrian;
                document.getElementById("nama-pasien").innerText = event.pasien.nama;
                document.getElementById("nama-poli").innerText = event.pasien.poli;
                document.getElementById("nama-dokter").innerText = event.pasien.dokter;

                // Text-to-Speech (TTS)
                let text = `Pasien ${event.pasien.nama}, silakan menuju poli ${event.pasien.poli}`;
                let speech = new SpeechSynthesisUtterance(text);
                speech.lang = "id-ID";
                window.speechSynthesis.speak(speech);
            });
        </script> --}}
        {{-- <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
        <script>
            var pusher = new Pusher("74rwhishbu7kaixs8lwg", {
                cluster: "",
                enabledTransports: ["ws"],
                forceTLS: false,
                wsHost: "127.0.0.1",
                wsPort: "8080",
            });

            var channel = pusher.subscribe('panggilan-pasien');
            channel.bind("APP\\Events\\PanggilPasienEvent", function(data) {
                console.log("Pasien dipanggil:", data.pasien);

                document.getElementById("nomor-antrian").innerText = data.pasien.nomor_antrian;
                document.getElementById("nama-pasien").innerText = data.pasien.nama;
                document.getElementById("nama-poli").innerText = data.pasien.poli;
                document.getElementById("nama-dokter").innerText = data.pasien.dokter;

                // Text-to-Speech (TTS)
                let text = `Pasien ${data.pasien.nama}, silakan menuju poli ${data.pasien.poli}`;
                let speech = new SpeechSynthesisUtterance(text);
                speech.lang = "id-ID";
                window.speechSynthesis.speak(speech);
            });
        </script> --}}

        <script>
            const reverb = new Reverb({
                host: "127.0.0.1",
                port: 8080
            });
        </script>
    </body>

</html>
