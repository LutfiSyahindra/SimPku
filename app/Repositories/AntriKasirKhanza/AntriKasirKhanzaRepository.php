<?php

namespace App\Repositories\AntriKasirKhanza;

use App\Models\khanza\antri_kasirModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AntriKasirKhanzaRepository
{
    public static function getAntriKasir()
    {
        return antri_kasirModel::selectRaw('antri_kasir.no_rawat as no_reg')
            ->addSelect([
                'antri_kasir.status',  // Gunakan prefix tabel
                'antri_kasir.no_rawat', // Gunakan prefix tabel
                'pasien.nm_pasien',
            ])
            ->join('reg_periksa', 'antri_kasir.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('antri_kasir.status', 'Dipanggil') // Gunakan prefix tabel
            ->limit(1)
            ->get()
            ->toArray();
    }

    public static function updateAntriKasir($panggil){
        return antri_kasirModel::where('status', $panggil)
            ->update([
                'status' => 'Selesai'
            ]);
    }
}
