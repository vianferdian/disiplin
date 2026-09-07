@extends('layouts.app')

@section('title', 'Rekapitulasi Poin Pelanggaran Siswa')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Rekapitulasi Akumulasi Poin Pelanggaran Siswa</h4>
            <p class="mb-0">Peringkat & total akumulasi poin pelanggaran per siswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex gap-2">
        <a href="{{ route('pelanggaran-siswa.print', request()->all()) }}" target="_blank" class="btn btn-outline-secondary btn-rounded font-w600">
            <i class="fa fa-print me-1"></i> Cetak / Export Rekap
        </a>
        <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-primary btn-rounded shadow-sm">
            <i class="fa fa-list me-1"></i> Lihat Semua Riwayat
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header pb-0 border-0">
                <form action="{{ route('pelanggaran-siswa.report') }}" method="GET" class="w-100">
                    <div class="row g-2">
                        <!-- Filter Tingkat -->
                        <div class="col-md-3">
                            <label class="form-label fs-12 mb-1 font-w600">Filter Tingkat Kelas</label>
                            <select name="tingkat" class="form-select form-select-sm border-primary" onchange="this.form.submit()">
                                <option value="">-- Semua Tingkat --</option>
                                <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <!-- Filter Kelas -->
                        <div class="col-md-4">
                            <label class="form-label fs-12 mb-1 font-w600">Filter Rombel / Kelas</label>
                            <select name="kelas" class="form-select form-select-sm border-primary" onchange="this.form.submit()">
                                <option value="">-- Semua Rombel --</option>
                                @foreach(['X', 'XI', 'XII', 'Lainnya'] as $t)
                                    @if(!empty($kelasListGrouped[$t]))
                                        <optgroup label="Kelas {{ $t }}">
                                            @foreach($kelasListGrouped[$t] as $kNama)
                                                <option value="{{ $kNama }}" {{ request('kelas') == $kNama ? 'selected' : '' }}>{{ $kNama }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('pelanggaran-siswa.report') }}" class="btn btn-sm btn-light w-100"><i class="fa fa-sync me-1"></i> Reset Filter</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th><strong>PERINGKAT</strong></th>
                                <th><strong>NAMA SISWA</strong></th>
                                <th><strong>NIS</strong></th>
                                <th><strong>KELAS</strong></th>
                                <th><strong>FREKUENSI PELANGGARAN</strong></th>
                                <th><strong>AKUMULASI POIN</strong></th>
                                <th><strong>KATEGORI TINDAKAN / SANKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapSiswa as $index => $item)
                                @php
                                    $poin = $item->total_poin;
                                    $statusPoin = 'Aman / Teguran';
                                    $badgeColor = 'bg-success';
                                    if ($poin >= 50) {
                                        $statusPoin = 'Peringatan III / Pemanggilan Orang Tua';
                                        $badgeColor = 'bg-danger';
                                    } elseif ($poin >= 30) {
                                        $statusPoin = 'Peringatan II / Konseling Kesiswaan';
                                        $badgeColor = 'bg-warning text-dark';
                                    } elseif ($poin >= 15) {
                                        $statusPoin = 'Peringatan I / Teguran Tertulis';
                                        $badgeColor = 'bg-info';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge rounded-circle p-2 {{ $index == 0 ? 'bg-danger text-white' : ($index == 1 ? 'bg-warning text-dark' : 'bg-light text-dark') }} font-w700" style="width: 28px; height: 28px; display:inline-flex; align-items:center; justify-content:center;">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-w700 text-black fs-15">{{ $item->siswa_nama }}</span>
                                    </td>
                                    <td>
                                        <code class="text-dark">{{ $item->siswa_nisn }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white font-w600">{{ $item->siswa_kelas }}</span>
                                    </td>
                                    <td>
                                        <span class="font-w600 text-dark">{{ $item->total_pelanggaran }}x Kejadian</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white font-w800 fs-14 px-3 py-2">+{{ $poin }} Poin</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeColor }} font-w600">{{ $statusPoin }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa fa-chart-bar text-muted fs-40 mb-2 d-block"></i>
                                        <span class="text-muted font-w600">Belum ada data akumulasi pelanggaran siswa.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
