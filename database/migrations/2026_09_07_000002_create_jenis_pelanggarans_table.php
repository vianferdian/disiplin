<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_pelanggarans')->onDelete('cascade');
            $table->string('kode_pelanggaran')->unique()->nullable();
            $table->string('nama_pelanggaran');
            $table->integer('poin')->default(10);
            $table->text('sanksi_default')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_pelanggarans');
    }
};
