@extends('layouts.app')

@section('title', 'Riwayat Pelanggaran Siswa')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Daftar & Riwayat Pelanggaran Siswa</h4>
            <p class="mb-0">Semua catatan pelanggaran kedisiplinan yang telah diinput</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex gap-2">
        <a href="{{ route('pelanggaran-siswa.print', request()->all()) }}" target="_blank" class="btn btn-outline-secondary btn-rounded font-w600">
            <i class="fa fa-print me-1"></i> Cetak / Export Rekap
        </a>
        <a href="{{ route('pelanggaran-siswa.create') }}" class="btn btn-primary btn-rounded shadow-sm font-w600">
            <i class="fa fa-plus-circle me-1"></i> Catat Pelanggaran Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header pb-0 border-0">
                <form action="{{ route('pelanggaran-siswa.index') }}" method="GET" class="w-100">
                    <div class="row g-2">
                        <!-- Filter Tingkat -->
                        <div class="col-md-2">
                            <label class="form-label fs-12 mb-1 font-w600">Tingkat Kelas</label>
                            <select name="tingkat" class="form-select form-select-sm border-primary" onchange="this.form.submit()">
                                <option value="">-- Semua --</option>
                                <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <!-- Filter Kelas -->
                        <div class="col-md-3">
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

                        <!-- Filter Tanggal -->
                        <div class="col-md-2">
                            <label class="form-label fs-12 mb-1 font-w600">Filter Tanggal</label>
                            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" onchange="this.form.submit()">
                        </div>

                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label fs-12 mb-1 font-w600">Cari Nama / NISN Siswa</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="q" class="form-control" placeholder="Kata kunci nama/NISN..." value="{{ request('q') }}">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-sm btn-light w-100"><i class="fa fa-sync me-1"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>TANGGAL & WAKTU</strong></th>
                                <th><strong>SISWA & KELAS</strong></th>
                                <th><strong>JENIS PELANGGARAN</strong></th>
                                <th><strong>POIN</strong></th>
                                <th><strong>PENCATAT</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggaranList as $item)
                                <tr>
                                    <td><strong>{{ $pelanggaranList->firstItem() + $loop->index }}</strong></td>
                                    <td>
                                        <span class="font-w600 text-black d-block">{{ \Carbon\Carbon::parse($item->tanggal_pelanggaran)->format('d/m/Y') }}</span>
                                        <small class="text-muted"><i class="fa fa-clock me-1"></i>{{ $item->waktu_pelanggaran ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="font-w700 text-primary d-block">{{ $item->siswa_nama }}</span>
                                        <span class="badge badge-sm bg-primary text-white mb-1">{{ $item->siswa_kelas }}</span>
                                        <small class="text-muted d-block">NIS: {{ $item->siswa_nisn }}</small>
                                    </td>
                                    <td>
                                        <span class="font-w600 text-black d-block">{{ $item->nama_pelanggaran_formatted }}</span>
                                        <span class="badge badge-xs bg-light text-dark me-1">{{ $item->jenisPelanggaran->kategori->nama_kategori ?? 'Umum' }}</span>
                                        @if($item->catatan_keterangan)
                                            <small class="text-secondary d-block mt-1 fs-11"><i class="fa fa-info-circle me-1 text-primary"></i>{{ \Illuminate\Support\Str::limit($item->catatan_keterangan, 75) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white font-w700 fs-13">+{{ $item->poin_pelanggaran }} Poin</span>
                                    </td>
                                    <td>
                                        <small class="font-w500 text-dark">{{ $item->pencatat->name ?? 'Wakasek' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('pelanggaran-siswa.show', $item->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Lihat Detail & Track Siswa"><i class="fa fa-eye"></i></a>
                                            <form action="{{ route('pelanggaran-siswa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus data pelanggaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Hapus"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa fa-clipboard-check text-muted fs-40 mb-2 d-block"></i>
                                        <span class="text-muted font-w600">Belum ada data pelanggaran siswa yang dicatat.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $pelanggaranList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
