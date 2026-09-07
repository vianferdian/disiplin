@extends('layouts.app')

@section('title', 'Manajemen Akun User')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Manajemen Akun User</h4>
            <p class="mb-0">Kelola akses pengguna sistem DISIPLIN (Admin & Wakasek Kesiswaan)</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary btn-rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa fa-user-plus me-2"></i> Tambah User Baru
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
                                <th><strong>NAMA LENGKAP</strong></th>
                                <th><strong>USERNAME</strong></th>
                                <th><strong>EMAIL</strong></th>
                                <th><strong>HAK AKSES / ROLE</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td><span class="font-w700 text-black">{{ $user->name }}</span></td>
                                    <td><code>{{ $user->username }}</code></td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger text-white">Administrator</span>
                                        @else
                                            <span class="badge bg-primary text-white">Wakasek Kesiswaan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Akun User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Nama Lengkap</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Hak Akses / Role</label>
                                                        <select name="role" class="form-control" required>
                                                            <option value="wakasek" {{ $user->role == 'wakasek' ? 'selected' : '' }}>Wakasek Kesiswaan</option>
                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label font-w600 text-black">Password Baru (Kosongkan jika tidak diubah)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="••••••••">
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
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada user.</td>
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
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Drs. H. Mulyadi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: wakasek_mulyadi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="mulyadi@disiplin.sch.id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Hak Akses / Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="wakasek">Wakasek Kesiswaan</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
