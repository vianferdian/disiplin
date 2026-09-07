<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDataSiswa extends Model
{
    use HasFactory;

    protected $table = 'bank_data_siswas';

    protected $fillable = [
        'uuid',
        'nisn',
        'nis',
        'nama',
        'gender',
        'kelas',
        'tingkat',
        'jurusan',
        'status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];
}
