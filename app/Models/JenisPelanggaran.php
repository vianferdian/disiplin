<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pelanggarans';

    protected $fillable = [
        'kategori_id',
        'kode_pelanggaran',
        'nama_pelanggaran',
        'poin',
        'sanksi_default',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriPelanggaran::class, 'kategori_id');
    }

    public function pelanggaranSiswa()
    {
        return $this->hasMany(PelanggaranSiswa::class, 'jenis_pelanggaran_id');
    }
}
