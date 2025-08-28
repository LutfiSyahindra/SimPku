<?php

namespace App\Services\WaGateway;

use App\Repositories\WaGateway\WaGatewayRepository;

class WaGatewayService
{
    protected $WaGatewayRepository;

    public function __construct(WaGatewayRepository $WaGatewayRepository)
    {
        $this->WaGatewayRepository = $WaGatewayRepository;
    }


    public function getWaTerkirim()
    {
        return $this->WaGatewayRepository->getWaTerkirim();
    }

    public function LogWa($id)
    {
        return $this->WaGatewayRepository->LogWa($id);
    }
}
