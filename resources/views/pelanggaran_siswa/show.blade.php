@extends('layouts.app')

@section('title', 'Detail Pelanggaran Siswa')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Detail Record Pelanggaran</h4>
            <p class="mb-0">Rincian data pelanggaran dan rekam jejak kedisiplinan siswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-outline-secondary btn-rounded me-2">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('pelanggaran-siswa.print-single', $pelanggaranSiswa->id) }}" target="_blank" class="btn btn-primary btn-rounded shadow-sm font-w600">
            <i class="fa fa-print me-1"></i> Cetak Detail (PDF Preview)
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Detail Card -->
    <div class="col-xl-5 col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title text-white mb-0 font-w700"><i class="fa fa-user-graduate me-2"></i> Profil Siswa & Kejadian</h5>
            </div>
            <div class="card-body">
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle font-w700 mb-2" style="width: 70px; height: 70px; font-size: 28px;">
                        {{ strtoupper(substr($pelanggaranSiswa->siswa_nama, 0, 1)) }}
                    </div>
                    <h4 class="text-black font-w700 mb-2">{{ $pelanggaranSiswa->siswa_nama }}</h4>
                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap text-center mx-auto w-100">
                        <span class="badge bg-primary text-white font-w600 px-3 py-1 fs-12" style="border-radius: 20px;">{{ $pelanggaranSiswa->siswa_kelas }}</span>
                        <span class="text-secondary font-w600 fs-13">NIS: {{ $pelanggaranSiswa->siswa_nisn }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted font-w600" style="width: 140px;">Pelanggaran:</td>
                            <td class="font-w700 text-black">{{ $pelanggaranSiswa->nama_pelanggaran_formatted }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-w600">Kategori:</td>
                            <td><span class="badge bg-info">{{ $pelanggaranSiswa->jenisPelanggaran->kategori->nama_kategori ?? 'Umum' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted font-w600">Poin Kejadian:</td>
                            <td><span class="badge bg-danger text-white font-w700">+{{ $pelanggaranSiswa->poin_pelanggaran }} Poin</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted font-w600">Tanggal & Waktu:</td>
                            <td class="font-w600 text-dark">{{ \Carbon\Carbon::parse($pelanggaranSiswa->tanggal_pelanggaran)->format('d F Y') }} ({{ $pelanggaranSiswa->waktu_pelanggaran ?? '-' }})</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-w600">Pencatat:</td>
                            <td class="font-w600 text-dark">{{ $pelanggaranSiswa->pencatat->name ?? 'Wakasek Kesiswaan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-w600">Sanksi Default:</td>
                            <td class="text-danger font-w600">{{ $pelanggaranSiswa->jenisPelanggaran->sanksi_default ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                @if($pelanggaranSiswa->catatan_keterangan)
                    <div class="mt-3 p-3 bg-light rounded border">
                        <strong class="d-block text-dark mb-1 fs-13">Catatan / Kronologi:</strong>
                        <p class="mb-0 text-muted fs-13">{{ $pelanggaranSiswa->catatan_keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Student Accumulation History Timeline -->
    <div class="col-xl-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="card-title text-black font-w700 mb-0">Rekam Jejak Kedisiplinan Siswa</h5>
                <div>
                    <span class="text-muted fs-12 me-1">Total Akumulasi:</span>
                    <span class="badge bg-danger font-w700 fs-16">{{ $totalPoinSiswa }} Poin</span>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 fs-13 mb-4">
                    <i class="fa fa-info-circle me-1"></i> Menampilkan semua riwayat catatan pelanggaran milik <strong>{{ $pelanggaranSiswa->siswa_nama }}</strong>.
                </div>

                <div class="widget-timeline">
                    <ul class="timeline">
                        @forelse($riwayatSiswa as $history)
                            <li>
                                <div class="timeline-badge {{ $history->id == $pelanggaranSiswa->id ? 'primary' : 'secondary' }}"></div>
                                <div class="timeline-panel card border p-3 shadow-none">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="font-w700 text-black fs-15">{{ $history->nama_pelanggaran_formatted }}</span>
                                            <small class="d-block text-muted">{{ \Carbon\Carbon::parse($history->tanggal_pelanggaran)->format('d M Y') }} • {{ $history->waktu_pelanggaran }}</small>
                                        </div>
                                        <span class="badge bg-danger text-white font-w700">+{{ $history->poin_pelanggaran }} Poin</span>
                                    </div>
                                    @if($history->catatan_keterangan)
                                        <p class="mb-0 text-secondary fs-13 mt-2"><em>"{{ $history->catatan_keterangan }}"</em></p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <p class="text-muted text-center py-4">Belum ada riwayat lain.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
