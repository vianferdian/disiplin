@extends('layouts.app')

@section('title', 'Integrasi & Sync Bank Data API')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Integrasi & Sinkronisasi Bank Data API</h4>
            <p class="mb-0">Kelola koneksi Bank Data API sekolah dan sinkronisasi data siswa (Tingkat X, XI, XII)</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex gap-2">
        <button id="btnTestApi" class="btn btn-outline-primary btn-sm btn-rounded px-3">
            <i class="fa fa-plug me-1"></i> Uji Koneksi API
        </button>
        <button id="btnSyncNow" class="btn btn-primary btn-sm btn-rounded font-w600 shadow-sm px-3">
            <i class="fa fa-rotate me-1" id="syncIcon"></i> Sinkronkan Data Sekarang
        </button>
    </div>
</div>

<!-- ALERT API STATUS (MINIMALIST) -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-xs" style="border-radius: 10px; background: {{ $testResult['success'] ? '#f0fdf4' : '#fef2f2' }}; border-left: 4px solid {{ $testResult['success'] ? '#16a34a' : '#dc2626' }} !important;">
            <div class="card-body py-2.5 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <span class="badge {{ $testResult['success'] ? 'bg-success' : 'bg-danger' }} rounded-circle me-3 p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                        <i class="fa {{ $testResult['success'] ? 'fa-check' : 'fa-triangle-exclamation' }} text-white fs-14"></i>
                    </span>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="font-w700 text-dark fs-13">Status Endpoint API:</span>
                            <span class="fs-12 fw-semibold {{ $testResult['success'] ? 'text-success' : 'text-danger' }}">{{ $testResult['message'] }}</span>
                        </div>
                        <span class="fs-11 text-muted">Endpoint: <code class="px-1.5 py-0.5 rounded bg-white border text-dark">{{ config('bankdata.api_url') }}/students</code></span>
                    </div>
                </div>
                <div>
                    <span class="badge bg-white text-dark border font-w600 px-2.5 py-1.5 fs-11 shadow-xs" style="border-radius: 6px;">
                        <i class="fa fa-key me-1 text-warning"></i> X-Client-ID: {{ config('bankdata.client_id') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS (MINIMALIST) -->
<div class="row g-3 mb-4">
    <!-- Total Siswa Lokal -->
    <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="card border border-light-subtle shadow-xs bg-white h-100" style="border-radius: 10px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; background-color: #e0e7ff; color: #1E33F2;">
                    <i class="fa fa-users fs-16"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block text-muted text-uppercase fs-11 font-w600" style="letter-spacing: 0.3px;">Total Siswa Tersinkron</span>
                    <div class="d-flex align-items-baseline gap-1.5 mt-0.5">
                        <h4 class="mb-0 font-w700 text-dark fs-18" id="statTotalSiswa">{{ number_format($totalSiswaLocal) }}</h4>
                        <small class="text-muted fs-11">siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kelas -->
    <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="card border border-light-subtle shadow-xs bg-white h-100" style="border-radius: 10px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; background-color: #e0f2fe; color: #0284c7;">
                    <i class="fa fa-school fs-16"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block text-muted text-uppercase fs-11 font-w600" style="letter-spacing: 0.3px;">Total Rombel / Kelas</span>
                    <div class="d-flex align-items-baseline gap-1.5 mt-0.5">
                        <h4 class="mb-0 font-w700 text-dark fs-18" id="statTotalKelas">{{ $totalKelasLocal }}</h4>
                        <small class="text-muted fs-11">kelas (X, XI, XII)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown Tingkat -->
    <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="card border border-light-subtle shadow-xs bg-white h-100" style="border-radius: 10px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; background-color: #fef3c7; color: #d97706;">
                    <i class="fa fa-layer-group fs-16"></i>
                </div>
                <div class="overflow-hidden w-100">
                    <span class="d-block text-muted text-uppercase fs-11 font-w600 mb-1" style="letter-spacing: 0.3px;">Rincian Per Tingkat</span>
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span class="badge bg-light text-dark border font-w600 px-2 py-0.5 fs-11">X: {{ number_format($tingkatCounts['X']) }}</span>
                        <span class="badge bg-light text-dark border font-w600 px-2 py-0.5 fs-11">XI: {{ number_format($tingkatCounts['XI']) }}</span>
                        <span class="badge bg-light text-dark border font-w600 px-2 py-0.5 fs-11">XII: {{ number_format($tingkatCounts['XII']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Last Synced -->
    <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="card border border-light-subtle shadow-xs bg-white h-100" style="border-radius: 10px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; background-color: #fce7f3; color: #db2777;">
                    <i class="fa fa-clock-rotate-left fs-16"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block text-muted text-uppercase fs-11 font-w600" style="letter-spacing: 0.3px;">Terakhir Disinkron</span>
                    <h6 class="mb-0 font-w700 text-dark mt-1 text-truncate fs-13" id="statLastSynced">
                        {{ $lastSyncedAt ? \Carbon\Carbon::parse($lastSyncedAt)->format('d M Y H:i') : 'Belum Pernah' }}
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- INFORMASI TEKNIS API -->
<div class="row">
    <div class="col-12">
        <div class="card border border-light-subtle shadow-xs" style="border-radius: 10px;">
            <div class="card-header bg-white py-2.5 border-0" style="border-bottom: 1px solid #f1f5f9 !important;">
                <h5 class="card-title text-black mb-0 font-w700 fs-14">
                    <i class="fa fa-code-branch text-primary me-2"></i> Detail Konfigurasi Service Integrasi API
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <tbody class="fs-13">
                            <tr>
                                <th style="width: 250px; background-color: #f8fafc;" class="fw-semibold text-dark">Aplikasi Klien</th>
                                <td><span class="badge bg-primary px-2.5 py-1 fs-12" style="border-radius: 5px;">DISIPLIN — Kesiswaan</span></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;" class="fw-semibold text-dark">Endpoint Host</th>
                                <td><code class="text-primary bg-light px-2 py-0.5 rounded">https://onedata.nepertech.id/api/v1/students</code></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;" class="fw-semibold text-dark">Authentication Header</th>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div><code class="bg-light px-2 py-0.5 rounded text-dark">X-Client-ID: DISIPLIN_GTQ1</code></div>
                                        <div><code class="bg-light px-2 py-0.5 rounded text-dark">X-Client-Secret: qiQdPAoF...</code></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;" class="fw-semibold text-dark">Struktur Data Hasil Synchronize</th>
                                <td class="text-secondary">
                                    Sistem memetakan <code>nisn</code>, <code>nis</code>, <code>full_name</code>, <code>gender</code>, <code>class.name</code> ke dalam sistem DISIPLIN, lalu memisahkan otomatis ke dalam grup <strong>Kelas X</strong>, <strong>Kelas XI</strong>, dan <strong>Kelas XII</strong>.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3 mb-0 fs-12 border-0" style="border-radius: 8px; background: #e0f2fe; color: #0369a1;">
                    <i class="fa fa-info-circle me-1"></i> <strong>Tips:</strong> Apabila terdapat data siswa baru atau perubahan kelas di Bank Data Sekolah, klik tombol <strong>Sinkronkan Data Sekarang</strong> di pojok kanan atas untuk memperbarui data secara instan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnTestApi = document.getElementById('btnTestApi');
    const btnSyncNow = document.getElementById('btnSyncNow');
    const syncIcon = document.getElementById('syncIcon');

    // Test API Connection
    btnTestApi.addEventListener('click', function () {
        btnTestApi.disabled = true;
        btnTestApi.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Menguji...';

        fetch('{{ route("bank-data.test") }}')
            .then(res => res.json())
            .then(data => {
                btnTestApi.disabled = false;
                btnTestApi.innerHTML = '<i class="fa fa-plug me-1"></i> Uji Koneksi API';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Koneksi Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#1E33F2',
                        customClass: { popup: 'rounded-4 shadow-lg border-0' }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal',
                        text: data.message,
                        confirmButtonColor: '#dc2626',
                        customClass: { popup: 'rounded-4 shadow-lg border-0' }
                    });
                }
            })
            .catch(err => {
                btnTestApi.disabled = false;
                btnTestApi.innerHTML = '<i class="fa fa-plug me-1"></i> Uji Koneksi API';
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server',
                    text: 'Gagal menghubungi endpoint API Bank Data.',
                    confirmButtonColor: '#dc2626',
                    customClass: { popup: 'rounded-4 shadow-lg border-0' }
                });
            });
    });

    // Sync Now
    btnSyncNow.addEventListener('click', function () {
        Swal.fire({
            title: 'Konfirmasi Sinkronisasi',
            text: 'Apakah Anda yakin ingin menyinkronkan seluruh data siswa dari Bank Data API Sekolah?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-rotate me-1"></i> Ya, Sinkronkan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0',
                confirmButton: 'btn btn-primary px-4 py-2 font-w600 me-2',
                cancelButton: 'btn btn-light px-4 py-2 font-w600 text-dark'
            },
            buttonsStyling: false
        }).then((result) => {
            if (!result.isConfirmed) return;

            btnSyncNow.disabled = true;
            syncIcon.classList.add('fa-spin');
            btnSyncNow.innerHTML = '<i class="fa fa-rotate fa-spin me-1"></i> Menyinkronkan Data...';

            fetch('{{ route("bank-data.sync") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                syncIcon.classList.remove('fa-spin');
                btnSyncNow.disabled = false;
                btnSyncNow.innerHTML = '<i class="fa fa-rotate me-1"></i> Sinkronkan Data Sekarang';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sinkronisasi Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#1E33F2',
                        customClass: { popup: 'rounded-4 shadow-lg border-0' }
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sinkronisasi Gagal',
                        text: data.message,
                        confirmButtonColor: '#dc2626',
                        customClass: { popup: 'rounded-4 shadow-lg border-0' }
                    });
                }
            })
            .catch(err => {
                syncIcon.classList.remove('fa-spin');
                btnSyncNow.disabled = false;
                btnSyncNow.innerHTML = '<i class="fa fa-rotate me-1"></i> Sinkronkan Data Sekarang';
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: err.message || 'Terjadi kesalahan saat proses sinkronisasi.',
                    confirmButtonColor: '#dc2626',
                    customClass: { popup: 'rounded-4 shadow-lg border-0' }
                });
            });
        });
    });
});
</script>
@endpush

