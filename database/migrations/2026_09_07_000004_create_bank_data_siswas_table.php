<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_data_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable()->unique();
            $table->string('nisn')->index();
            $table->string('nis')->nullable();
            $table->string('nama');
            $table->string('gender', 10)->nullable();
            $table->string('kelas')->index();
            $table->string('tingkat', 10)->index(); // X, XI, XII
            $table->string('jurusan')->nullable();
            $table->string('status')->default('Aktif');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_data_siswas');
    }
};
