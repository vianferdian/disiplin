<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelanggarans';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function jenisPelanggaran()
    {
        return $this->hasMany(JenisPelanggaran::class, 'kategori_id');
    }
}
