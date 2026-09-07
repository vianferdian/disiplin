@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="row page-titles mx-0 align-items-center">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4 class="text-black font-w700 mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="mb-0 text-muted">Sistem Digitalisasi Pelanggaran dan Kedisiplinan Siswa (DISIPLIN)</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a href="{{ route('pelanggaran-siswa.create') }}" class="btn btn-primary btn-rounded shadow-sm font-w600 px-4">
            <i class="fa fa-plus-circle me-2"></i> Catat Pelanggaran Siswa
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row">
    <!-- Card 1: Hari Ini -->
    <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
        <div class="card widget-stat bg-primary text-white shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="media d-flex align-items-center">
                    <span class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-calendar-day text-white" style="font-size: 18px;"></i>
                    </span>
                    <div class="media-body text-white flex-grow-1">
                        <p class="mb-0 text-white-50 font-w600 fs-11 text-uppercase">Pelanggaran Hari Ini</p>
                        <h3 class="text-white font-w800 mb-0" style="font-size: 24px; line-height:1.2;">{{ $totalPelanggaranHariIni }}</h3>
                        <small class="text-white-50" style="font-size: 10px;">Data real-time</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Bulan Ini -->
    <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
        <div class="card widget-stat bg-warning text-white shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="media d-flex align-items-center">
                    <span class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-clock-rotate-left text-white" style="font-size: 18px;"></i>
                    </span>
                    <div class="media-body text-white flex-grow-1">
                        <p class="mb-0 text-white-50 font-w600 fs-11 text-uppercase">Pelanggaran Bulan Ini</p>
                        <h3 class="text-white font-w800 mb-0" style="font-size: 24px; line-height:1.2;">{{ $totalPelanggaranBulanIni }}</h3>
                        <small class="text-white-50" style="font-size: 10px;">{{ date('F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Siswa -->
    <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
        <div class="card widget-stat bg-danger text-white shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="media d-flex align-items-center">
                    <span class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-user-xmark text-white" style="font-size: 18px;"></i>
                    </span>
                    <div class="media-body text-white flex-grow-1">
                        <p class="mb-0 text-white-50 font-w600 fs-11 text-uppercase">Total Siswa Tercatat</p>
                        <h3 class="text-white font-w800 mb-0" style="font-size: 24px; line-height:1.2;">{{ $totalSiswaTercatat }}</h3>
                        <small class="text-white-50" style="font-size: 10px;">Poin Pelanggaran</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Master Jenis -->
    <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
        <div class="card widget-stat bg-info text-white shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="media d-flex align-items-center">
                    <span class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-cubes text-white" style="font-size: 18px;"></i>
                    </span>
                    <div class="media-body text-white flex-grow-1">
                        <p class="mb-0 text-white-50 font-w600 fs-11 text-uppercase">Jenis Pelanggaran</p>
                        <h3 class="text-white font-w800 mb-0" style="font-size: 24px; line-height:1.2;">{{ $totalJenisPelanggaran }}</h3>
                        <small class="text-white-50" style="font-size: 10px;">Dikelola oleh Admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Violation Records -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title text-black font-w700 mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i> Pelanggaran Terbaru Dicatat</h5>
                <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-sm btn-outline-primary font-w600">Lihat Semua <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-md align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="fs-12 font-w700 text-uppercase">WAKTU</th>
                                <th class="fs-12 font-w700 text-uppercase">SISWA & KELAS</th>
                                <th class="fs-12 font-w700 text-uppercase">JENIS PELANGGARAN</th>
                                <th class="fs-12 font-w700 text-uppercase">POIN</th>
                                <th class="fs-12 font-w700 text-uppercase">PENCATAT</th>
                                <th class="fs-12 font-w700 text-uppercase text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggaranTerbaru as $item)
                                <tr>
                                    <td>
                                        <span class="font-w700 text-dark d-block fs-13">{{ \Carbon\Carbon::parse($item->tanggal_pelanggaran)->format('d/m/Y') }}</span>
                                        <small class="text-muted fs-11"><i class="fa-regular fa-clock me-1"></i>{{ $item->waktu_pelanggaran ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="font-w700 text-primary d-block fs-14">{{ $item->siswa_nama }}</span>
                                        <span class="badge bg-light text-dark font-w600 fs-11 border me-1">{{ $item->siswa_kelas }}</span>
                                        <small class="text-muted fs-11">NIS: {{ $item->siswa_nisn }}</small>
                                    </td>
                                    <td>
                                        <span class="font-w600 text-black d-block fs-13">{{ $item->nama_pelanggaran_formatted }}</span>
                                        <span class="badge bg-info-light text-info fs-10 font-w600">{{ $item->jenisPelanggaran->kategori->nama_kategori ?? 'Umum' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white font-w700 fs-12 px-2 py-1">+{{ $item->poin_pelanggaran }} Poin</span>
                                    </td>
                                    <td>
                                        <small class="font-w600 text-secondary fs-12">{{ $item->pencatat->name ?? 'Wakasek' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pelanggaran-siswa.show', $item->id) }}" class="btn btn-primary shadow-sm btn-xs sharp rounded-circle" title="Detail"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted fs-13">Belum ada pelanggaran yang dicatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Violation Students -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title text-black font-w700 mb-0"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> Siswa Poin Akumulasi Tinggi</h5>
            </div>
            <div class="card-body">
                @forelse($topSiswaPoin as $siswa)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-3 font-w800 fs-16 border" style="width: 42px; height: 42px; min-width: 42px;">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden me-2">
                            <h6 class="mb-0 text-black font-w700 text-truncate fs-14">{{ $siswa->siswa_nama }}</h6>
                            <span class="badge bg-light text-muted border fs-10">{{ $siswa->siswa_kelas }}</span>
                            <small class="text-muted fs-11 ms-1">NISN: {{ $siswa->siswa_nisn }}</small>
                        </div>
                        <div>
                            <span class="badge bg-danger text-white fs-13 font-w700 px-2 py-1">{{ $siswa->total_poin }} Poin</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3 fs-13">Belum ada akumulasi poin siswa.</p>
                @endforelse

                <div class="mt-3">
                    <a href="{{ route('pelanggaran-siswa.report') }}" class="btn btn-outline-primary btn-block w-100 font-w600 py-2">
                        <i class="fa-solid fa-file-invoice me-2"></i> Lihat Rekapitulasi Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
