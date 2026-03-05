<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Resume - {{ $resume->no_rawat ?? "" }}</title>

        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 11px;
                color: #222;
                margin: 0;
            }

            /* ================= HEADER ================= */
            .header {
                border-bottom: 2px solid #222;
                padding-bottom: 12px;
                margin-bottom: 20px;
            }

            .header-table {
                width: 100%;
            }

            .header-table td {
                vertical-align: top;
            }

            .rs-name {
                font-size: 18px;
                font-weight: bold;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .rs-address {
                font-size: 10.5px;
                margin-top: 4px;
                line-height: 1.5;
                color: #555;
            }

            .patient-box {
                border: 1px solid #000000;
                padding: 8px 10px;
                font-size: 10.5px;
                /* background: #fafafa; */
            }

            .patient-box b {
                display: inline-block;
                width: 85px;
            }

            /* ================= SECTION TITLE ================= */
            .section-title {
                text-align: center;
                font-weight: bold;
                font-size: 14px;
                margin: 20px 0 12px 0;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            /* ================= TABLE STYLE ================= */
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                border: 1px solid #000000;
                /* background: #f2f4f6; */
                padding: 8px 6px;
                font-size: 11px;
                text-align: center;
                font-weight: bold;
            }

            td {
                border: 1px solid #000000;
                padding: 7px 8px;
                font-size: 10.5px;
                vertical-align: top;
            }

            .text-center {
                text-align: center;
            }

            /* ================= SOAP ================= */
            .soap-title {
                font-weight: bold;
                margin-bottom: 3px;
            }

            .soap-content {
                margin-left: 10px;
                line-height: 1.45;
                margin-bottom: 6px;
            }

            /* ================= LAB INFO ================= */
            .lab-info td {
                border: none;
                padding: 3px 4px;
                font-size: 11px;
            }

            .lab-info b {
                display: inline-block;
                width: 120px;
            }

            .lab-group {
                /* background: #eef2f6; */
                font-weight: bold;
                text-transform: uppercase;
                color: #222;
            }

            .lab-group td {
                border-top: 2px solid #444;
                padding-top: 9px;
                padding-bottom: 7px;
            }

            /* ================= FOOTER ================= */
            .footer {
                margin-top: 20px;
                font-size: 10px;
                color: #000000;
                text-align: right;
            }

            .page-break {
                page-break-after: always;
            }

            /* ================= LAB FIX ================= */
            .lab-header-box {
                border: 1px solid #000000;
                padding: 8px 10px;
                /* background: #fafafa; */
                font-size: 10.5px;
                margin-bottom: 10px;
            }

            .lab-header-table td {
                border: none;
                padding: 3px 4px;
                vertical-align: top;
            }

            .lab-header-table b {
                display: inline-block;
                width: 130px;
            }

            .lab-table th {
                /* background: #f2f4f6; */
                border: 1px solid #000000;
            }

            .lab-table td {
                border: 1px solid #000000;
                padding: 6px 7px;
            }

            .lab-group {
                /* background: #eef2f6; */
                font-weight: bold;
                text-transform: uppercase;
            }

            .lab-group td {
                border-top: 2px solid #444;
            }

            .abnormal-high {
                color: #c00000;
                font-weight: bold;
            }

            .abnormal-low {
                color: #004aad;
                font-weight: bold;
            }

            .flag {
                font-size: 10px;
                font-weight: bold;
                margin-left: 4px;
            }

            /* ================= LAB FOOTER ================= */
            .lab-signature-table td {
                border: none;
                vertical-align: top;
            }

            .signature-title {
                font-weight: bold;
                margin-bottom: 6px;
            }

            .signature-box {
                text-align: center;
                margin-top: 5px;
            }

            .signature-box img {
                margin: 6px 0;
            }

            .lab-note {
                margin-top: 15px;
                font-size: 10px;
            }

            /* ================= HEADER FIX ================= */
            .header-table,
            .header-table td,
            .header-table tr {
                border: none !important;
            }

            /* ================= ULTRA PROFESSIONAL RESUME ================= */
            .resume-header {
                text-align: center;
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 14px;
                font-weight: bold;
                letter-spacing: 1px;
                padding: 10px 0;
                /* border: 1px solid #000; */
                border-bottom: none;
                margin-bottom: -2px;
            }

            .tgResume {
                width: 100%;
                border-collapse: collapse;
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 11px;
                color: #000;
                border: 1px solid #000;
            }

            .tgResume td {
                border: 1px solid #000;
                /* lebih tipis dari sebelumnya */
                padding: 8px 10px;
                vertical-align: top;
            }

            /* Label kiri */
            .tgResume td[colspan="4"] {
                font-weight: bold;
                width: 20%;
                /* background: #f5f5f5; */
                /* soft, tidak berat */
            }

            /* Isi panjang */
            .tgResume td[colspan="16"] {
                line-height: 1.7;
                padding-top: 10px;
                padding-bottom: 10px;
            }

            /* Baris tanggal sedikit lebih lega */
            .tgResume tr:nth-child(4) td {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            /* Diagnosis akhir lebih tegas */
            .tgResume tr:nth-child(8) td[colspan="16"] {
                font-weight: 600;
            }

            /* Section pemisah halus */
            .tgResume tr.section-top td {
                border-top: 1.8px solid #000;
            }

            /* TTD */
            .tgResume .tg-dvpl {
                text-align: right;
                /* padding-top: 40px; */
                line-height: 1.8;
            }

            .resume-identitas {
                border: 1px solid #000;
                border-collapse: collapse;
            }

            .resume-identitas td {
                border-top: 1px solid #000;
                border-bottom: 1px solid #000;
            }

            .resume-identitas tr:first-child td {
                border-top: none;
                border-bottom: none;
            }

            .resume-identitas tr:last-child td {
                border-bottom: none;
            }

            .resume-identitas td.label {
                border-right: 0;
                border-bottom: 0;
            }

            .resume-identitas td.value {
                border-left: 0;
            }

            .resume-tanggal {
                width: 100%;
                border: 1px solid #000;
                /* hanya border luar */
                border-collapse: collapse;
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 10.5px;
                margin-bottom: 10px;
            }

            .resume-tanggal td {
                border: none;
                /* hilangkan semua border dalam */
                padding: 4px 6px;
                vertical-align: middle;
            }

            .resume-tanggal td.label {
                font-weight: 600;
                width: 12%;
            }

            .resume-tanggal td.value {
                width: 14%;
            }

            .no-border-table {
                border-collapse: collapse;
            }

            .no-border-table td {
                border: none !important;
                padding: 4px 6px;
            }
        </style>
    </head>

    <body>
        {{-- ================= RESUME MEDIS ================= --}}
        @if ($resume)
            @php
                $data = $resume;

                $namaBersih = trim(
                    preg_replace('/^(SDR|TN|NY|AN|BY|NN)\s+|,?\s*(SDR|TN|NY|AN|BY|NN)$/i', "", $data->nm_pasien ?? ""),
                );
            @endphp
            <div class="header">
                <table class="header-table" width="100%">
                    <tr>
                        <td width="15%">
                            <img src="{{ public_path("dist/assets/images/pku_v3.png") }}" height="85">
                        </td>

                        <td width="45%">
                            <div class="rs-name">
                                {{ $setting->nama_instansi ?? "NAMA FASILITAS KESEHATAN" }}
                            </div>
                            <div class="rs-address">
                                {{ $setting->alamat_instansi ?? "" }} <br>
                                {{ $setting->kabupaten ?? "" }} - {{ $setting->propinsi ?? "" }} <br>
                                Telp: {{ $setting->kontak ?? "" }} |
                                Email: {{ $setting->email ?? "" }}
                            </div>
                        </td>

                        <td width="40%">
                            <div class="patient-box">
                                <b>No. RM</b> : {{ $data->no_rkm_medis ?? "" }} <br>
                                <b>Nama</b> : {{ $namaBersih }} <br>
                                <b>JK</b> :
                                {{ ($data->jk ?? "") == "L" ? "Laki-laki" : "Perempuan" }} <br>
                                <b>Tgl Lahir</b> :
                                {{ $data->tgl_lahir ?? "" }} <br>
                            </div>
                            <br>
                            Dicetak pada : {{ now()->format("d-m-Y H:i") }}
                        </td>
                    </tr>
                </table>
            </div>
            {{-- ================= RESUME MEDIS ================= --}}
            <div class="section-title">
                Resume Medis (Medical Discharge Summary)
            </div>

            <table class="resume-identitas">
                <tr>
                    <td class="label border-end-0">Nama Lengkap</td>
                    <td class="value border-start-0">
                        : {{ $namaBersih }}
                    </td>

                    <td class="label border-end-0">Ruang / Kelas</td>
                    <td class="value border-start-0">
                        : {{ $resume->kd_kamar ?? "-" }}
                    </td>
                </tr>

                <tr>
                    <td class="label border-end-0">No. Reg</td>
                    <td class="value border-start-0">
                        : {{ $resume->no_rawat }}
                    </td>

                    <td class="label border-end-0">No Telp</td>
                    <td class="value border-start-0">
                        : {{ $resume->no_tlp ?? "-" }}
                    </td>
                </tr>

                <tr>
                    <td class="label border-end-0">Alamat Lengkap</td>
                    <td colspan="3" class="value border-start-0">
                        : {{ $resume->alamat ?? "-" }}
                    </td>
                </tr>
            </table>
            <br>

            {{-- ================= TANGGAL PERAWATAN ================= --}}
            <table class="resume-tanggal">
                <tr>
                    <td class="label">Tgl. Masuk RS</td>
                    <td class="value">
                        : {{ \Carbon\Carbon::parse($resume->tgl_registrasi)->format("d-m-Y") }}
                    </td>

                    <td class="label">Tanggal Keluar RS</td>
                    <td class="value">
                        : {{ \Carbon\Carbon::parse($resume->tgl_keluar)->format("d-m-Y") }}
                    </td>

                    <td class="label">Tanggal Meninggal</td>
                    <td class="value">: -</td>
                </tr>
            </table>

            <table class="tgResume" width="100%">
                <tbody>

                    <tr>
                        <td width="35%"><strong>Diagnosa Medis</strong></td>
                        <td width="65%">{{ $resume->diagnosa_awal }}</td>
                    </tr>

                    <tr>
                        <td><strong>Dokter yang Merawat</strong></td>
                        <td>{{ $resume->nm_dokter }}</td>
                    </tr>

                    <tr>
                        <td><strong>Dokter Konsultan</strong></td>
                        {{-- <td>{{ $resume->nm_dokter }}</td> --}}
                        <td>-</td>
                    </tr>

                    {{-- ANAMNESIS --}}
                    <tr>
                        <td><strong><strong>Anamnesis</strong><br>
                                -> Keluhan Utama<br>
                                -> Riwayat Penyakit Sekarang<br>
                                -> Riwayat Penyakit Dahulu</strong></td>
                        <td>{!! nl2br(e($resume->keluhan_utama)) !!}</td>
                    </tr>

                    {{-- PEMERIKSAAN --}}
                    <tr>
                        <td><strong><strong>Hasil Pemeriksaan waktu MRS</strong><br>
                                -> Fisik<br></td>
                        <td>
                            {!! $resume->pemeriksaan_fisik ? nl2br(e($resume->pemeriksaan_fisik)) : "Tidak Dilakukan" !!}
                        </td>
                    </tr>

                    <tr>
                        <td><strong><strong>Hasil Pemeriksaan waktu MRS</strong><br>
                                -> Laborat</strong></td>
                        <td>
                            {!! $resume->hasil_laborat ? nl2br(e($resume->hasil_laborat)) : "Tidak Dilakukan" !!}
                        </td>
                    </tr>

                    <tr>
                        <td><strong><strong>Hasil Pemeriksaan waktu MRS</strong><br>
                                -> Radiologi, dan Lain-lain</strong></td>
                        <td>
                            {!! $resume->pemeriksaan_penunjang ? nl2br(e($resume->pemeriksaan_penunjang)) : "Tidak Dilakukan" !!}
                        </td>
                    </tr>

                    {{-- DIAGNOSIS AKHIR --}}
                    <tr>
                        <td>
                            <strong>Diagnosis Akhir <br>
                                Diagnosis PA</strong> <br>
                            <small>(Ditulis huruf balok dan tidak disingkat)</small>
                        </td>
                        <td>
                            <strong>
                                {{ strtoupper($resume->diagnosa_utama) }}
                                ({{ $resume->kd_diagnosa_utama }})
                            </strong>
                        </td>
                    </tr>

                    {{-- PENGOBATAN --}}
                    <tr>
                        <td><strong>Pengobatan</strong></td>
                        <td>{!! nl2br(e($resume->obat_di_rs)) !!}</td>
                    </tr>

                    {{-- PROSEDUR --}}
                    <tr>
                        <td><strong>Prosedur Tindakan</strong></td>
                        <td>{{ $resume->tindakan_dan_operasi }}</td>
                    </tr>

                    {{-- RENCANA --}}
                    <tr>
                        <td><strong>Rencana Pemeriksaan Lanjutan</strong></td>
                        <td>
                            Kontrol {{ \Carbon\Carbon::parse($resume->kontrol)->format("d-m-Y H:i") }}<br>
                            {{ $resume->ket_dilanjutkan }}
                        </td>
                    </tr>

                    {{-- KEADAAN --}}
                    <tr>
                        <td><strong>Keadaan Waktu Keluar RS</strong></td>
                        <td>{{ $resume->keadaan }}</td>
                    </tr>

                    {{-- ANJURAN --}}
                    <tr>
                        <td><strong>Anjuran Perawatan di Rumah</strong></td>
                        <td>
                            {!! nl2br(e($resume->edukasi)) !!}<br><br>
                            <strong>Obat Pulang</strong><br>
                            {!! nl2br(e($resume->obat_pulang)) !!}
                        </td>
                    </tr>

                    {{-- PROGNOSIS --}}
                    <tr>
                        <td><strong>Prognosis / Sebab Meninggal</strong></td>
                        <td>{{ $resume->prognosis }}</td>
                    </tr>

                    {{-- TTD --}}
                    <tr>
                        <td><strong>Nama Dokter yang Merawat</strong></td>
                        <td class="tg-dvpl">
                            Paciran, {{ \Carbon\Carbon::parse($resume->tgl_registrasi)->translatedFormat("d F Y") }}
                            <br><br>

                            <img style="text-align:center;"
                                src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Dokter: " . $resume->nm_dokter . "\nSIP: " . $resume->nomor_sip, "QRCODE", 3, 3) }}"
                                width="70">

                            <br>
                            <strong>{{ $resume->nm_dokter }}</strong>
                        </td>

                    </tr>

                </tbody>
            </table>
        @endif
    </body>

</html>
