<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;

class wa_gatewayModel extends Model
{
    protected $connection = 'mysql_khanza';
    protected $table = 'wa_gateway';
}
