<?php

namespace App\Services\AntriKasirKhanza;

use App\Repositories\AntriKasirKhanza\AntriKasirKhanzaRepository;

class AntriKasirKhanzaService
{
    protected $AntriKasirKhanzaRepository;

    public function __construct(AntriKasirKhanzaRepository $AntriKasirKhanzaRepository)
    {
        $this->AntriKasirKhanzaRepository = $AntriKasirKhanzaRepository;
    }

    public function getAntriKasir()
    {
        return $this->AntriKasirKhanzaRepository->getAntriKasir();
    }

    public function updateAntriKasir($panggil)
    {
        return $this->AntriKasirKhanzaRepository->updateAntriKasir($panggil);
    }

}
