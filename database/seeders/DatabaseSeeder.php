<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Kesiswaan',
                'email' => 'admin@disiplin.sch.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $wakasek = User::firstOrCreate(
            ['username' => 'wakasek'],
            [
                'name' => 'Drs. H. Mulyadi, M.Pd (Wakasek Kesiswaan)',
                'email' => 'wakasek@disiplin.sch.id',
                'password' => Hash::make('password'),
                'role' => 'wakasek',
            ]
        );

        // 2. Create Categories & Violation Types
        $katKedisiplinan = KategoriPelanggaran::firstOrCreate(
            ['nama_kategori' => 'Kedisiplinan & Kehadiran'],
            ['deskripsi' => 'Pelanggaran terkait ketertiban waktu, kehadiran, dan kedisiplinan sekolah.']
        );

        $katKerapian = KategoriPelanggaran::firstOrCreate(
            ['nama_kategori' => 'Kerapian & Seragam'],
            ['deskripsi' => 'Pelanggaran terkait tata cara berpakaian, rambut, dan atribut sekolah.']
        );

        $katPerilaku = KategoriPelanggaran::firstOrCreate(
            ['nama_kategori' => 'Perilaku & Etika'],
            ['deskripsi' => 'Pelanggaran terhadap tata krama, kesopanan, dan etika siswa.']
        );

        $katBerat = KategoriPelanggaran::firstOrCreate(
            ['nama_kategori' => 'Pelanggaran Berat'],
            ['deskripsi' => 'Pelanggaran tingkat tinggi yang berpotensi merusak nama baik sekolah/tindak pidana.']
        );

        // Standard Jenis Pelanggaran Data
        $jenis1 = JenisPelanggaran::firstOrCreate(
            ['kode_pelanggaran' => 'DIS-01'],
            [
                'kategori_id' => $katKedisiplinan->id,
                'nama_pelanggaran' => 'Terlambat Masuk Sekolah (>15 Menit)',
                'poin' => 5,
                'sanksi_default' => 'Teguran lisan & pembersihan area sekolah',
                'deskripsi' => 'Datang ke sekolah lewat dari jam 07.15 WIB.',
            ]
        );

        $jenis2 = JenisPelanggaran::firstOrCreate(
            ['kode_pelanggaran' => 'DIS-02'],
            [
                'kategori_id' => $katKedisiplinan->id,
                'nama_pelanggaran' => 'Meninggalkan Kelas Tanpa Izin (Membolos)',
                'poin' => 15,
                'sanksi_default' => 'Surat Peringatan I & Tugas tambahan dari Wali Kelas',
                'deskripsi' => 'Tidak berada di kelas saat jam pelajaran tanpa surat izin.',
            ]
        );

        // --- ATRIBUT SERAGAM HARIAN (SENIN - JUMAT) ---
        $uniformItems = [
            // SENIN: Baju PDU Putih-Putih
            ['kode' => 'SER-SEN-01', 'nama' => 'Senin (PDU): Rambut Tidak Sesuai Ketentuan', 'poin' => 10],
            ['kode' => 'SER-SEN-02', 'nama' => 'Senin (PDU): Tidak Memakai Topi & Pin Topi', 'poin' => 5],
            ['kode' => 'SER-SEN-03', 'nama' => 'Senin (PDU): Tidak Memakai Dasi Sekolah', 'poin' => 5],
            ['kode' => 'SER-SEN-04', 'nama' => 'Senin (PDU): Tidak Memakai Name Tag', 'poin' => 5],
            ['kode' => 'SER-SEN-05', 'nama' => 'Senin (PDU): Tidak Memakai Balok', 'poin' => 5],
            ['kode' => 'SER-SEN-06', 'nama' => 'Senin (PDU): Tidak Memakai Pangkat BET', 'poin' => 5],
            ['kode' => 'SER-SEN-07', 'nama' => 'Senin (PDU): Tidak Memakai Sabuk Sekolah', 'poin' => 5],
            ['kode' => 'SER-SEN-08', 'nama' => 'Senin (PDU): Tidak Memakai Sepatu Pantopel Hitam', 'poin' => 5],

            // SELASA: Baju PSAS
            ['kode' => 'SER-SEL-01', 'nama' => 'Selasa (PSAS): Rambut Tidak Sesuai Ketentuan', 'poin' => 10],
            ['kode' => 'SER-SEL-02', 'nama' => 'Selasa (PSAS): Tidak Memakai BET Bendera', 'poin' => 5],
            ['kode' => 'SER-SEL-03', 'nama' => 'Selasa (PSAS): Tidak Memakai BET Logo & Lokasi SMK', 'poin' => 5],
            ['kode' => 'SER-SEL-04', 'nama' => 'Selasa (PSAS): Tidak Memakai BET Nama', 'poin' => 5],
            ['kode' => 'SER-SEL-05', 'nama' => 'Selasa (PSAS): Tidak Memakai Sabuk Sekolah', 'poin' => 5],
            ['kode' => 'SER-SEL-06', 'nama' => 'Selasa (PSAS): Tidak Memakai Sepatu Warrior', 'poin' => 5],

            // RABU: Baju Pramuka
            ['kode' => 'SER-RAB-01', 'nama' => 'Rabu (Pramuka): Rambut Tidak Sesuai Ketentuan', 'poin' => 10],
            ['kode' => 'SER-RAB-02', 'nama' => 'Rabu (Pramuka): Tidak Memakai Kacu (Ukuran L/XL)', 'poin' => 5],
            ['kode' => 'SER-RAB-03', 'nama' => 'Rabu (Pramuka): Tidak Memakai Ring Kacu / Ring Tambang', 'poin' => 5],
            ['kode' => 'SER-RAB-04', 'nama' => 'Rabu (Pramuka): Tidak Memakai Sabuk Sekolah', 'poin' => 5],
            ['kode' => 'SER-RAB-05', 'nama' => 'Rabu (Pramuka): Tidak Memakai Sepatu Pantopel', 'poin' => 5],

            // KAMIS: Baju Batik
            ['kode' => 'SER-KAM-01', 'nama' => 'Kamis (Batik): Rambut Tidak Sesuai Ketentuan', 'poin' => 10],
            ['kode' => 'SER-KAM-02', 'nama' => 'Kamis (Batik): Tidak Memakai Baju Batik SMK', 'poin' => 10],
            ['kode' => 'SER-KAM-03', 'nama' => 'Kamis (Batik): Celana / Rok Tidak Berbahan Hitam', 'poin' => 10],
            ['kode' => 'SER-KAM-04', 'nama' => 'Kamis (Batik): Tidak Memakai Sabuk Sekolah', 'poin' => 5],
            ['kode' => 'SER-KAM-05', 'nama' => 'Kamis (Batik): Tidak Memakai Sepatu Warrior', 'poin' => 5],

            // JUMAT: Baju Muslim
            ['kode' => 'SER-JUM-01', 'nama' => 'Jumat (Muslim): Rambut Tidak Sesuai Ketentuan', 'poin' => 10],
            ['kode' => 'SER-JUM-02', 'nama' => 'Jumat (Muslim): Tidak Memakai Baju Muslim SMK', 'poin' => 10],
            ['kode' => 'SER-JUM-03', 'nama' => 'Jumat (Muslim): Bawahan Tidak Berwarna Coklat', 'poin' => 10],
            ['kode' => 'SER-JUM-04', 'nama' => 'Jumat (Muslim): Tidak Memakai Sepatu Warrior', 'poin' => 5],
        ];

        foreach ($uniformItems as $u) {
            JenisPelanggaran::firstOrCreate(
                ['kode_pelanggaran' => $u['kode']],
                [
                    'kategori_id' => $katKerapian->id,
                    'nama_pelanggaran' => $u['nama'],
                    'poin' => $u['poin'],
                    'sanksi_default' => 'Teguran lisan & penertiban atribut seragam',
                    'deskripsi' => 'Pelanggaran ketentuan seragam harian sekolah.',
                ]
            );
        }

        $jenis5 = JenisPelanggaran::firstOrCreate(
            ['kode_pelanggaran' => 'ETK-01'],
            [
                'kategori_id' => $katPerilaku->id,
                'nama_pelanggaran' => 'Menggunakan HP Saat Jam Pelajaran Tanpa Izin Guru',
                'poin' => 10,
                'sanksi_default' => 'HP disita dan diambil oleh Orang Tua',
                'deskripsi' => 'Bermain game / sosmed saat guru menerangkan.',
            ]
        );

        $jenis6 = JenisPelanggaran::firstOrCreate(
            ['kode_pelanggaran' => 'BRT-01'],
            [
                'kategori_id' => $katBerat->id,
                'nama_pelanggaran' => 'Merokok / Vaping di Lingkungan Sekolah',
                'poin' => 50,
                'sanksi_default' => 'Surat Peringatan II & Pemanggilan Orang Tua',
                'deskripsi' => 'Kedapatan membawa/menggunakan rokok atau vape.',
            ]
        );

        // Sample Violations if table empty
        if (PelanggaranSiswa::count() == 0) {
            PelanggaranSiswa::create([
                'siswa_nisn' => '0114834603',
                'siswa_nama' => 'MUHAMMAD RESTU PRATAMA',
                'siswa_kelas' => 'X DPIB 1',
                'jenis_pelanggaran_id' => $jenis1->id,
                'poin_pelanggaran' => 5,
                'tanggal_pelanggaran' => now()->subDays(2),
                'waktu_pelanggaran' => '07:25:00',
                'catatan_keterangan' => 'Ban sepeda motor bocor di jalan.',
                'pencatat_user_id' => $wakasek->id,
            ]);

            PelanggaranSiswa::create([
                'siswa_nisn' => '0114834603',
                'siswa_nama' => 'MUHAMMAD RESTU PRATAMA',
                'siswa_kelas' => 'X DPIB 1',
                'jenis_pelanggaran_id' => JenisPelanggaran::where('kode_pelanggaran', 'SER-SEN-03')->first()->id,
                'poin_pelanggaran' => 5,
                'tanggal_pelanggaran' => now()->subDays(1),
                'waktu_pelanggaran' => '07:40:00',
                'catatan_keterangan' => 'Lupa membawa dasi PDU.',
                'pencatat_user_id' => $wakasek->id,
            ]);
        }
    }
}
