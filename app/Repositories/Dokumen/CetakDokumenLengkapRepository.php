<?php

namespace App\Repositories\Dokumen;

use Illuminate\Support\Facades\DB;

class CetakDokumenLengkapRepository
{
    protected $connection = 'mysql_khanza';

    public function getDokumenCppt(string $no_rawat)
    {
        return DB::connection($this->connection)
            ->table('pasien')
            ->join('reg_periksa', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pemeriksaan_ranap', 'pemeriksaan_ranap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pegawai', 'pemeriksaan_ranap.nip', '=', 'pegawai.nik')
            ->join('sip', 'pegawai.nik', '=', 'sip.nik')
            ->where('pemeriksaan_ranap.no_rawat', $no_rawat)
            ->orderBy('pemeriksaan_ranap.tgl_perawatan')
            ->orderBy('pemeriksaan_ranap.jam_rawat')
            ->select(
                'pemeriksaan_ranap.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'pemeriksaan_ranap.tgl_perawatan',
                'pemeriksaan_ranap.jam_rawat',
                'pemeriksaan_ranap.suhu_tubuh',
                'pemeriksaan_ranap.tensi',
                'pemeriksaan_ranap.nadi',
                'pemeriksaan_ranap.respirasi',
                'pemeriksaan_ranap.tinggi',
                'pemeriksaan_ranap.berat',
                'pemeriksaan_ranap.spo2',
                'pemeriksaan_ranap.gcs',
                'pemeriksaan_ranap.kesadaran',
                'pemeriksaan_ranap.keluhan',
                'pemeriksaan_ranap.pemeriksaan',
                'pemeriksaan_ranap.alergi',
                'pemeriksaan_ranap.penilaian',
                'pemeriksaan_ranap.rtl',
                'pemeriksaan_ranap.instruksi',
                'pemeriksaan_ranap.evaluasi',
                'pemeriksaan_ranap.nip',
                'pegawai.nama as nama_dokter',
                'pegawai.jbtn as jabatan',
                'sip.sip as nomor_sip'
            )
            ->get();
    }

    public function getHeaderLab($noRawat)
    {
        return DB::connection($this->connection)
            ->table('periksa_lab')
            ->join('reg_periksa', 'periksa_lab.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('petugas', 'periksa_lab.nip', '=', 'petugas.nip')
            ->join('dokter', 'periksa_lab.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
            ->join('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
            ->join('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
            ->leftJoin('dokter as dokter_perujuk', 
                'periksa_lab.dokter_perujuk', '=', 'dokter_perujuk.kd_dokter')
            ->leftJoin('sip as sip_dokter', function($join) {
                $join->on('sip_dokter.nik', '=', 'dokter.kd_dokter');
            })
            ->leftJoin('sip as sip_petugas', function($join) {
                $join->on('sip_petugas.nik', '=', 'petugas.nip');
            })  
            ->where('periksa_lab.kategori', 'PK')
            ->where('periksa_lab.no_rawat', $noRawat)
            ->selectRaw("
                periksa_lab.no_rawat,
                periksa_lab.tgl_periksa,
                periksa_lab.jam,
                reg_periksa.no_rkm_medis,
                pasien.nm_pasien,
                pasien.jk,
                pasien.umur,
                petugas.nama as petugas_lab,
                periksa_lab.nip,
                dokter_perujuk.nm_dokter as dokter_perujuk_nama,
                periksa_lab.kd_dokter,
                dokter.nm_dokter,
                sip_dokter.sip as nomor_sip_dokter,
                sip_petugas.str as nomor_str_petugas,
                CONCAT(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab) as alamat,
                DATE_FORMAT(periksa_lab.tgl_periksa,'%d-%m-%Y') as tgl_periksa_format,
                DATE_FORMAT(pasien.tgl_lahir,'%d-%m-%Y') as lahir
            ")
            ->orderBy('periksa_lab.tgl_periksa', 'desc')
            ->orderBy('periksa_lab.jam', 'desc')
            ->first();
    }

    public function getJenisLab($no_rawat)
    {
        return DB::connection($this->connection)
            ->table('periksa_lab')
            ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->where('periksa_lab.kategori', 'PK')
            ->where('periksa_lab.no_rawat', $no_rawat)
            // ->where('periksa_lab.tgl_periksa', $tgl_periksa)
            // ->where('periksa_lab.jam', $jam)
            ->select(
                'jns_perawatan_lab.kd_jenis_prw',
                'jns_perawatan_lab.nm_perawatan'
            )
            ->distinct()
            ->get();
    }

    public function getDetailLab($no_rawat)
    {
        return DB::connection($this->connection)
            ->table('detail_periksa_lab')
            ->join('template_laboratorium', 'detail_periksa_lab.id_template', '=', 'template_laboratorium.id_template')
            ->join('jns_perawatan_lab', 'detail_periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->where('detail_periksa_lab.no_rawat', $no_rawat)
            // ->where('detail_periksa_lab.tgl_periksa', $tgl_periksa)
            // ->where('detail_periksa_lab.jam', $jam)
            ->orderBy('jns_perawatan_lab.nm_perawatan')
            ->orderBy('template_laboratorium.urut')
            ->select(
                'jns_perawatan_lab.nm_perawatan',
                'template_laboratorium.Pemeriksaan',
                'detail_periksa_lab.nilai',
                'template_laboratorium.satuan',
                'detail_periksa_lab.nilai_rujukan',
                'detail_periksa_lab.keterangan'
            )
            ->get();
    }

    public function getResumeByNoRawat(string $noRawat)
    {
        return DB::connection($this->connection)
            ->table('resume_pasien_ranap')
            ->join('reg_periksa', 'resume_pasien_ranap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'resume_pasien_ranap.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('sip', 'dokter.kd_dokter', '=', 'sip.nik')
            ->join('dokter as pengirim', 'reg_periksa.kd_dokter', '=', 'pengirim.kd_dokter')
            ->join('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
            ->join('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
            ->join('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
            ->join('penjab', 'penjab.kd_pj', '=', 'reg_periksa.kd_pj')
            ->join('kamar_inap', 'resume_pasien_ranap.no_rawat', '=', 'kamar_inap.no_rawat')
            ->select(
                'reg_periksa.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'resume_pasien_ranap.kd_dokter',
                'dokter.nm_dokter',
                'reg_periksa.kd_dokter as kodepengirim',
                'pengirim.nm_dokter as pengirim',
                'reg_periksa.tgl_registrasi',
                'reg_periksa.jam_reg',
                'resume_pasien_ranap.*',
                'reg_periksa.kd_pj',
                'penjab.png_jawab',
                'reg_periksa.umurdaftar',
                'reg_periksa.sttsumur',
                'pasien.pekerjaan',
                'pasien.jk',
                'pasien.no_tlp',
                'pasien.tgl_lahir',
                'kamar_inap.tgl_keluar',
                'kamar_inap.kd_kamar',
                'sip.sip as nomor_sip',
                DB::raw("concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab) as alamat")
            )
            ->where('resume_pasien_ranap.no_rawat', $noRawat)
            ->first();
    }

    public function getSpriByNoRawat(string $noRawat)
    {
        return DB::connection($this->connection)
            ->table('kamar_inap')
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('dpjp_ranap', 'kamar_inap.no_rawat', '=', 'dpjp_ranap.no_rawat')
            ->join('sip', 'dpjp_ranap.kd_dokter', '=', 'sip.nik')
            ->leftJoin('dokter as dpjp', 
                'dpjp_ranap.kd_dokter', '=', 'dpjp.kd_dokter')
            ->select(
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'pasien.umur',
                'pasien.no_peserta',
                'kamar_inap.diagnosa_awal',
                'dokter.nm_dokter',
                'dpjp.nm_dokter as dpjp_nama',
                'sip.sip as nomor_sip',
                DB::raw('(
                    SELECT keluhan 
                    FROM pemeriksaan_ranap pr
                    WHERE pr.no_rawat = kamar_inap.no_rawat
                    ORDER BY pr.tgl_perawatan ASC, pr.jam_rawat ASC
                    LIMIT 1
                ) as keluhan')
            )
            ->where('kamar_inap.no_rawat', $noRawat)
            ->first();
    }

    // public function getRitpByNoRawat(string $noRawat)
    // {
        
    //     return DB::connection($this->connection)
    //         ->table('kamar_inap')
    //         ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
    //         ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
    //         ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
    //         ->join('dpjp_ranap', 'kamar_inap.no_rawat', '=', 'dpjp_ranap.no_rawat')
    //         ->leftJoin('dokter as dpjp', 
    //             'dpjp_ranap.kd_dokter', '=', 'dpjp.kd_dokter')
    //         ->leftJoin('pemeriksaan_ranap', 'pemeriksaan_ranap.no_rawat', '=', 'kamar_inap.no_rawat')
    //         ->leftJoin('rawat_inap_dr', 'rawat_inap_dr.no_rawat', '=', 'kamar_inap.no_rawat')
    //         ->leftJoin('rawat_inap_pr', 'rawat_inap_pr.no_rawat', '=', 'kamar_inap.no_rawat')
    //         ->leftJoin('jns_perawatan_inap as jns_pr', 
    //             'jns_pr.kd_jenis_prw', '=', 'rawat_inap_pr.kd_jenis_prw')
    //         ->leftJoin('jns_perawatan_inap as jns_dr', 
    //             'jns_dr.kd_jenis_prw', '=', 'rawat_inap_dr.kd_jenis_prw')
    //         ->select(
    //             'pasien.nm_pasien',
    //             'pasien.jk',
    //             'pasien.tgl_lahir',
    //             'pasien.umur',
    //             'pasien.alamat',
    //             'pasien.no_peserta',
    //             'kamar_inap.lama',
    //             'kamar_inap.diagnosa_awal',
    //             'kamar_inap.tgl_masuk',
    //             'kamar_inap.tgl_keluar',
    //             'kamar_inap.stts_pulang',
    //             'dpjp.nm_dokter as dpjp_nama',
    //             'dokter.nm_dokter',

    //             DB::raw('MAX(pemeriksaan_ranap.rtl) as rtl'),

    //             DB::raw('GROUP_CONCAT(DISTINCT jns_pr.nm_perawatan SEPARATOR ", ") as perawatan_perawat'),

    //             DB::raw('GROUP_CONCAT(DISTINCT jns_dr.nm_perawatan SEPARATOR ", ") as perawatan_dokter'),

    //             DB::raw('(kamar_inap.lama * 300000) as totalpiutang')
    //         )
    //         ->where('kamar_inap.no_rawat', $noRawat)
    //         ->groupBy(
    //             'pasien.nm_pasien',
    //             'pasien.jk',
    //             'pasien.tgl_lahir',
    //             'pasien.umur',
    //             'pasien.alamat',
    //             'pasien.no_peserta',
    //             'kamar_inap.lama',
    //             'kamar_inap.diagnosa_awal',
    //             'kamar_inap.tgl_masuk',
    //             'kamar_inap.tgl_keluar',
    //             'kamar_inap.stts_pulang',
    //             'dokter.nm_dokter',
    //             'dpjp.nm_dokter'
    //         )
    //         // ->orderBy('kamar_inap.tgl_keluar', 'desc')
    //         // ->orderBy('kamar_inap.jam_keluar', 'desc')
    //         ->first();
    // }

    public function getRitpByNoRawat(string $noRawat)
    {
        $excludeKode = [
            'RI00056','RI00058','RI00059','RI00060','RI00061','RI00065',
            'RI00069','RI00070','RI00071','RI00075','RI00076','RI00098',
            'RI00100','RI00102','RI00127','RI00128','RI00129','RI00130',
            'RI00131','RI00132','RI00133','RI00134','RI00135','RI00136',
            'RI00137','RI00138','RI00139','RI00140','RI00141','RI00142',
            'RI00143','RI00144','RI00145','RI00146','RI00147','RI00148',
            'RI00149','RI00150','RI00151','RI00152','RI00153','RI00155',
            'RI00159'
        ];

        return DB::connection($this->connection)
            ->table('kamar_inap')

            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('dpjp_ranap', 'kamar_inap.no_rawat', '=', 'dpjp_ranap.no_rawat')

            ->leftJoin('dokter as dpjp', 'dpjp_ranap.kd_dokter', '=', 'dpjp.kd_dokter')
            ->leftJoin('pemeriksaan_ranap', 'pemeriksaan_ranap.no_rawat', '=', 'kamar_inap.no_rawat')

            // ✅ WAJIB ADA DULU
            ->leftJoin('rawat_inap_pr', 'rawat_inap_pr.no_rawat', '=', 'kamar_inap.no_rawat')
            ->leftJoin('rawat_inap_dr', 'rawat_inap_dr.no_rawat', '=', 'kamar_inap.no_rawat')

            // ✅ BARU FILTER DI SINI
            ->leftJoin('jns_perawatan_inap as jns_pr', function ($join) use ($excludeKode) {
                $join->on('jns_pr.kd_jenis_prw', '=', 'rawat_inap_pr.kd_jenis_prw')
                    ->whereNotIn('jns_pr.kd_jenis_prw', $excludeKode);
            })

            ->leftJoin('jns_perawatan_inap as jns_dr', function ($join) use ($excludeKode) {
                $join->on('jns_dr.kd_jenis_prw', '=', 'rawat_inap_dr.kd_jenis_prw')
                    ->whereNotIn('jns_dr.kd_jenis_prw', $excludeKode);
            })

            ->select(
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'pasien.umur',
                'pasien.alamat',
                'pasien.no_peserta',
                'kamar_inap.lama',
                'kamar_inap.diagnosa_awal',
                'kamar_inap.tgl_masuk',
                'kamar_inap.tgl_keluar',
                'kamar_inap.stts_pulang',
                'dpjp.nm_dokter as dpjp_nama',
                'dokter.nm_dokter',

                DB::raw('MAX(pemeriksaan_ranap.rtl) as rtl'),

                DB::raw('GROUP_CONCAT(DISTINCT TRIM(REPLACE(UPPER(jns_pr.nm_perawatan), " BPJS", "")) SEPARATOR ", ") as perawatan_perawat'),

                DB::raw('GROUP_CONCAT(DISTINCT TRIM(REPLACE(UPPER(jns_dr.nm_perawatan), " BPJS", "")) SEPARATOR ", ") as perawatan_dokter'),

                DB::raw('(kamar_inap.lama * 300000) as totalpiutang')
            )

            ->where('kamar_inap.no_rawat', $noRawat)

            ->groupBy(
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'pasien.umur',
                'pasien.alamat',
                'pasien.no_peserta',
                'kamar_inap.lama',
                'kamar_inap.diagnosa_awal',
                'kamar_inap.tgl_masuk',
                'kamar_inap.tgl_keluar',
                'kamar_inap.stts_pulang',
                'dokter.nm_dokter',
                'dpjp.nm_dokter'
            )

            ->first();
    }

    public function getSetting()
    {
        return DB::connection($this->connection)
            ->table('setting')
            ->first();
    }
}
