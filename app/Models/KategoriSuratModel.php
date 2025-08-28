<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSuratModel extends Model
{
    Use HasFactory;
    protected $table = 'kategori_surat';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
