<?php

namespace App\Services\It;

use App\Repositories\It\ItRepository;

class ItService
{
    protected $itRepository;

    public function __construct(ItRepository $itRepository)
    {
        $this->itRepository = $itRepository;
    }

    public function getAllLaporan()
    {
        return $this->itRepository->getLaporan();
    }

    public function createLaporan(array $data)
    {
        return $this->itRepository->createLaporan($data);
    }

    public function findLaporan($id)
    {
        return $this->itRepository->findLaporan($id);
    }

    public function countLaporan()
    {
        return $this->itRepository->countLaporan();
    }

    public function forWidget()
    {
        return $this->itRepository->forWidget();
    }

}
