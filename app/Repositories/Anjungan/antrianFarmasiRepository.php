<?php

namespace App\Repositories\Anjungan;

use App\Models\khanza\resepObatModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class antrianFarmasiRepository
{
    public function generateAntrianFarmasi($no_rawat)
    {
        Log::info("Mencari data dengan No Rawat: " . $no_rawat);
        $antrianFarmasi = resepObatModel::where('no_rawat', $no_rawat)
        ->select('*', DB::raw("RIGHT(no_resep, 3) as no_resep_potong"))
        ->get();
        return $antrianFarmasi;
    }
    public function generateDataFarmasi($nomor)
    {
        // Log::info("Mencari data dengan No Rawat: " . $no_rawat);
        $dataFarmasi = resepObatModel::where('no_resep', $nomor)
        ->select('*', DB::raw("RIGHT(no_resep, 3) as no_resep_potong"))
        ->get();
        return $dataFarmasi;
    }

}
