@extends('layouts.app')

@section('title', 'Jenis & Poin Pelanggaran')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Master Jenis & Poin Pelanggaran</h4>
            <p class="mb-0">Daftar aturan jenis pelanggaran beserta akumulasi poin dan sanksi default</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary btn-rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#addJenisModal">
            <i class="fa fa-plus-circle me-2"></i> Tambah Jenis Pelanggaran
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-md">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>KODE & PELANGGARAN</strong></th>
                                <th><strong>KATEGOlRI</strong></th>
                                <th><strong>BOBOT POIN</strong></th>
                                <th><strong>SANKSI DEFAULT</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisPelanggaranList as $item)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>
                                        <span class="font-w600 text-black d-block">{{ $item->nama_pelanggaran }}</span>
                                        @if($item->kode_pelanggaran)
                                            <span class="badge badge-xs bg-secondary text-white">{{ $item->kode_pelanggaran }}</span>
                                        @endif
                                        @if($item->deskripsi)
                                            <small class="text-muted d-block">{{ $item->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white fs-14 font-w600">+{{ $item->poin }} Poin</span>
                                    </td>
                                    <td>
                                        <span class="text-dark fs-13">{{ $item->sanksi_default ?? 'Teguran' }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editJenisModal{{ $item->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('jenis-pelanggaran.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus jenis pelanggaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editJenisModal{{ $item->id }}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('jenis-pelanggaran.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Jenis Pelanggaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Kategori</label>
                                                        <select name="kategori_id" class="form-control" required>
                                                            @foreach($kategoriList as $kat)
                                                                <option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 mb-3">
                                                            <label class="form-label font-w600 text-black">Nama Pelanggaran</label>
                                                            <input type="text" name="nama_pelanggaran" class="form-control" value="{{ $item->nama_pelanggaran }}" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label font-w600 text-black">Bobot Poin</label>
                                                            <input type="number" name="poin" class="form-control" value="{{ $item->poin }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Kode Pelanggaran (Opsional)</label>
                                                        <input type="text" name="kode_pelanggaran" class="form-control" value="{{ $item->kode_pelanggaran }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Sanksi Default</label>
                                                        <textarea name="sanksi_default" class="form-control" rows="2">{{ $item->sanksi_default }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" rows="2">{{ $item->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada jenis pelanggaran. Klik "Tambah Jenis Pelanggaran" untuk menambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addJenisModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('jenis-pelanggaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pelanggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label font-w600 text-black">Nama Pelanggaran <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pelanggaran" class="form-control" placeholder="Contoh: Membolos jam pelajaran" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-w600 text-black">Bobot Poin <span class="text-danger">*</span></label>
                            <input type="number" name="poin" class="form-control" value="10" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Kode Pelanggaran</label>
                        <input type="text" name="kode_pelanggaran" class="form-control" placeholder="Contoh: DIS-03">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Sanksi Default</label>
                        <textarea name="sanksi_default" class="form-control" rows="2" placeholder="Contoh: Teguran lisan & pemanggilan wali kelas"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan rincian pelanggaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Jenis Pelanggaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
