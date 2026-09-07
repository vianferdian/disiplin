<!--**********************************
    Sidebar start
***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">MENU UTAMA</li>
            
            <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                <a class="ai-icon {{ request()->routeIs('dashboard') ? 'mm-active' : '' }}" href="{{ route('dashboard') }}" aria-expanded="false">
                    <i class="fa-solid fa-chart-line fs-18"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-label">KESISWAAN & PELANGGARAN</li>
            
            <li class="{{ request()->routeIs('pelanggaran-siswa.create') ? 'mm-active' : '' }}">
                <a class="ai-icon {{ request()->routeIs('pelanggaran-siswa.create') ? 'mm-active' : '' }}" href="{{ route('pelanggaran-siswa.create') }}" aria-expanded="false">
                    <i class="fa-solid fa-file-circle-plus fs-18"></i>
                    <span class="nav-text">Catat Pelanggaran</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('pelanggaran-siswa.index') || request()->routeIs('pelanggaran-siswa.show') ? 'mm-active' : '' }}">
                <a class="ai-icon {{ request()->routeIs('pelanggaran-siswa.index') ? 'mm-active' : '' }}" href="{{ route('pelanggaran-siswa.index') }}" aria-expanded="false">
                    <i class="fa-solid fa-clipboard-list fs-18"></i>
                    <span class="nav-text">Riwayat Pelanggaran</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('pelanggaran-siswa.report') ? 'mm-active' : '' }}">
                <a class="ai-icon {{ request()->routeIs('pelanggaran-siswa.report') ? 'mm-active' : '' }}" href="{{ route('pelanggaran-siswa.report') }}" aria-expanded="false">
                    <i class="fa-solid fa-file-invoice fs-18"></i>
                    <span class="nav-text">Rekapitulasi Poin</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('bank-data.*') ? 'mm-active' : '' }}">
                <a class="ai-icon {{ request()->routeIs('bank-data.*') ? 'mm-active' : '' }}" href="{{ route('bank-data.index') }}" aria-expanded="false">
                    <i class="fa-solid fa-database fs-18"></i>
                    <span class="nav-text">Integrasi Bank Data</span>
                </a>
            </li>

            @can('admin-access')
                <li class="nav-label">MASTER DATA (ADMIN)</li>
                
                <li class="{{ request()->routeIs('kategori-pelanggaran.*') ? 'mm-active' : '' }}">
                    <a class="ai-icon {{ request()->routeIs('kategori-pelanggaran.*') ? 'mm-active' : '' }}" href="{{ route('kategori-pelanggaran.index') }}" aria-expanded="false">
                        <i class="fa-solid fa-folder-tree fs-18"></i>
                        <span class="nav-text">Kategori Pelanggaran</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('jenis-pelanggaran.*') ? 'mm-active' : '' }}">
                    <a class="ai-icon {{ request()->routeIs('jenis-pelanggaran.*') ? 'mm-active' : '' }}" href="{{ route('jenis-pelanggaran.index') }}" aria-expanded="false">
                        <i class="fa-solid fa-layer-group fs-18"></i>
                        <span class="nav-text">Jenis Pelanggaran</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
                    <a class="ai-icon {{ request()->routeIs('users.*') ? 'mm-active' : '' }}" href="{{ route('users.index') }}" aria-expanded="false">
                        <i class="fa-solid fa-users-gear fs-18"></i>
                        <span class="nav-text">Manajemen User</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('settings.*') ? 'mm-active' : '' }}">
                    <a class="ai-icon {{ request()->routeIs('settings.*') ? 'mm-active' : '' }}" href="{{ route('settings.wakasek') }}" aria-expanded="false">
                        <i class="fa-solid fa-file-signature fs-18"></i>
                        <span class="nav-text">Pengaturan TTD Wakasek</span>
                    </a>
                </li>
            @endcan

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
