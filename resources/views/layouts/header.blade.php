<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="{{ route('dashboard') }}" class="brand-logo d-flex align-items-center">
        <div class="logo-abbr d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; min-width: 38px;">
            <img src="{{ asset('assets/images/logo-smk.png') }}" alt="Logo SMK" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <div class="brand-title d-flex align-items-center">
            <img src="{{ asset('assets/images/disiplin-logo-full.png') }}" alt="DISIPLIN" style="max-height: 42px; width: auto; max-width: 175px; object-fit: contain;">
        </div>
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>
<!--**********************************
    Nav header end
***********************************-->

<!--**********************************
    Header start
***********************************-->
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar">
                        @yield('title', 'Dashboard')
                    </div>
                </div>
                <ul class="navbar-nav header-right d-flex align-items-center ms-auto">
                    <li class="nav-item dropdown header-profile me-3">
                        <a class="nav-link p-0 border-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent !important; padding: 0 !important; cursor: pointer;">
                            <div class="profile-card-pill d-flex align-items-center" style="background: #ffffff; border: 1px solid #e2e8f0; padding: 5px 14px 5px 6px; border-radius: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s ease;">
                                <div class="profile-avatar" style="background: linear-gradient(135deg, #1E33F2, #1224be); color: #ffffff; width: 34px; height: 34px; min-width: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; margin-right: 10px; box-shadow: 0 2px 4px rgba(30, 51, 242, 0.25);">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="header-info d-none d-sm-block text-start" style="padding-left: 0; margin-left: 0; margin-right: 10px;">
                                    <span class="d-block" style="font-weight: 700; font-size: 12.5px; line-height: 1.3; white-space: nowrap; color: #1e293b;">{{ Auth::user()->name }}</span>
                                    <small class="d-block" style="font-weight: 500; font-size: 10.5px; line-height: 1.2; white-space: nowrap; color: #64748b; margin-top: 1px;">{{ Auth::user()->role == 'admin' ? 'Administrator System' : 'Wakasek Kesiswaan' }}</small>
                                </div>
                                <i class="fa fa-chevron-down" style="font-size: 10px; color: #94a3b8; margin-left: 2px;"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-0" style="border-radius: 14px; min-width: 230px; overflow: hidden; border: 1px solid #e2e8f0 !important;">
                            <div class="dropdown-header px-3 py-3 border-bottom" style="background-color: #f8fafc; border-top-left-radius: 14px; border-top-right-radius: 14px;">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge fw-semibold px-2 py-1 rounded" style="font-size: 10px; background-color: #eff6ff; color: #1E33F2; border: 1px solid #dbeafe;">
                                        {{ Auth::user()->role == 'admin' ? 'ADMINISTRATOR' : 'WAKASEK' }}
                                    </span>
                                </div>
                                <h6 class="mb-0 text-dark" style="font-weight: 700; font-size: 13px; line-height: 1.3;">{{ Auth::user()->name }}</h6>
                                <small class="text-muted d-block" style="font-size: 11px; margin-top: 2px;">{{ Auth::user()->email ?? Auth::user()->username }}</small>
                            </div>
                            <div class="p-1">
                                <form action="{{ route('logout') }}" method="POST" class="d-block m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 text-danger font-w600 fs-13 d-flex align-items-center" style="border-radius: 8px;">
                                        <i class="fa fa-right-from-bracket me-2 text-danger"></i>
                                        <span>Keluar (Logout)</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end
***********************************-->
