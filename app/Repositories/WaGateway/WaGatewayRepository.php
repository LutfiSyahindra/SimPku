<?php

namespace App\Repositories\WaGateway;

use App\Models\khanza\LogWaModel;
use App\Models\khanza\wa_gatewayModel;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class WaGatewayRepository
{
    public function getWaTerkirim()
    {
        return wa_gatewayModel::query();
    }

    public function LogWa($id)
    {
        return LogWaModel::where('wa_gateway_id', $id)->get();
    }
    

}
