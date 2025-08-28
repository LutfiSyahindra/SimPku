<?php

namespace App\Services\Anjungan;

use App\Repositories\Anjungan\AdmisiRepository;

class admisiService
{
    protected $admisiRepository;

    public function __construct(AdmisiRepository $admisiRepository)
    {
        $this->admisiRepository = $admisiRepository;
    }

    public function generateNomorAntrian()
    {
        return $this->admisiRepository->generateNomorAntrian();
    }

}
