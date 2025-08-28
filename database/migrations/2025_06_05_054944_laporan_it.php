<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_it', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu_laporan');
            $table->time('tanggap_laporan')->nullable();
            $table->string('nama_barang');
            $table->string('ruangan');
            $table->text('analisa')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->time('waktu_penyelesaian')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
