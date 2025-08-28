<?php

namespace App\Repositories\Anjungan;

use App\Models\AdmisiModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdmisiRepository
{
    public function generateNomorAntrian()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        // Ambil nomor antrian terakhir untuk tanggal hari ini
        $lastAntrian = AdmisiModel::whereDate('tanggal', $tanggalHariIni)
            ->orderBy('no_antrian', 'desc')
            ->first(); // Ambil satu data terbaru

        if ($lastAntrian) {
            // Jika sudah ada antrian hari ini, tambahkan 1
            $nomorAntrian = $lastAntrian->no_antrian + 1;
        } else {
            // Jika belum ada, mulai dari 1
            $nomorAntrian = 1;
        }

        // Simpan nomor antrian baru ke database
        AdmisiModel::create([
            'no_antrian' => $nomorAntrian,
            'tanggal' => $tanggalHariIni,
        ]);

        return $nomorAntrian;
    }

}
