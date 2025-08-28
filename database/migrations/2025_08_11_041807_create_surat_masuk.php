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
        Schema::create('surat_masuk', function (Blueprint $table) {
           $table->id(); // bigint primary key auto increment
            $table->string('no_agenda', 50)->unique()->comment('Nomor agenda internal');
            $table->string('no_surat', 100)->comment('Nomor surat resmi');
            $table->date('tgl_surat')->comment('Tanggal surat');
            $table->date('tgl_terima')->comment('Tanggal diterima');
            $table->string('pengirim', 255)->comment('Pengirim surat');
            $table->text('perihal')->comment('Judul / perihal');
            $table->integer('lampiran')->default(0)->comment('Jumlah lampiran');
            $table->enum('prioritas', ['biasa', 'penting', 'rahasia'])->default('biasa')->comment('Prioritas surat');
            $table->enum('sifat', ['segera', 'sangat segera', 'biasa'])->default('biasa')->comment('sifat surat');
            $table->string('file');            
            $table->string('file_mime')->nullable(); // Simpan MIME type (pdf, docx, jpg, dll)
            $table->enum('status', ['baru', 'proses', 'selesai', 'arsip'])->default('baru')->comment('Status surat');
            $table->unsignedBigInteger('created_by')->comment('User ID SDI');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->comment('User ID SDI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
