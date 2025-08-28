<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class reg_periksaModel extends Model
{
    protected $connection = 'mysql_khanza';
    protected $table = 'antripoli';

    public static function dataByDate()
    {
        $data = DB::connection('mysql_khanza')
        ->table('antripoli')
        ->join('poliklinik', 'antripoli.kd_poli', '=', 'poliklinik.kd_poli')
        ->join('reg_periksa', 'antripoli.no_rawat', '=', 'reg_periksa.no_rawat')
        ->join('dokter', 'antripoli.kd_dokter', '=', 'dokter.kd_dokter')
        ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
        ->select('antripoli.no_rawat', 'reg_periksa.no_reg', 'poliklinik.nm_poli', 'dokter.nm_dokter', 'pasien.nm_pasien')
        ->whereDate('reg_periksa.tgl_registrasi', now()->toDateString())
        ->groupBy('antripoli.no_rawat', 'reg_periksa.no_reg', 'poliklinik.nm_poli', 'dokter.nm_dokter', 'pasien.nm_pasien')
        ->distinct()
        ->get();
        return $data;
    }

    public static function getLatestQueue()
    {
        return DB::connection('mysql_khanza')->table('antripoli')
        ->join('reg_periksa', 'antripoli.no_rawat', '=', 'reg_periksa.no_rawat')
        ->join('poliklinik', 'antripoli.kd_poli', '=', 'poliklinik.kd_poli')
        ->join('dokter', 'antripoli.kd_dokter', '=', 'dokter.kd_dokter')
        ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
        ->whereDate('reg_periksa.tgl_registrasi', now()->toDateString())
        ->orderBy('antripoli.updated_at', 'desc') // Ambil antrean yang terbaru diupdate
        ->orderBy('antripoli.kd_dokter', 'asc')
        ->select(
            'reg_periksa.no_reg',
            'poliklinik.nm_poli',
            'dokter.nm_dokter',
            'pasien.nm_pasien'
        )
        ->first(); 

    }



}
