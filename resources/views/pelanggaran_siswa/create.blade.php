@extends('layouts.app')

@section('title', 'Form Pencatatan Pelanggaran Siswa')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Form Pencatatan Pelanggaran Siswa</h4>
            <p class="mb-0">Modul Wakasek Kesiswaan untuk mencatat pelanggaran harian & inspeksi seragam</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="fa fa-arrow-left me-2"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="card shadow-sm border-0">
            <!-- MODE SELECTION HEADER -->
            <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title text-white mb-0 font-w700">
                    <i class="fa fa-edit me-2"></i> Form Pencatatan Pelanggaran Siswa
                </h5>

                <div class="btn-group btn-group-sm mode-btn-wrapper" role="group">
                    <button type="button" id="btnModeUniform" class="btn btn-light text-primary font-w700 active">
                        <i class="fa fa-shirt me-1"></i> Inspeksi Seragam Harian
                    </button>
                    <button type="button" id="btnModeStandard" class="btn btn-outline-light text-white font-w600">
                        <i class="fa fa-list-check me-1"></i> Pelanggaran Umum / Tambahan
                    </button>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('pelanggaran-siswa.store') }}" method="POST" id="formPelanggaran">
                    @csrf
                    <input type="hidden" name="input_mode" id="input_mode" value="uniform_checklist">

                    <!-- STEP 1: SISWA & KELAS (LIVE SEARCH & AUTO FILL KELAS) -->
                    <div class="form-step-card p-3 p-md-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary text-white rounded-circle me-2 font-w700" style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                            <h6 class="mb-0 font-w700 text-black">IDENTITAS SISWA & KELAS</h6>
                        </div>

                        <!-- LIVE AUTOCOMPLETE SEARCH INPUT -->
                        <div class="position-relative mb-3">
                            <label class="form-label font-w600 text-black mb-1">Cari Cepat Nama / NIS Siswa <span class="badge bg-primary text-white font-w600 ms-1 fs-11">Auto-Fill Kelas</span></label>
                            <div class="position-relative">
                                <i class="fa fa-search position-absolute text-muted fs-15" style="left: 14px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                                <input type="text" id="inputSearchSiswa" class="form-control border-secondary-subtle fs-14 py-2 rounded-3" placeholder="Ketik nama atau NIS siswa..." autocomplete="off" style="padding-left: 42px !important; height: 46px;">
                            </div>
                            <!-- Live Dropdown Results -->
                            <div id="searchResultsList" class="list-group position-absolute w-100 shadow-lg d-none mt-1" style="z-index: 1050; max-height: 260px; overflow-y: auto; border-radius: 10px;">
                            </div>
                            <small class="text-muted d-block mt-1 fs-11"><i class="fa fa-info-circle me-1"></i> Ketik nama/NIS untuk mencari siswa secara otomatis.</small>
                        </div>

                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1 my-0">
                            <span class="px-3 text-muted fs-11 font-w700 text-uppercase">Atau Pilih Manual Berdasarkan Kelas</span>
                            <hr class="flex-grow-1 my-0">
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label font-w600 text-black">1. Pilih Kelas <span class="text-danger">*</span></label>
                                <select id="selectKelas" name="siswa_kelas" class="form-select border-secondary-subtle rounded-3" style="height: 44px;" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach(['X', 'XI', 'XII', 'Lainnya'] as $tingkat)
                                        @if(!empty($kelasListGrouped[$tingkat]))
                                            <optgroup label="--- TINGKAT KELAS {{ $tingkat }} ---">
                                                @foreach($kelasListGrouped[$tingkat] as $kelasNama)
                                                    <option value="{{ $kelasNama }}">{{ $kelasNama }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-7 mb-3">
                                <label class="form-label font-w600 text-black">2. Pilih Nama Siswa <span class="text-danger">*</span></label>
                                <select id="selectSiswa" class="form-select border-secondary-subtle rounded-3" style="height: 44px;" required disabled>
                                    <option value="">-- Silakan Pilih Kelas / Ketik Nama Di Atas --</option>
                                </select>
                                <div id="siswaLoading" class="spinner-border spinner-border-sm text-primary mt-1 d-none" role="status">
                                    <span class="visually-hidden">Loading data siswa...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for NISN & Nama -->
                        <input type="hidden" name="siswa_nisn" id="siswa_nisn">
                        <input type="hidden" name="siswa_nama" id="siswa_nama">

                        <!-- Preview Student Card -->
                        <div id="studentPreviewCard" class="alert alert-info d-none mb-0 mt-2 py-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-user-check fs-20 me-3 text-info"></i>
                                    <div>
                                        <strong id="previewNama" class="d-block text-dark font-w700"></strong>
                                        <small id="previewDetails" class="text-muted"></small>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white font-w700 px-3 py-2 fs-12" id="previewKelasBadge"></span>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2A: MODE INSPEKSI SERAGAM HARIAN (SENIN - JUMAT) -->
                    <div id="sectionModeUniform" class="form-step-card p-3 p-md-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary text-white rounded-circle me-2 font-w700" style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                                <h6 class="mb-0 font-w700 text-black">INSPEKSI KETENTUAN SERAGAM HARIAN</h6>
                            </div>

                            <!-- Day Switcher Pills -->
                            <div class="nav nav-pills" id="pillsDayTab" role="tablist">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    <button class="nav-link py-1 px-3 fs-12 font-w600 me-1 {{ $day === $todayName ? 'active' : '' }}" 
                                        type="button" data-day="{{ $day }}">
                                        {{ $day }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Active Day Info Banner -->
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                            <div class="day-container {{ $day === $todayName ? '' : 'd-none' }}" id="dayBox_{{ $day }}">
                                <div class="alert alert-primary py-2 px-3 mb-3 d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="fa fa-calendar-day me-2"></i> Ketentuan Hari <strong>{{ $day }}</strong>: 
                                        <span class="badge bg-white text-primary font-w700 ms-1">{{ $uniformItemsByDay[$day]['baju'] }}</span>
                                    </span>
                                    <span class="fs-12 text-primary font-w600">Centang atribut yang dilanggar</span>
                                </div>

                                <div class="row g-3">
                                    @forelse($uniformItemsByDay[$day]['items'] as $item)
                                        <div class="col-md-6 mb-2">
                                            <label class="uniform-card-selector rounded-3 p-3 d-flex align-items-center justify-content-between w-100 mb-0 shadow-sm" for="chk_{{ $item->id }}">
                                                <div class="d-flex align-items-center me-2">
                                                    <input type="checkbox" name="uniform_items[]" value="{{ $item->id }}" 
                                                        class="uniform-chk me-3" id="chk_{{ $item->id }}" 
                                                        data-poin="{{ $item->poin }}" data-nama="{{ $item->nama_pelanggaran }}">
                                                    <div>
                                                        <strong class="d-block text-dark fs-14 item-title font-w700">{{ $item->nama_pelanggaran }}</strong>
                                                        <small class="text-muted fs-11">Klik untuk mencatat pelanggaran ini</small>
                                                    </div>
                                                </div>
                                                <span class="badge {{ $item->poin > 0 ? 'bg-danger text-white' : 'bg-secondary text-white' }} font-w700 fs-12 px-3 py-2 rounded-pill" style="min-width: 70px;">{{ $item->poin > 0 ? '+'.$item->poin : '0' }} Poin</span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted fs-13 mb-0">Belum ada item atribut seragam tersimpan untuk hari ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach

                        <!-- Total Points Summary for Checklist -->
                        <div id="uniformSummaryBox" class="alert alert-warning d-none mt-3 mb-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-circle-exclamation text-warning me-2"></i>
                                <strong id="summarySelectedCount" class="text-dark">0 Item Dicentang</strong>
                            </div>
                            <div>
                                <span class="text-muted fs-12 me-2">Estimasi Akumulasi Poin:</span>
                                <span id="summaryTotalPoin" class="badge bg-danger fs-16 font-w700 px-3 py-1">+0 Poin</span>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2B: MODE STANDARD PELANGGARAN UMUM -->
                    <div id="sectionModeStandard" class="form-step-card p-3 p-md-4 mb-4 d-none">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary text-white rounded-circle me-2 font-w700" style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                            <h6 class="mb-0 font-w700 text-black">PILIH JENIS PELANGGARAN UMUM</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-w600 text-black">Pilih Jenis Pelanggaran <span class="text-danger">*</span></label>
                            <select name="jenis_pelanggaran_id" id="selectJenisPelanggaran" class="form-select border-primary">
                                <option value="">-- Pilih Jenis Pelanggaran --</option>
                                @foreach($jenisPelanggaranList as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                        data-poin="{{ $jenis->poin }}" 
                                        data-kategori="{{ $jenis->kategori->nama_kategori ?? '-' }}"
                                        data-sanksi="{{ $jenis->sanksi_default ?? 'Teguran' }}">
                                        [{{ $jenis->kategori->nama_kategori ?? 'Umum' }}] {{ $jenis->nama_pelanggaran }} ({{ $jenis->poin > 0 ? '+'.$jenis->poin : '0' }} Poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Violation Details Preview -->
                        <div id="violationPreviewCard" class="card border border-warning bg-white d-none mb-0">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <span id="previewKategoriBadge" class="badge bg-info mb-1"></span>
                                        <h6 id="previewNamaPelanggaran" class="mb-1 font-w700 text-black"></h6>
                                        <p class="mb-0 fs-13 text-muted"><strong>Sanksi Default:</strong> <span id="previewSanksi"></span></p>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                        <span class="text-muted d-block fs-12">Poin Pelanggaran:</span>
                                        <span id="previewPoinBadge" class="badge bg-danger fs-18 font-w700 px-3 py-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: TANGGAL & CATATAN -->
                    <div class="form-step-card p-3 p-md-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary text-white rounded-circle me-2 font-w700" style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;">3</span>
                            <h6 class="mb-0 font-w700 text-black">WAKTU & CATATAN KETERANGAN</h6>
                        </div>

                        <!-- Automatic Date & Time Banner (Real-Time Live) -->
                        <input type="hidden" name="tanggal_pelanggaran" id="input_tanggal_pelanggaran" value="{{ date('Y-m-d') }}">
                        <input type="hidden" name="waktu_pelanggaran" id="input_waktu_pelanggaran" value="{{ date('H:i:s') }}">

                        <div class="alert alert-info py-2.5 px-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2 rounded-3 border-0 shadow-xs" style="background: #e0f2fe; color: #0369a1;">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-2.5 shadow-xs" style="width: 34px; height: 34px; min-width: 34px;">
                                    <i class="fa fa-clock text-primary fs-15"></i>
                                </div>
                                <div>
                                    <span class="fs-11 text-secondary d-block font-w600">Waktu & Tanggal Laporan (Real-Time Live):</span>
                                    <strong id="displayRealtimeClock" class="text-dark font-w700 fs-14">Memuat jam real-time...</strong>
                                </div>
                            </div>
                            <span class="badge bg-white text-primary border font-w600 px-2.5 py-1.5 fs-11" style="border-radius: 6px;">
                                <i class="fa fa-rotate fa-spin me-1 text-success"></i> Synchronized Live
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-w600 text-black">Catatan Keterangan / Kronologi Kejadian (Opsional)</label>
                            <textarea name="catatan_keterangan" class="form-control" rows="3" placeholder="Masukkan lokasi penertiban, catatan tambahan, atau keterangan khusus..."></textarea>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pelanggaran-siswa.index') }}" class="btn btn-danger light px-4">Batal</a>
                        <button type="submit" id="btnSubmit" class="btn btn-primary px-5 font-w700 shadow" disabled>
                            <i class="fa fa-save me-2"></i> Simpan Record Pelanggaran
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-step-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
        border-radius: 14px !important;
    }
    .uniform-card-selector {
        background: #ffffff !important;
        cursor: pointer !important;
        transition: all 0.2s ease-in-out !important;
        border: 2px solid #e2e8f0 !important;
        user-select: none !important;
    }
    .uniform-card-selector:hover {
        border-color: #1E33F2 !important;
        background: #f8fafc !important;
    }
    .uniform-card-selector.active-card {
        border-color: #1E33F2 !important;
        background: #eff6ff !important;
        box-shadow: 0 4px 14px rgba(30, 51, 242, 0.15) !important;
    }
    .uniform-card-selector.active-card .item-title {
        color: #1E33F2 !important;
    }
    .uniform-chk {
        width: 22px !important;
        height: 22px !important;
        cursor: pointer !important;
        accent-color: #1E33F2 !important;
    }

    #searchResultsList {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25) !important;
        z-index: 99999 !important;
        border-radius: 10px !important;
    }
    #searchResultsList .list-group-item {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background 0.15s ease;
    }
    #searchResultsList .list-group-item:hover,
    #searchResultsList .list-group-item:focus {
        background-color: #eff6ff !important;
        color: #1E33F2 !important;
    }

    @media (max-width: 575px) {
        .mode-btn-wrapper {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            gap: 6px !important;
        }
        .mode-btn-wrapper .btn {
            border-radius: 8px !important;
            width: 100% !important;
            text-align: center !important;
        }
        .uniform-card-selector {
            padding: 10px 12px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Real-Time Live Clock Ticker
    function updateRealtimeClock() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const formattedDateStr = `${year}-${month}-${day}`;

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const formattedTimeStr = `${hours}:${minutes}:${seconds}`;

        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const dayName = dayNames[now.getDay()];
        const monthName = monthNames[now.getMonth()];
        const dateNum = now.getDate();

        const displayStr = `${dayName}, ${dateNum} ${monthName} ${year} — ${hours}:${minutes}:${seconds} WIB`;

        const clockDisplay = document.getElementById('displayRealtimeClock');
        const inputDate = document.getElementById('input_tanggal_pelanggaran');
        const inputTime = document.getElementById('input_waktu_pelanggaran');

        if (clockDisplay) clockDisplay.innerText = displayStr;
        if (inputDate) inputDate.value = formattedDateStr;
        if (inputTime) inputTime.value = formattedTimeStr;
    }

    updateRealtimeClock();
    setInterval(updateRealtimeClock, 1000);
    const btnModeUniform = document.getElementById('btnModeUniform');
    const btnModeStandard = document.getElementById('btnModeStandard');
    const inputMode = document.getElementById('input_mode');

    const sectionModeUniform = document.getElementById('sectionModeUniform');
    const sectionModeStandard = document.getElementById('sectionModeStandard');

    const selectKelas = document.getElementById('selectKelas');
    const selectSiswa = document.getElementById('selectSiswa');
    const siswaLoading = document.getElementById('siswaLoading');
    const siswaNisn = document.getElementById('siswa_nisn');
    const siswaNama = document.getElementById('siswa_nama');
    const studentPreviewCard = document.getElementById('studentPreviewCard');
    const previewNama = document.getElementById('previewNama');
    const previewDetails = document.getElementById('previewDetails');

    const selectJenisPelanggaran = document.getElementById('selectJenisPelanggaran');
    const violationPreviewCard = document.getElementById('violationPreviewCard');
    const previewKategoriBadge = document.getElementById('previewKategoriBadge');
    const previewNamaPelanggaran = document.getElementById('previewNamaPelanggaran');
    const previewSanksi = document.getElementById('previewSanksi');
    const previewPoinBadge = document.getElementById('previewPoinBadge');

    const btnSubmit = document.getElementById('btnSubmit');

    // Uniform checklist elements
    const uniformCheckboxes = document.querySelectorAll('.uniform-chk');
    const uniformSummaryBox = document.getElementById('uniformSummaryBox');
    const summarySelectedCount = document.getElementById('summarySelectedCount');
    const summaryTotalPoin = document.getElementById('summaryTotalPoin');

    // Switch Mode logic
    btnModeUniform.addEventListener('click', function () {
        inputMode.value = 'uniform_checklist';
        btnModeUniform.classList.add('btn-light', 'text-primary', 'active');
        btnModeUniform.classList.remove('btn-outline-light', 'text-white');
        btnModeStandard.classList.add('btn-outline-light', 'text-white');
        btnModeStandard.classList.remove('btn-light', 'text-primary', 'active');

        sectionModeUniform.classList.remove('d-none');
        sectionModeStandard.classList.add('d-none');
        selectJenisPelanggaran.required = false;
        validateFormReady();
    });

    btnModeStandard.addEventListener('click', function () {
        inputMode.value = 'standard';
        btnModeStandard.classList.add('btn-light', 'text-primary', 'active');
        btnModeStandard.classList.remove('btn-outline-light', 'text-white');
        btnModeUniform.classList.add('btn-outline-light', 'text-white');
        btnModeUniform.classList.remove('btn-light', 'text-primary', 'active');

        sectionModeStandard.classList.remove('d-none');
        sectionModeUniform.classList.add('d-none');
        selectJenisPelanggaran.required = true;
        validateFormReady();
    });

    // Day Switcher Tabs
    const dayButtons = document.querySelectorAll('#pillsDayTab button');
    dayButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            dayButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const selectedDay = this.getAttribute('data-day');
            document.querySelectorAll('.day-container').forEach(box => box.classList.add('d-none'));
            const targetBox = document.getElementById('dayBox_' + selectedDay);
            if (targetBox) {
                targetBox.classList.remove('d-none');
            }
        });
    });

    // Checkbox cards calculate points & toggle active highlight state
    uniformCheckboxes.forEach(chk => {
        chk.addEventListener('change', function () {
            const parentCard = this.closest('.uniform-card-selector');
            if (parentCard) {
                if (this.checked) {
                    parentCard.classList.add('active-card');
                } else {
                    parentCard.classList.remove('active-card');
                }
            }

            let selectedCount = 0;
            let totalPoin = 0;

            uniformCheckboxes.forEach(c => {
                if (c.checked) {
                    selectedCount++;
                    totalPoin += parseInt(c.getAttribute('data-poin') || 0);
                }
            });

            if (selectedCount > 0) {
                uniformSummaryBox.classList.remove('d-none');
                summarySelectedCount.textContent = selectedCount + ' Atribut Dicentang';
                summaryTotalPoin.textContent = '+' + totalPoin + ' Poin';
            } else {
                uniformSummaryBox.classList.add('d-none');
            }

            validateFormReady();
        });
    });

    function validateFormReady() {
        const hasStudent = siswaNisn.value !== '';
        if (inputMode.value === 'uniform_checklist') {
            const hasCheckedUniform = Array.from(uniformCheckboxes).some(c => c.checked);
            btnSubmit.disabled = !(hasStudent && hasCheckedUniform);
        } else {
            const hasViolation = selectJenisPelanggaran.value !== '';
            btnSubmit.disabled = !(hasStudent && hasViolation);
        }
    }

    const inputSearchSiswa = document.getElementById('inputSearchSiswa');
    const searchResultsList = document.getElementById('searchResultsList');
    const previewKelasBadge = document.getElementById('previewKelasBadge');
    let searchDebounceTimeout = null;

    // Live Instant Search Input Listener
    inputSearchSiswa.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(searchDebounceTimeout);

        if (query.length < 1) {
            searchResultsList.classList.add('d-none');
            searchResultsList.innerHTML = '';
            return;
        }

        searchDebounceTimeout = setTimeout(() => {
            fetch(`{{ route('api.siswa') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data.length > 0) {
                        searchResultsList.innerHTML = '';
                        res.data.forEach(siswa => {
                            const a = document.createElement('a');
                            a.href = 'javascript:void(0)';
                            a.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3';
                            const nisVal = siswa.nis || siswa.nisn;
                            a.innerHTML = `
                                <div>
                                    <strong class="text-black font-w700 fs-14 d-block mb-0" style="color: #000000 !important;">${siswa.nama}</strong>
                                    <small class="text-secondary fs-11">NIS: ${nisVal}</small>
                                </div>
                                <span class="badge bg-primary text-white font-w700 fs-12 px-3 py-1">${siswa.kelas}</span>
                            `;
                            a.addEventListener('click', function (e) {
                                e.preventDefault();
                                selectStudentBySearch(nisVal, siswa.nama, siswa.kelas);
                            });
                            searchResultsList.appendChild(a);
                        });
                        searchResultsList.classList.remove('d-none');
                    } else {
                        searchResultsList.innerHTML = '<div class="list-group-item text-muted fs-12 py-2 px-3">Tidak ada siswa ditemukan dengan kata kunci ini.</div>';
                        searchResultsList.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error(err);
                });
        }, 50);
    });

    // Close search dropdown on click outside
    document.addEventListener('click', function (e) {
        if (!inputSearchSiswa.contains(e.target) && !searchResultsList.contains(e.target)) {
            searchResultsList.classList.add('d-none');
        }
    });

    function selectStudentBySearch(nisn, nama, kelas) {
        // 1. Set Hidden Fields
        siswaNisn.value = nisn;
        siswaNama.value = nama;

        // 2. Auto-Select Kelas Dropdown
        selectKelas.value = kelas;

        // 3. Populate & Select Student Dropdown
        selectSiswa.innerHTML = `<option value="${nisn}" selected data-nama="${nama}" data-kelas="${kelas}">${nama} (NIS: ${nisn})</option>`;
        selectSiswa.disabled = false;

        // 4. Update Student Preview Card
        previewNama.textContent = nama;
        previewDetails.textContent = `NIS: ${nisn} | Kelas: ${kelas}`;
        if (previewKelasBadge) {
            previewKelasBadge.textContent = kelas;
        }
        studentPreviewCard.classList.remove('d-none');

        // 5. Update Search Input Text & Close Dropdown
        inputSearchSiswa.value = `${nama} (${kelas})`;
        searchResultsList.classList.add('d-none');

        validateFormReady();
    }

    // 1. Fetch Students when Class changes
    selectKelas.addEventListener('change', function () {
        const selectedKelas = this.value;
        selectSiswa.innerHTML = '<option value="">-- Loading data siswa... --</option>';
        selectSiswa.disabled = true;
        studentPreviewCard.classList.add('d-none');
        siswaNisn.value = '';
        siswaNama.value = '';
        validateFormReady();

        if (!selectedKelas) {
            selectSiswa.innerHTML = '<option value="">-- Silakan Pilih Kelas Terlebih Dahulu --</option>';
            return;
        }

        siswaLoading.classList.remove('d-none');

        fetch(`{{ route('api.siswa') }}?kelas=${encodeURIComponent(selectedKelas)}`)
            .then(response => response.json())
            .then(res => {
                siswaLoading.classList.add('d-none');
                if (res.success && res.data.length > 0) {
                    selectSiswa.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                    res.data.forEach(siswa => {
                        const option = document.createElement('option');
                        option.value = siswa.nisn;
                        option.setAttribute('data-nama', siswa.nama);
                        option.setAttribute('data-kelas', siswa.kelas);
                        option.textContent = `${siswa.nama} (NIS: ${siswa.nisn})`;
                        selectSiswa.appendChild(option);
                    });
                    selectSiswa.disabled = false;
                } else {
                    selectSiswa.innerHTML = '<option value="">-- Tidak ada siswa ditemukan di kelas ini --</option>';
                }
            })
            .catch(err => {
                siswaLoading.classList.add('d-none');
                selectSiswa.innerHTML = '<option value="">-- Gagal mengambil data siswa --</option>';
                console.error(err);
            });
    });

    // 2. On Student selection change
    selectSiswa.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            const nama = selectedOption.getAttribute('data-nama');
            const kelas = selectedOption.getAttribute('data-kelas');
            
            siswaNisn.value = this.value;
            siswaNama.value = nama;

            previewNama.textContent = nama;
            previewDetails.textContent = `NIS: ${this.value} | Kelas: ${kelas}`;
            if (previewKelasBadge) {
                previewKelasBadge.textContent = kelas;
            }
            studentPreviewCard.classList.remove('d-none');
        } else {
            siswaNisn.value = '';
            siswaNama.value = '';
            studentPreviewCard.classList.add('d-none');
        }
        validateFormReady();
    });

    // 3. On Violation selection change (Standard Mode)
    selectJenisPelanggaran.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            const poin = selectedOption.getAttribute('data-poin');
            const kategori = selectedOption.getAttribute('data-kategori');
            const sanksi = selectedOption.getAttribute('data-sanksi');

            previewKategoriBadge.textContent = kategori;
            previewNamaPelanggaran.textContent = selectedOption.textContent;
            previewSanksi.textContent = sanksi;
            previewPoinBadge.textContent = `+${poin} Poin`;

            violationPreviewCard.classList.remove('d-none');
        } else {
            violationPreviewCard.classList.add('d-none');
        }
        validateFormReady();
    });
});
</script>
@endpush
