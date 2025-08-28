<?php

namespace App\Services\Anjungan;

use App\Repositories\Anjungan\antrianFarmasiRepository;

class antrianFarmasiService
{
    protected $antrianFarmasiRepository;

    public function __construct(antrianFarmasiRepository $antrianFarmasiRepository)
    {
        $this->antrianFarmasiRepository = $antrianFarmasiRepository;
    }

    public function generateAntrianFarmasi($no_rawat)
    {
        return $this->antrianFarmasiRepository->generateAntrianFarmasi($no_rawat);
    }

    public function generateDataFarmasi($nomor){
        return $this->antrianFarmasiRepository->generateDataFarmasi($nomor);
    }

}
