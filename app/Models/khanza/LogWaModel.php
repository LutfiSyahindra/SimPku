<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;

class LogWaModel extends Model
{
    protected $connection = 'mysql_khanza';
    protected $table = 'log_wa_gateway';
}
