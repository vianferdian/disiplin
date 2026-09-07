@extends('layouts.app')

@section('title', 'Kategori Pelanggaran')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Master Kategori Pelanggaran</h4>
            <p class="mb-0">Kelola pengelompokan jenis-jenis pelanggaran siswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary btn-rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
            <i class="fa fa-plus-circle me-2"></i> Tambah Kategori Baru
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
                                <th><strong>NAMA KATEGORI</strong></th>
                                <th><strong>DESKRIPSI</strong></th>
                                <th><strong>TOTAL JENIS PELANGGARAN</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoriList as $kategori)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td><span class="font-w600 text-black">{{ $kategori->nama_kategori }}</span></td>
                                    <td>{{ $kategori->deskripsi ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $kategori->jenis_pelanggaran_count }} Item</span></td>
                                    <td>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editKategoriModal{{ $kategori->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('kategori-pelanggaran.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editKategoriModal{{ $kategori->id }}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('kategori-pelanggaran.update', $kategori->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Kategori Pelanggaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Nama Kategori</label>
                                                        <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $kategori->deskripsi }}</textarea>
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
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori pelanggaran. Klik "Tambah Kategori Baru" untuk menambahkan.</td>
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
<div class="modal fade" id="addKategoriModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('kategori-pelanggaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Pelanggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Kedisiplinan & Kehadiran" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat kategori..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
