<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admisiModel extends Model
{
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'anjungan_admisi';

    // Tentukan primary key jika berbeda dari 'id'
    protected $primaryKey = 'id';

    // Jika tabel tidak memiliki kolom created_at dan updated_at
    public $timestamps = false;

    // Izinkan mass assignment untuk kolom-kolom berikut
    protected $fillable = [
        'no_antrian',
        'tanggal',
    ];
}
