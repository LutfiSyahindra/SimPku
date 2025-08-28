<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;

class antri_kasirModel extends Model
{
    protected $connection = 'mysql_khanza';
    protected $table = 'antri_kasir';
    public $timestamps = false; // Matikan timestamps
}
