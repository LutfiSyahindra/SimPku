<?php

namespace App\Repositories\TaskId;

use App\Models\khanza\LogWaModel;
use App\Models\khanza\TaskIdModel;
use App\Models\khanza\wa_gatewayModel;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class TaskIdRepository
{
    public function getTaskId()
    {
        return TaskIdModel::query();
    }

    public function getLogTaskId($id)
    {
        return TaskIdModel::where('id', $id)->get();
    }
    public function getLogTaskIdRw($no_rawat)
    {
        return TaskIdModel::where('no_rawat', $no_rawat)
        ->orderBy('task_id', 'asc') // urut dari kecil ke besar
        ->get();
    }
    

}
