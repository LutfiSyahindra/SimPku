<?php

namespace App\Repositories\Surat;

use App\Models\KategoriSuratModel;
use App\Models\SuratMasukModel;
use Carbon\Carbon;

class SuratMasukRepository
{
    public function getSuratMasuk()
    {
        return SuratMasukModel::get();
    }

    public function storeSuratMasuk(array $data)
    {
        $record = SuratMasukModel::create($data);
        
        // Jangan return $record langsung (mengandung file BLOB)
        return $record->id; // atau true
    }

    public function findSuratMasuk($id)
    {
        return SuratMasukModel::findOrFail($id);
    }

}
