<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggaranHariIni = PelanggaranSiswa::whereDate('tanggal_pelanggaran', today())->count();
        $totalPelanggaranBulanIni = PelanggaranSiswa::whereMonth('tanggal_pelanggaran', now()->month)
            ->whereYear('tanggal_pelanggaran', now()->year)
            ->count();
        $totalSiswaTercatat = PelanggaranSiswa::distinct('siswa_nisn')->count('siswa_nisn');
        $totalJenisPelanggaran = JenisPelanggaran::count();
        $totalUserWakasek = User::where('role', 'wakasek')->count();

        // Recent Violations (Latest 5)
        $pelanggaranTerbaru = PelanggaranSiswa::with(['jenisPelanggaran.kategori', 'pencatat'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top 5 Violations Count
        $topJenisPelanggaran = PelanggaranSiswa::select('jenis_pelanggaran_id', DB::raw('count(*) as total'))
            ->groupBy('jenis_pelanggaran_id')
            ->orderByDesc('total')
            ->with('jenisPelanggaran')
            ->take(5)
            ->get();

        // Top 5 Siswa Poin Tertinggi
        $topSiswaPoin = PelanggaranSiswa::select('siswa_nisn', 'siswa_nama', 'siswa_kelas', DB::raw('SUM(poin_pelanggaran) as total_poin'))
            ->groupBy('siswa_nisn', 'siswa_nama', 'siswa_kelas')
            ->orderByDesc('total_poin')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPelanggaranHariIni',
            'totalPelanggaranBulanIni',
            'totalSiswaTercatat',
            'totalJenisPelanggaran',
            'totalUserWakasek',
            'pelanggaranTerbaru',
            'topJenisPelanggaran',
            'topSiswaPoin'
        ));
    }
}
