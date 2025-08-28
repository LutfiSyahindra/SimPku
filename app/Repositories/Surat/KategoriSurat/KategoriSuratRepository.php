<?php

namespace App\Repositories\Surat\KategoriSurat;

use App\Models\KategoriSuratModel;
use Carbon\Carbon;

class KategoriSuratRepository
{
    public function getKategoriSurat()
    {
        return KategoriSuratModel::get();
    
    }
    public function storeKategoriSurat(array $data)
    {
        return KategoriSuratModel::create($data);
    }

    public function findKategoriSurat($id)
    {
        return KategoriSuratModel::findOrFail($id);
    }
    
    public function updateKategoriSurat(array $data, $id)
    {
        return KategoriSuratModel::where('id', $id)->update($data);
    }

}
