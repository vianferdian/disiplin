<?php

namespace App\Services;

use App\Models\BankDataSiswa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankDataService
{
    protected string $apiUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected bool $mockMode;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('bankdata.api_url', 'https://onedata.nepertech.id/api/v1');
        $this->clientId = config('bankdata.client_id', 'DISIPLIN_GTQ1');
        $this->clientSecret = config('bankdata.client_secret', 'qiQdPAoF5jg0iAWPuW3nWWZpffmGJVio');
        $this->mockMode = config('bankdata.mock_mode', false);
        $this->timeout = config('bankdata.timeout', 15);
    }

    /**
     * Test connection to OneData API
     */
    public function testConnection(): array
    {
        if ($this->mockMode) {
            return [
                'success' => true,
                'message' => 'Sistem berjalan dalam mode Mock Data.',
                'http_code' => 200,
            ];
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'X-Client-ID' => $this->clientId,
                'X-Client-Secret' => $this->clientSecret,
                'Accept' => 'application/json',
            ])->timeout($this->timeout)->get($this->apiUrl . '/students', ['per_page' => 1]);

            if ($response->successful()) {
                $json = $response->json();
                $total = $json['meta']['total'] ?? count($json['data'] ?? []);
                return [
                    'success' => true,
                    'message' => "Terhubung ke OneData API! Total {$total} data siswa terdeteksi.",
                    'http_code' => $response->status(),
                    'total_api' => $total,
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal terhubung. HTTP ' . $response->status() . ': ' . $response->body(),
                'http_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('BankDataService testConnection error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Koneksi error: ' . $e->getMessage(),
                'http_code' => 500,
            ];
        }
    }

    /**
     * Synchronize all students from OneData API into local DB table
     */
    public function syncAllStudentsFromApi(): array
    {
        if ($this->mockMode) {
            return ['success' => true, 'count' => 0, 'message' => 'Mode Mock aktif.'];
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        try {
            $page = 1;
            $lastPage = 1;
            $syncedCount = 0;

            do {
                $response = Http::withoutVerifying()->withHeaders([
                    'X-Client-ID' => $this->clientId,
                    'X-Client-Secret' => $this->clientSecret,
                    'Accept' => 'application/json',
                ])->timeout($this->timeout)->get($this->apiUrl . '/students', [
                    'page' => $page,
                    'per_page' => 100,
                ]);

                if (!$response->successful()) {
                    Log::warning("BankDataSync failed at page {$page} with status " . $response->status());
                    break;
                }

                $json = $response->json();
                $students = $json['data'] ?? [];
                $lastPage = $json['meta']['last_page'] ?? $page;

                if (!empty($students)) {
                    \Illuminate\Support\Facades\DB::transaction(function () use ($students, &$syncedCount) {
                        foreach ($students as $item) {
                            if (empty($item['nisn'])) continue;

                            $namaKelas = $item['class']['name'] ?? 'Unassigned';
                            $jurusan = $item['class']['major'] ?? '';

                            // Determine Tingkat (X, XI, XII)
                            $tingkat = 'Lainnya';
                            if (preg_match('/^XII\b/i', $namaKelas)) {
                                $tingkat = 'XII';
                            } elseif (preg_match('/^XI\b/i', $namaKelas)) {
                                $tingkat = 'XI';
                            } elseif (preg_match('/^X\b/i', $namaKelas)) {
                                $tingkat = 'X';
                            }

                            BankDataSiswa::updateOrCreate(
                                ['nisn' => $item['nisn']],
                                [
                                    'uuid' => $item['uuid'] ?? null,
                                    'nis' => $item['nis'] ?? null,
                                    'nama' => $item['full_name'] ?? 'Tanpa Nama',
                                    'gender' => $item['gender'] ?? 'L',
                                    'kelas' => $namaKelas,
                                    'tingkat' => $tingkat,
                                    'jurusan' => $jurusan,
                                    'status' => $item['status'] ?? 'Aktif',
                                    'last_synced_at' => now(),
                                ]
                            );

                            $syncedCount++;
                        }
                    });
                }

                $page++;
            } while ($page <= $lastPage);

            return [
                'success' => true,
                'count' => $syncedCount,
                'message' => "Berhasil menyinkronkan {$syncedCount} siswa dari OneData API!",
            ];
        } catch (\Exception $e) {
            Log::error('BankDataService syncAllStudentsFromApi error: ' . $e->getMessage());
            return [
                'success' => false,
                'count' => 0,
                'message' => 'Gagal sync: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get list of classes grouped by level (Tingkat X, XI, XII)
     */
    public function getKelasGrouped(): array
    {
        // First check local DB if populated
        if (BankDataSiswa::count() > 0) {
            $classes = BankDataSiswa::select('kelas', 'tingkat')
                ->distinct()
                ->orderBy('kelas')
                ->get();

            $grouped = [
                'X' => [],
                'XI' => [],
                'XII' => [],
                'Lainnya' => [],
            ];

            foreach ($classes as $c) {
                $t = $c->tingkat ?? 'Lainnya';
                if (!isset($grouped[$t])) {
                    $grouped[$t] = [];
                }
                $grouped[$t][] = $c->kelas;
            }

            return $grouped;
        }

        // Fallback to Live API or Mock
        $allKelas = $this->getKelasFlat();
        $grouped = ['X' => [], 'XI' => [], 'XII' => [], 'Lainnya' => []];

        foreach ($allKelas as $k) {
            $nama = is_array($k) ? ($k['nama_kelas'] ?? $k['name'] ?? '') : $k;
            if (preg_match('/^XII\b/i', $nama)) {
                $grouped['XII'][] = $nama;
            } elseif (preg_match('/^XI\b/i', $nama)) {
                $grouped['XI'][] = $nama;
            } elseif (preg_match('/^X\b/i', $nama)) {
                $grouped['X'][] = $nama;
            } else {
                $grouped['Lainnya'][] = $nama;
            }
        }

        return $grouped;
    }

    /**
     * Get flat list of classes
     */
    public function getKelasFlat(): array
    {
        if (BankDataSiswa::count() > 0) {
            return BankDataSiswa::select('kelas as nama_kelas')
                ->distinct()
                ->orderBy('kelas')
                ->get()
                ->toArray();
        }

        if ($this->mockMode) {
            return $this->getMockKelas();
        }

        // Fetch sample page from API to get classes
        try {
            $response = Http::withHeaders([
                'X-Client-ID' => $this->clientId,
                'X-Client-Secret' => $this->clientSecret,
                'Accept' => 'application/json',
            ])->timeout($this->timeout)->get($this->apiUrl . '/students', ['per_page' => 100]);

            if ($response->successful()) {
                $students = $response->json()['data'] ?? [];
                $classes = [];
                foreach ($students as $s) {
                    if (isset($s['class']['name']) && !in_array($s['class']['name'], $classes)) {
                        $classes[] = $s['class']['name'];
                    }
                }
                sort($classes);
                return array_map(fn($c) => ['nama_kelas' => $c], $classes);
            }
        } catch (\Exception $e) {
            Log::error('BankDataService getKelasFlat error: ' . $e->getMessage());
        }

        return $this->getMockKelas();
    }

    public function getKelas(): array
    {
        return $this->getKelasFlat();
    }

    /**
     * Get students by class or search query
     */
    public function getSiswa(?string $kelas = null, ?string $search = null): array
    {
        // Use local DB if synced
        if (BankDataSiswa::count() > 0) {
            $query = BankDataSiswa::query();

            if (!empty($kelas)) {
                $query->where('kelas', $kelas);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('nama')
                ->get(['nisn', 'nis', 'nama', 'kelas', 'gender as jenis_kelamin'])
                ->map(function ($s) {
                    $nisValue = !empty($s->nis) ? $s->nis : $s->nisn;
                    return [
                        'nisn' => $nisValue,
                        'nis' => $nisValue,
                        'nama' => $s->nama,
                        'kelas' => $s->kelas,
                        'jenis_kelamin' => $s->jenis_kelamin,
                    ];
                })
                ->toArray();
        }

        if ($this->mockMode) {
            return $this->getMockSiswa($kelas, $search);
        }

        // Direct API call fallback
        try {
            $response = Http::withHeaders([
                'X-Client-ID' => $this->clientId,
                'X-Client-Secret' => $this->clientSecret,
                'Accept' => 'application/json',
            ])->timeout($this->timeout)->get($this->apiUrl . '/students', ['per_page' => 100]);

            if ($response->successful()) {
                $students = $response->json()['data'] ?? [];
                $filtered = [];
                foreach ($students as $s) {
                    $sKelas = $s['class']['name'] ?? '';
                    $sNama = $s['full_name'] ?? '';
                    $sNis = $s['nis'] ?? $s['nisn'] ?? '';
                    $sNisn = $s['nisn'] ?? '';

                    $matchKelas = empty($kelas) || strtolower($sKelas) === strtolower($kelas);
                    $matchSearch = empty($search) || str_contains(strtolower($sNama), strtolower($search)) || str_contains($sNis, $search) || str_contains($sNisn, $search);

                    if ($matchKelas && $matchSearch) {
                        $filtered[] = [
                            'nisn' => $sNis,
                            'nis' => $sNis,
                            'nama' => $sNama,
                            'kelas' => $sKelas,
                            'jenis_kelamin' => $s['gender'] ?? 'L',
                        ];
                    }
                }
                return $filtered;
            }
        } catch (\Exception $e) {
            Log::error('BankDataService getSiswa error: ' . $e->getMessage());
        }

        return $this->getMockSiswa($kelas, $search);
    }

    protected function getMockKelas(): array
    {
        return [
            ['nama_kelas' => 'X DPIB 1'],
            ['nama_kelas' => 'X DPIB 2'],
            ['nama_kelas' => 'X RPL 1'],
            ['nama_kelas' => 'X TKJ 1'],
            ['nama_kelas' => 'XI RPL 1'],
            ['nama_kelas' => 'XI TKJ 1'],
            ['nama_kelas' => 'XII RPL 1'],
            ['nama_kelas' => 'XII TKJ 1'],
        ];
    }

    protected function getMockSiswa(?string $kelas = null, ?string $search = null): array
    {
        $allStudents = [
            ['nisn' => '12631001', 'nis' => '12631001', 'nama' => 'Ahmad Fadhil', 'kelas' => 'X RPL 1', 'jenis_kelamin' => 'L'],
            ['nisn' => '12631002', 'nis' => '12631002', 'nama' => 'Anisa Rahmawati', 'kelas' => 'X RPL 1', 'jenis_kelamin' => 'P'],
            ['nisn' => '12631003', 'nis' => '12631003', 'nama' => 'Bagus Setiawan', 'kelas' => 'X RPL 1', 'jenis_kelamin' => 'L'],
            ['nisn' => '12631004', 'nis' => '12631004', 'nama' => 'Citra Lestari', 'kelas' => 'X RPL 1', 'jenis_kelamin' => 'P'],
            ['nisn' => '12631005', 'nis' => '12631005', 'nama' => 'Dwi Prasetyo', 'kelas' => 'X RPL 2', 'jenis_kelamin' => 'L'],
            ['nisn' => '12631006', 'nis' => '12631006', 'nama' => 'Eka Putri Utami', 'kelas' => 'X RPL 2', 'jenis_kelamin' => 'P'],
        ];

        return array_values(array_filter($allStudents, function ($siswa) use ($kelas, $search) {
            $matchKelas = empty($kelas) || strtolower($siswa['kelas']) === strtolower($kelas);
            $matchSearch = empty($search) || str_contains(strtolower($siswa['nama']), strtolower($search)) || str_contains($siswa['nisn'], $search) || str_contains($siswa['nis'], $search);
            return $matchKelas && $matchSearch;
        }));
    }
}
