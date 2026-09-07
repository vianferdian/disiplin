<?php

namespace App\Http\Controllers;

use App\Services\BankDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankDataProxyController extends Controller
{
    protected BankDataService $bankDataService;

    public function __construct(BankDataService $bankDataService)
    {
        $this->bankDataService = $bankDataService;
    }

    public function getKelas(): JsonResponse
    {
        $kelas = $this->bankDataService->getKelas();
        return response()->json([
            'success' => true,
            'data' => $kelas,
        ]);
    }

    public function getSiswa(Request $request): JsonResponse
    {
        $kelas = $request->query('kelas');
        $search = $request->query('q');

        $siswa = $this->bankDataService->getSiswa($kelas, $search);

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ]);
    }
}
