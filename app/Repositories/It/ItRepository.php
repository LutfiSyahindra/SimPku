<?php

namespace App\Repositories\It;

use App\Models\khanza\laporanModel;
use Illuminate\Support\Facades\DB;

class ItRepository
{
    public function getLaporan()
    {
        return laporanModel::get();
    }

    public function forWidget()
    {
        return laporanModel::query();
    }

    public function createLaporan(array $data)
    {
        return laporanModel::create($data);
    }

    public function findLaporan($id)
    {
        return laporanModel::findOrFail($id);
    }

    public function countLaporan()
    {
        return laporanModel::count();
    }

}
