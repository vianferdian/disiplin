<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Services\BankDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelanggaranSiswaController extends Controller
{
    protected BankDataService $bankDataService;

    public function __construct(BankDataService $bankDataService)
    {
        $this->bankDataService = $bankDataService;
    }

    public function index(Request $request)
    {
        $query = PelanggaranSiswa::with(['jenisPelanggaran.kategori', 'pencatat']);

        if ($request->filled('kelas')) {
            $query->where('siswa_kelas', $request->kelas);
        }

        if ($request->filled('tingkat')) {
            $tingkat = $request->tingkat;
            $query->where('siswa_kelas', 'like', "{$tingkat} %");
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pelanggaran', $request->tanggal);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('siswa_nama', 'like', "%{$search}%")
                  ->orWhere('siswa_nisn', 'like', "%{$search}%");
            });
        }

        $pelanggaranList = $query->orderBy('tanggal_pelanggaran', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $kelasListGrouped = $this->bankDataService->getKelasGrouped();

        return view('pelanggaran_siswa.index', compact('pelanggaranList', 'kelasListGrouped'));
    }

    public function create()
    {
        $kelasListGrouped = $this->bankDataService->getKelasGrouped();
        $jenisPelanggaranList = JenisPelanggaran::with('kategori')->get();

        // Map Daily Uniform Checklist Items by Code Prefix
        $uniformItemsByDay = [
            'Senin' => [
                'baju' => 'Baju PDU Putih-Putih',
                'items' => JenisPelanggaran::where('kode_pelanggaran', 'like', 'SER-SEN-%')->get(),
            ],
            'Selasa' => [
                'baju' => 'Baju PSAS',
                'items' => JenisPelanggaran::where('kode_pelanggaran', 'like', 'SER-SEL-%')->get(),
            ],
            'Rabu' => [
                'baju' => 'Baju Pramuka',
                'items' => JenisPelanggaran::where('kode_pelanggaran', 'like', 'SER-RAB-%')->get(),
            ],
            'Kamis' => [
                'baju' => 'Baju Batik SMK',
                'items' => JenisPelanggaran::where('kode_pelanggaran', 'like', 'SER-KAM-%')->get(),
            ],
            'Jumat' => [
                'baju' => 'Baju Muslim SMK',
                'items' => JenisPelanggaran::where('kode_pelanggaran', 'like', 'SER-JUM-%')->get(),
            ],
        ];

        // Determine Today's Day Name in Indonesian
        $dayIndex = date('N'); // 1 = Monday, 5 = Friday
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Senin', 7 => 'Senin'];
        $todayName = $dayNames[$dayIndex] ?? 'Senin';

        return view('pelanggaran_siswa.create', compact(
            'kelasListGrouped',
            'jenisPelanggaranList',
            'uniformItemsByDay',
            'todayName'
        ));
    }

    public function store(Request $request)
    {
        $mode = $request->input('input_mode', 'standard');

        if ($mode === 'uniform_checklist') {
            $validated = $request->validate([
                'siswa_nisn' => 'required|string',
                'siswa_nama' => 'required|string',
                'siswa_kelas' => 'required|string',
                'tanggal_pelanggaran' => 'nullable|date',
                'waktu_pelanggaran' => 'nullable',
                'uniform_items' => 'required|array|min:1',
                'uniform_items.*' => 'exists:jenis_pelanggarans,id',
                'catatan_keterangan' => 'nullable|string',
            ]);

            $tgl = $validated['tanggal_pelanggaran'] ?? now()->toDateString();
            $wkt = $validated['waktu_pelanggaran'] ?? now()->format('H:i:s');

            $jenisList = JenisPelanggaran::whereIn('id', $validated['uniform_items'])->get();
            $totalPoin = $jenisList->sum('poin');
            $itemCount = $jenisList->count();
            $primaryJenis = $jenisList->first();

            if ($itemCount > 1) {
                $catatanCombined = "Tidak Sesuai Ketentuan Seragam ({$itemCount} Item)";
            } else {
                $catatanCombined = $primaryJenis->nama_pelanggaran;
            }

            if (!empty($validated['catatan_keterangan'])) {
                $catatanCombined .= " | Catatan: " . $validated['catatan_keterangan'];
            }

            // Simpan sebagai 1 Record Gabungan
            PelanggaranSiswa::create([
                'siswa_nisn' => $validated['siswa_nisn'],
                'siswa_nama' => $validated['siswa_nama'],
                'siswa_kelas' => $validated['siswa_kelas'],
                'jenis_pelanggaran_id' => $primaryJenis->id,
                'poin_pelanggaran' => $totalPoin,
                'tanggal_pelanggaran' => $tgl,
                'waktu_pelanggaran' => $wkt,
                'catatan_keterangan' => $catatanCombined,
                'pencatat_user_id' => Auth::id(),
            ]);

            $this->consolidateDuplicates();

            return redirect()->route('pelanggaran-siswa.index')
                ->with('success', "Berhasil menyimpan 1 catatan pelanggaran seragam ({$itemCount} item, total +{$totalPoin} Poin) untuk {$validated['siswa_nama']}!");
        }

        // Standard Mode
        $validated = $request->validate([
            'siswa_nisn' => 'required|string',
            'siswa_nama' => 'required|string',
            'siswa_kelas' => 'required|string',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'tanggal_pelanggaran' => 'nullable|date',
            'waktu_pelanggaran' => 'nullable',
            'catatan_keterangan' => 'nullable|string',
        ]);

        $jenis = JenisPelanggaran::findOrFail($validated['jenis_pelanggaran_id']);
        $tgl = $validated['tanggal_pelanggaran'] ?? now()->toDateString();

        // Cek apakah siswa sudah memiliki catatan pelanggaran pada tanggal yang sama
        $existing = PelanggaranSiswa::where('siswa_nisn', $validated['siswa_nisn'])
            ->whereDate('tanggal_pelanggaran', $tgl)
            ->first();

        if ($existing) {
            // Gabungkan ke record yang sudah ada
            $existing->poin_pelanggaran += $jenis->poin;
            $existing->catatan_keterangan = "Tidak Sesuai Ketentuan Seragam";
            if (!empty($validated['catatan_keterangan'])) {
                $existing->catatan_keterangan .= " | " . $validated['catatan_keterangan'];
            }
            $existing->save();
        } else {
            PelanggaranSiswa::create([
                'siswa_nisn' => $validated['siswa_nisn'],
                'siswa_nama' => $validated['siswa_nama'],
                'siswa_kelas' => $validated['siswa_kelas'],
                'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
                'poin_pelanggaran' => $jenis->poin,
                'tanggal_pelanggaran' => $tgl,
                'waktu_pelanggaran' => $validated['waktu_pelanggaran'] ?? now()->format('H:i:s'),
                'catatan_keterangan' => $validated['catatan_keterangan'],
                'pencatat_user_id' => Auth::id(),
            ]);
        }

        $this->consolidateDuplicates();

        return redirect()->route('pelanggaran-siswa.index')
            ->with('success', "Pencatatan pelanggaran siswa ({$validated['siswa_nama']}) berhasil disimpan!");
    }

    /**
     * Konsolidasi otomatis jika ada beberapa record pelanggaran siswa yang sama pada tanggal yang sama.
     */
    protected function consolidateDuplicates()
    {
        $duplicates = DB::table('pelanggaran_siswas')
            ->select('siswa_nisn', 'tanggal_pelanggaran', DB::raw('COUNT(*) as total'))
            ->groupBy('siswa_nisn', 'tanggal_pelanggaran')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $records = PelanggaranSiswa::where('siswa_nisn', $dup->siswa_nisn)
                ->whereDate('tanggal_pelanggaran', $dup->tanggal_pelanggaran)
                ->with('jenisPelanggaran')
                ->get();

            if ($records->count() <= 1) continue;

            $first = $records->first();
            $totalPoin = $records->sum('poin_pelanggaran');

            $combinedNotes = "Tidak Sesuai Ketentuan Seragam (" . $records->count() . " Item)";
            if ($first->catatan_keterangan && !str_contains($first->catatan_keterangan, 'Tidak Sesuai Ketentuan Seragam')) {
                $combinedNotes .= " | " . $first->catatan_keterangan;
            }

            $first->update([
                'poin_pelanggaran' => $totalPoin,
                'catatan_keterangan' => $combinedNotes,
            ]);

            $otherIds = $records->pluck('id')->reject(fn($id) => $id == $first->id);
            PelanggaranSiswa::whereIn('id', $otherIds)->delete();
        }
    }

    public function show(PelanggaranSiswa $pelanggaranSiswa)
    {
        $pelanggaranSiswa->load(['jenisPelanggaran.kategori', 'pencatat']);

        $totalPoinSiswa = PelanggaranSiswa::where('siswa_nisn', $pelanggaranSiswa->siswa_nisn)->sum('poin_pelanggaran');
        $riwayatSiswa = PelanggaranSiswa::where('siswa_nisn', $pelanggaranSiswa->siswa_nisn)
            ->with('jenisPelanggaran')
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();

        return view('pelanggaran_siswa.show', compact('pelanggaranSiswa', 'totalPoinSiswa', 'riwayatSiswa'));
    }

    public function destroy(PelanggaranSiswa $pelanggaranSiswa)
    {
        $pelanggaranSiswa->delete();

        return redirect()->route('pelanggaran-siswa.index')
            ->with('success', 'Data pelanggaran siswa berhasil dihapus!');
    }

    public function report(Request $request)
    {
        $kelas = $request->query('kelas');
        $tingkat = $request->query('tingkat');

        $query = PelanggaranSiswa::select('siswa_nisn', 'siswa_nama', 'siswa_kelas', DB::raw('SUM(poin_pelanggaran) as total_poin'), DB::raw('COUNT(*) as total_pelanggaran'))
            ->groupBy('siswa_nisn', 'siswa_nama', 'siswa_kelas');

        if ($kelas) {
            $query->where('siswa_kelas', $kelas);
        }

        if ($tingkat) {
            $query->where('siswa_kelas', 'like', "{$tingkat} %");
        }

        $rekapSiswa = $query->orderByDesc('total_poin')->get();
        $kelasListGrouped = $this->bankDataService->getKelasGrouped();

        return view('pelanggaran_siswa.report', compact('rekapSiswa', 'kelasListGrouped', 'kelas', 'tingkat'));
    }

    public function printReport(Request $request)
    {
        $kelas = $request->query('kelas');
        $tingkat = $request->query('tingkat');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $query = PelanggaranSiswa::with(['jenisPelanggaran.kategori', 'pencatat']);

        if ($kelas) {
            $query->where('siswa_kelas', $kelas);
        }

        if ($tingkat) {
            $query->where('siswa_kelas', 'like', "{$tingkat} %");
        }

        if ($tanggalMulai) {
            $query->whereDate('tanggal_pelanggaran', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal_pelanggaran', '<=', $tanggalSelesai);
        }

        $pelanggaranList = $query->orderBy('tanggal_pelanggaran', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelanggaran_siswa.print_pdf', compact('pelanggaranList', 'kelas', 'tingkat', 'tanggalMulai', 'tanggalSelesai'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Rekapitulasi_Pelanggaran_Siswa.pdf');
    }

    public function printSingle(PelanggaranSiswa $pelanggaranSiswa)
    {
        $pelanggaranSiswa->load(['jenisPelanggaran.kategori', 'pencatat']);

        $totalPoinSiswa = PelanggaranSiswa::where('siswa_nisn', $pelanggaranSiswa->siswa_nisn)->sum('poin_pelanggaran');
        $riwayatSiswa = PelanggaranSiswa::where('siswa_nisn', $pelanggaranSiswa->siswa_nisn)
            ->with(['jenisPelanggaran.kategori', 'pencatat'])
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelanggaran_siswa.print_single_pdf', compact('pelanggaranSiswa', 'totalPoinSiswa', 'riwayatSiswa'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Detail_Pelanggaran_{$pelanggaranSiswa->siswa_nama}.pdf");
    }
}
