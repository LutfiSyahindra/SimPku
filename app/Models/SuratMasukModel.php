<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukModel extends Model
{
    Use HasFactory;
    protected $table = 'surat_masuk';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
