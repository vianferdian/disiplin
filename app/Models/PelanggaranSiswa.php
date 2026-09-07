<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran_siswas';

    protected $fillable = [
        'siswa_nisn',
        'siswa_nama',
        'siswa_kelas',
        'jenis_pelanggaran_id',
        'poin_pelanggaran',
        'tanggal_pelanggaran',
        'waktu_pelanggaran',
        'catatan_keterangan',
        'pencatat_user_id',
    ];

    protected $casts = [
        'tanggal_pelanggaran' => 'date',
    ];

    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'pencatat_user_id');
    }

    public function getNamaPelanggaranFormattedAttribute(): string
    {
        if ($this->catatan_keterangan && (
            str_contains($this->catatan_keterangan, 'Tidak Sesuai Ketentuan Seragam') ||
            str_contains($this->catatan_keterangan, 'Pelanggaran Seragam (') ||
            str_contains($this->catatan_keterangan, 'Gabungan Pelanggaran (')
        )) {
            return 'Tidak Sesuai Ketentuan Seragam';
        }

        return $this->jenisPelanggaran->nama_pelanggaran ?? '-';
    }

    public function getRincianItemsAttribute(): array
    {
        if (!$this->catatan_keterangan) {
            return [];
        }

        if (str_contains($this->catatan_keterangan, ':')) {
            $parts = explode(':', $this->catatan_keterangan, 2);
            $itemsPart = trim($parts[1]);
            if (str_contains($itemsPart, '| Catatan:')) {
                $itemsPart = trim(explode('| Catatan:', $itemsPart)[0]);
            }
            $items = array_map('trim', explode(',', $itemsPart));
            return array_values(array_filter($items));
        }

        return [];
    }
}
