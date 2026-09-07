<?php

namespace App\Http\Controllers;

use App\Models\BankDataSiswa;
use App\Services\BankDataService;
use Illuminate\Http\Request;

class BankDataSyncController extends Controller
{
    protected BankDataService $bankDataService;

    public function __construct(BankDataService $bankDataService)
    {
        $this->bankDataService = $bankDataService;
    }

    public function index()
    {
        $totalSiswaLocal = BankDataSiswa::count();
        $totalKelasLocal = BankDataSiswa::select('kelas')->distinct()->count();
        $rawLastSynced = BankDataSiswa::max('last_synced_at');
        $lastSyncedAt = $rawLastSynced ? \Carbon\Carbon::parse($rawLastSynced) : null;

        $tingkatCounts = [
            'X' => BankDataSiswa::where('tingkat', 'X')->count(),
            'XI' => BankDataSiswa::where('tingkat', 'XI')->count(),
            'XII' => BankDataSiswa::where('tingkat', 'XII')->count(),
        ];

        $testResult = $this->bankDataService->testConnection();

        return view('bank_data.index', compact(
            'totalSiswaLocal',
            'totalKelasLocal',
            'lastSyncedAt',
            'tingkatCounts',
            'testResult'
        ));
    }

    public function testConnection()
    {
        return response()->json($this->bankDataService->testConnection());
    }

    public function syncNow()
    {
        $result = $this->bankDataService->syncAllStudentsFromApi();
        return response()->json($result);
    }
}
