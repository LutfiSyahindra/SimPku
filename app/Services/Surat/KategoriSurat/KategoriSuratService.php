<?php

namespace App\Services\Surat\KategoriSurat;

use App\Repositories\Surat\KategoriSurat\KategoriSuratRepository;

class KategoriSuratService
{
    protected $KategoriSuratRepository;

    public function __construct(KategoriSuratRepository $KategoriSuratRepository)
    {
        $this->KategoriSuratRepository = $KategoriSuratRepository;
    }

    public function getKategoriSurat()
    {
        return $this->KategoriSuratRepository->getKategoriSurat();
    }
    public function storeKategoriSurat(array $data)
    {
        return $this->KategoriSuratRepository->storeKategoriSurat($data);
    }

    public function findKategoriSurat($id)
    {
        return $this->KategoriSuratRepository->findKategoriSurat($id);
    }

    public function updateKategoriSurat(array $data, $id)
    {
        return $this->KategoriSuratRepository->updateKategoriSurat($data, $id);
    }

}
