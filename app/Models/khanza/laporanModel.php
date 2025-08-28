<?php

namespace App\Models\khanza;

use Illuminate\Database\Eloquent\Model;

class laporanModel extends Model
{
    protected $table = 'laporan_it'; // Nama tabel di database

    protected $fillable = [
        'tanggal',
        'waktu_laporan',
        'tanggap_laporan',
        'nama_barang',
        'ruangan',
        'analisa',
        'tindak_lanjut',
        'waktu_penyelesaian',
    ];

    public $timestamps = true; // Jika kamu menggunakan kolom created_at dan updated_at
}
