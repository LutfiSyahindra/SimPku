<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Dokumen Lengkap - {{ $cppt->first()->no_rawat ?? "" }}</title>

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

        @if ($spri)
            @php
                $data = $cppt->first();

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

            <div class="section-title">
                SURAT PERINTAH RAWAT INAP
            </div>

            <br>
            <div style="width: 85%; margin: 0 auto;">
                <table width="100%" class="no-border-table">
                    <tr>
                        <td width="35%"><strong>Nama Pasien</strong></td>
                        <td width="65%">
                            : {{ $namaBersih }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>No. Kartu BPJS</strong></td>
                        <td>
                            : {{ $spri->no_peserta }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>
                            : {{ $spri->jk }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Umur / Tanggal Lahir</strong></td>
                        <td>
                            : {{ $spri->umur }} /
                            {{ \Carbon\Carbon::parse($spri->tgl_lahir)->translatedFormat("d F Y") }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Keluhan</strong></td>
                        <td>
                            : {{ $spri->keluhan }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Diagnosa</strong></td>
                        <td>
                            : {{ $spri->diagnosa_awal }}<br>
                            ..............................................................................
                        </td>
                    </tr>
                </table>

                <br>

                <p>
                    Karena kondisi tersebut pasien perlu dirawat
                </p>

                <br><br><br>

                <table width="100%" class="no-border-table">
                    <tr>
                        <td width="60%"></td>
                        <td width="40%" style="text-align: center;">
                            Paciran, {{ \Carbon\Carbon::now()->translatedFormat("d F Y") }}<br><br>
                            Dokter yang merawat
                            <br><br>

                            @if (!empty($spri->dpjp_nama))
                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Dokter: " . $spri->dpjp_nama . "\nSIP: " . $spri->nomor_sip, "QRCODE", 3, 3) }}"
                                    width="70" style="margin-bottom:10px;">
                                <br>
                                <strong>{{ $spri->dpjp_nama }}</strong>
                            @endif
                        </td>
                    </tr>
                </table>

            </div>
        @endif

        @if ($ritp)
            @php
                $data = $cppt->first();

                $namaBersih = trim(
                    preg_replace('/^(SDR|TN|NY|AN|BY|NN)\s+|,?\s*(SDR|TN|NY|AN|BY|NN)$/i', "", $data->nm_pasien ?? ""),
                );
            @endphp
            <div class="page-break"></div>

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

            <div class="section-title">
                BUKTI PELAYANAN RITP
            </div>

            <br>
            <div style="width: 85%; margin: 0 auto;">
                <table width="100%" class="no-border-table">
                    <tr>
                        <td width="35%"><strong>Nama Penderita</strong></td>
                        <td width="65%">
                            : {{ $namaBersih ?? "" }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>No. Kartu</strong></td>
                        <td>
                            : {{ $ritp->no_peserta ?? "" }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Umur / Tanggal Lahir</strong></td>
                        <td>
                            : {{ $ritp->umur ?? "" }} /
                            {{ \Carbon\Carbon::parse($ritp->tgl_lahir ?? "1900-01-01")->translatedFormat("d F Y") }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>
                            : {{ $ritp->alamat ?? "" }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Diagnosa</strong></td>
                        <td>
                            : {{ $ritp->diagnosa_awal ?? "" }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Tindakan</strong></td>
                        <td>
                            :
                            {{ implode(", ", array_filter([$ritp->perawatan_dokter ?? null, $ritp->perawatan_perawat ?? null])) }}
                            <br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Tanggal Masuk</strong></td>
                        <td>
                            :
                            {{ \Carbon\Carbon::parse($ritp->tgl_masuk ?? "1900-01-01")->translatedFormat("d F Y") }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Tanggal Pulang</strong></td>
                        <td>
                            :
                            {{ \Carbon\Carbon::parse($ritp->tgl_keluar ?? "1900-01-01")->translatedFormat("d F Y") }}<br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Jumlah Klaim</strong></td>
                        <td>
                            :
                            {{ isset($ritp->totalpiutang) ? "Rp " . number_format($ritp->totalpiutang, 0, ",", ".") : "" }}
                            <br>
                            ..............................................................................
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>
                            : {{ $ritp->stts_pulang ?? "" }}<br>
                            ..............................................................................
                        </td>
                    </tr>
                </table>

                <br><br><br>

                <table width="100%" class="no-border-table">
                    <tr>
                        <!-- TTD Penderita -->
                        <td width="50%" style="text-align: center;">
                            Penderita / Anggota Keluarga
                            <br><br>
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Pasien: " . $namaBersih, "QRCODE", 3, 3) }}"
                                width="70" style="margin-bottom:10px;">
                            <br>

                            <strong>{{ $namaBersih ?? "" }}</strong><br>
                            ___________________________________
                            <br><br>

                            No. HP: ...........................................
                        </td>

                        <!-- TTD Dokter -->
                        <td width="40%" style="text-align: center;">
                            Paciran, {{ \Carbon\Carbon::now()->translatedFormat("d F Y") }}<br><br>
                            Dokter Yang Menolong / Merawat
                            <br><br>

                            @if (!empty($spri->dpjp_nama))
                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Dokter: " . $spri->dpjp_nama . "\nSIP: " . $spri->nomor_sip, "QRCODE", 3, 3) }}"
                                    width="70" style="margin-bottom:10px;">
                                <br>
                                <strong>{{ $spri->dpjp_nama }}</strong>
                            @endif
                        </td>
                    </tr>
                </table>

            </div>
        @endif

        @if ($cppt)
            {{-- ================= HEADER ================= --}}
            <div class="header">
                <table class="header-table" width="100%">
                    @php
                        $data = $cppt->first();

                        $namaBersih = trim(
                            preg_replace(
                                '/^(SDR|TN|NY|AN|BY|NN)\s+|,?\s*(SDR|TN|NY|AN|BY|NN)$/i',
                                "",
                                $data->nm_pasien ?? "",
                            ),
                        );
                    @endphp

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

            {{-- ================= CPPT ================= --}}
            <div class="section-title">
                Catatan Perkembangan Pasien Terintegrasi (CPPT)
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="12%">Tgl / Jam</th>
                        <th width="12%">Profesi</th>
                        <th width="46%">SOAP</th>
                        <th width="15%">Instruksi</th>
                        <th width="15%">Paraf</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($cppt as $row)
                        <tr>
                            <td class="text-center">
                                {{ $row->tgl_perawatan }} <br>
                                {{ $row->jam_rawat }}
                            </td>

                            <td>{{ $row->jabatan }}</td>

                            <td>
                                <div class="soap-title">S :</div>
                                <div class="soap-content">
                                    {!! nl2br(e($row->keluhan ?? "-")) !!}
                                </div>

                                <div class="soap-title">O :</div>
                                <div class="soap-content">
                                    {!! nl2br(e($row->pemeriksaan ?? "-")) !!}<br>
                                    TD: {{ $row->tensi ?? "-" }} &nbsp;&nbsp;&nbsp;
                                    S: {{ $row->suhu_tubuh ?? "-" }} &nbsp;&nbsp;&nbsp;
                                    N: {{ $row->nadi ?? "-" }} &nbsp;&nbsp;&nbsp;
                                    RR: {{ $row->respirasi ?? "-" }} &nbsp;&nbsp;&nbsp;
                                    SpO2: {{ $row->spo2 ?? "-" }}

                                </div>

                                <div class="soap-title">A :</div>
                                <div class="soap-content">
                                    {!! nl2br(e($row->penilaian ?? "-")) !!}
                                </div>

                                <div class="soap-title">P :</div>
                                <div class="soap-content">
                                    {!! nl2br(e($row->rtl ?? "-")) !!}
                                </div>
                            </td>

                            <td>{!! nl2br(e($row->instruksi ?? "-")) !!}</td>

                            <td class="text-center">
                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Yang melakukan: " . $row->nama_dokter . "\nSIP: " . $row->nomor_sip, "QRCODE", 3, 3) }}"
                                    width="60">
                                <div style="font-size:9px;margin-top:4px;">
                                    {{ $row->nama_dokter }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- ================= RESUME MEDIS ================= --}}
        @if ($resume)
            @php
                $data = $cppt->first();

                $namaBersih = trim(
                    preg_replace('/^(SDR|TN|NY|AN|BY|NN)\s+|,?\s*(SDR|TN|NY|AN|BY|NN)$/i', "", $data->nm_pasien ?? ""),
                );
            @endphp
            <div class="page-break"></div>
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

        {{-- ================= LAB ================= --}}
        @if ($headerLab)
            @php
                $data = $cppt->first();

                $namaBersih = trim(
                    preg_replace('/^(SDR|TN|NY|AN|BY|NN)\s+|,?\s*(SDR|TN|NY|AN|BY|NN)$/i', "", $data->nm_pasien ?? ""),
                );
            @endphp
            <div class="page-break"></div>
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
            <div class="section-title">
                Hasil Pemeriksaan Laboratorium
            </div>

            {{-- ================= HEADER LAB ================= --}}
            <div class="lab-header-box">
                <table class="lab-header-table" width="100%">
                    <tr>
                        <td width="50%">
                            <b>No. Periksa</b> : {{ $headerLab->no_rawat }} <br>
                            <b>Dokter Pengirim</b> : {{ $headerLab->dokter_perujuk_nama }} <br>
                            <b>Petugas Lab</b> : {{ $headerLab->petugas_lab }}
                        </td>

                        <td width="50%">
                            <b>Tgl. Keluar Hasil</b> : {{ $headerLab->tgl_periksa_format }} <br>
                            <b>Jam Keluar Hasil</b> : {{ $headerLab->jam }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ================= TABEL HASIL ================= --}}
            <table class="lab-table">
                <thead>
                    <tr>
                        <th width="35%">Pemeriksaan</th>
                        <th width="15%">Hasil</th>
                        <th width="15%">Satuan</th>
                        <th width="20%">Nilai Rujukan</th>
                        <th width="15%">Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @php $group=null; @endphp
                    @foreach ($detailLab as $item)
                        @if ($group != $item->nm_perawatan)
                            <tr class="lab-group">
                                <td colspan="5">{{ $item->nm_perawatan }}</td>
                            </tr>
                            @php $group = $item->nm_perawatan; @endphp
                        @endif

                        <tr>
                            <td>{{ $item->Pemeriksaan }}</td>
                            <td class="text-center"><b>{{ $item->nilai }}</b></td>
                            <td class="text-center">{{ $item->satuan }}</td>
                            <td class="text-center">{{ $item->nilai_rujukan }}</td>
                            <td class="text-center">{{ $item->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- ================= LAB FOOTER ================= --}}
            <div class="lab-footer">

                <table class="lab-signature-table" width="100%">
                    <tr>
                        {{-- Penanggung Jawab --}}
                        <td width="50%" align="center">
                            <div class="signature-title">Penanggung Jawab</div>

                            <div class="signature-box">
                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Penanggung Jawab: " . $headerLab->nm_dokter . "\nSIP: " . $headerLab->nomor_sip_dokter, "QRCODE", 3, 3) }}"
                                    width="80">
                                <div style="margin-top:6px;">
                                    {{ $headerLab->nm_dokter }}
                                </div>
                            </div>
                        </td>

                        {{-- Petugas Laboratorium --}}
                        <td width="50%" align="center">
                            <div class="signature-title" style="margin-top:5px;">
                                Petugas Laboratorium
                            </div>

                            <div class="signature-box">
                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("Petugas Lab: " . $headerLab->petugas_lab, "QRCODE", 3, 3) }}"
                                    width="80">
                                <div style="margin-top:6px;">
                                    {{ $headerLab->petugas_lab }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>

        @endif

    </body>

</html>
