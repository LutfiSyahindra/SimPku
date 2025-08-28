<?php

namespace App\Services\TaskId;

use App\Repositories\TaskId\TaskIdRepository;

class TaskIdService
{
    protected $TaskIdRepository;

    public function __construct(TaskIdRepository $TaskIdRepository)
    {
        $this->TaskIdRepository = $TaskIdRepository;
    }


    public function getTaskId()
    {
        return $this->TaskIdRepository->getTaskId();
    }

    public function getLogTaskId($id)
    {
        return $this->TaskIdRepository->getLogTaskId($id);
    }

    public function getLogTaskIdRw($no_rawat)
    {
        return $this->TaskIdRepository->getLogTaskIdRw($no_rawat);
    }
}
