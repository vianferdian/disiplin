@extends('layouts.app')

@section('title', 'Pengaturan TTD Wakasek Kesiswaan')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Pengaturan Tanda Tangan Laporan (TTD)</h4>
            <p class="mb-0">Kustomisasi Nama Lengkap & NIP Wakasek Kesiswaan untuk dokumen cetak PDF</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="fa fa-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <i class="fa-solid fa-file-signature text-primary me-2 fs-18"></i>
                <h5 class="card-title text-black font-w700 mb-0">Form Data TTD Wakasek Kesiswaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.wakasek.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label font-w600 text-black">Nama Lengkap & Gelar Wakasek <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa fa-user-tie text-primary"></i></span>
                            <input type="text" name="wakasek_nama" class="form-control @error('wakasek_nama') is-invalid @enderror" 
                                value="{{ old('wakasek_nama', $wakasekNama) }}" placeholder="Contoh: Drs. H. Mulyadi, M.Pd" required>
                        </div>
                        @error('wakasek_nama')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted fs-11 mt-1 d-block"><i class="fa fa-info-circle me-1"></i> Nama ini akan tercantum di bagian kanan bawah setiap cetak dokumen/PDF.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-w600 text-black">NIP (Nomor Induk Pegawai)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa fa-id-card text-primary"></i></span>
                            <input type="text" name="wakasek_nip" class="form-control @error('wakasek_nip') is-invalid @enderror" 
                                value="{{ old('wakasek_nip', $wakasekNip) }}" placeholder="Contoh: 19780512 200501 1 004">
                        </div>
                        @error('wakasek_nip')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted fs-11 mt-1 d-block"><i class="fa fa-info-circle me-1"></i> Kosongkan atau isi '-' jika tidak ada NIP.</small>
                    </div>

                    <!-- Live Preview Box -->
                    <div class="p-3 rounded border mb-4" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                        <span class="badge bg-primary text-white mb-2 fs-10 font-w600">PREVIEW TTD PADA DOKUMEN CETAK</span>
                        <div class="text-center p-3 bg-white rounded border shadow-xs" style="max-width: 280px; margin: 0 auto;">
                            <small class="text-muted d-block fs-11">Cirebon, {{ date('d F Y') }}</small>
                            <strong class="d-block text-dark font-w700 fs-12 mt-1">Wakasek Kesiswaan</strong>
                            <div style="height: 50px;"></div>
                            <strong class="d-block text-dark font-w700 fs-12">( {{ $wakasekNama }} )</strong>
                            <small class="text-muted d-block fs-11">NIP. {{ $wakasekNip }}</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary font-w600 px-4 btn-rounded shadow-sm">
                            <i class="fa fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
