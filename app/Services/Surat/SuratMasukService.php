<?php

namespace App\Services\Surat;

use App\Repositories\Surat\SuratMasukRepository;

class SuratMasukService
{
    protected $SuratMasukRepository;

    public function __construct(SuratMasukRepository $SuratMasukRepository)
    {
        $this->SuratMasukRepository = $SuratMasukRepository;
    }

    public function getSuratMasuk()
    {
        return $this->SuratMasukRepository->getSuratMasuk();
    }

    public function storeSuratMasuk(array $data)
    {
        return $this->SuratMasukRepository->storeSuratMasuk($data);
    }

    public function findSuratMasuk($id)
    {
        return $this->SuratMasukRepository->findSuratMasuk($id);
    }

}
