<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;

class TaskIdModel extends Model
{
    protected $connection = 'mysql_khanza';
    protected $table = 'log_taskid';
}
