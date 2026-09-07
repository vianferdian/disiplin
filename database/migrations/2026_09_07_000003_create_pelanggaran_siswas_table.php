<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_nisn')->index();
            $table->string('siswa_nama');
            $table->string('siswa_kelas');
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->onDelete('cascade');
            $table->integer('poin_pelanggaran');
            $table->date('tanggal_pelanggaran');
            $table->time('waktu_pelanggaran')->nullable();
            $table->text('catatan_keterangan')->nullable();
            $table->foreignId('pencatat_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswas');
    }
};
