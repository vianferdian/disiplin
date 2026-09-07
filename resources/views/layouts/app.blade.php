<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — DISIPLIN</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    
    <!-- Template Icon Fonts -->
    <link href="{{ asset('assets/icons/flaticon/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/flaticon-1/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/line-awesome/css/line-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Stylesheets -->
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    
    @stack('styles')

    <style>
        /* === DISIPLIN COMPACT SIDEBAR & MOBILE RESPONSIVE === */

        /* Desktop Sidebar & Compact Header Sizing (1200px and above) - Normal State */
        @media only screen and (min-width: 1200px) {
            #main-wrapper:not(.menu-toggle) .nav-header {
                width: 15rem !important;
                height: 4.25rem !important;
            }
            #main-wrapper:not(.menu-toggle) .nav-header .brand-logo {
                padding-left: 1.25rem !important;
                justify-content: flex-start !important;
            }
            #main-wrapper:not(.menu-toggle) .nav-header .logo-abbr {
                display: none !important;
            }
            #main-wrapper:not(.menu-toggle) .nav-header .brand-title {
                display: flex !important;
            }
            #main-wrapper:not(.menu-toggle) .deznav {
                width: 15rem !important;
                top: 4.25rem !important;
                height: calc(100% - 4.25rem) !important;
            }
            #main-wrapper:not(.menu-toggle) .header {
                padding-left: 15rem !important;
                height: 4.25rem !important;
            }
            #main-wrapper:not(.menu-toggle) .content-body {
                margin-left: 15rem !important;
            }
            #main-wrapper:not(.menu-toggle) .footer {
                padding-left: 15rem !important;
            }

            /* Desktop Sidebar Sizing (1200px and above) - Collapsed / Mini State (.menu-toggle) */
            #main-wrapper.menu-toggle .nav-header {
                width: 5rem !important;
                height: 4.25rem !important;
            }
            #main-wrapper.menu-toggle .nav-header .brand-title {
                display: none !important;
            }
            #main-wrapper.menu-toggle .nav-header .logo-abbr {
                display: flex !important;
                margin-right: 0 !important;
            }
            #main-wrapper.menu-toggle .nav-header .brand-logo {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            #main-wrapper.menu-toggle .deznav {
                width: 5rem !important;
                top: 4.25rem !important;
                height: calc(100% - 4.25rem) !important;
                overflow: visible !important;
            }
            #main-wrapper.menu-toggle .deznav .metismenu > li > a {
                padding: 0.8rem 1rem !important;
                justify-content: center !important;
            }
            #main-wrapper.menu-toggle .deznav .metismenu > li > a .nav-text {
                display: none !important;
            }
            #main-wrapper.menu-toggle .deznav .metismenu > li > a i {
                margin-right: 0 !important;
                font-size: 1.25rem !important;
            }
            #main-wrapper.menu-toggle .deznav .metismenu .nav-label {
                display: none !important;
            }
            #main-wrapper.menu-toggle .header {
                padding-left: 5rem !important;
                height: 4.25rem !important;
            }
            #main-wrapper.menu-toggle .content-body {
                margin-left: 5rem !important;
            }
            #main-wrapper.menu-toggle .footer {
                padding-left: 5rem !important;
            }
        }

        /* Mobile & Tablet Responsive Layout (Below 1200px) */
        @media only screen and (max-width: 1199px) {
            .nav-header {
                width: 5rem !important;
                height: 4.25rem !important;
                position: fixed !important;
                z-index: 1001 !important;
            }
            .nav-header .brand-title {
                display: none !important;
            }
            .nav-header .logo-abbr {
                display: flex !important;
                margin-right: 0 !important;
            }
            .nav-header .brand-logo {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            .nav-control {
                right: -2.5rem !important;
            }
            .header {
                padding-left: 5rem !important;
                height: 4.25rem !important;
            }
            .header .header-content {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .header-left .dashboard_bar {
                font-size: 15px !important;
            }
            .content-body {
                margin-left: 0 !important;
                padding-top: 4.25rem !important;
            }
            .footer {
                padding-left: 0 !important;
            }
            .deznav {
                top: 4.25rem !important;
                width: 15rem !important;
                z-index: 1000 !important;
            }
        }

        /* Extra Small Mobile Screen Adjustments (< 575px) */
        @media only screen and (max-width: 575px) {
            .content-body {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .card-body {
                padding: 14px 12px !important;
            }
            .page-titles {
                margin-bottom: 12px !important;
                padding: 12px !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 8px !important;
            }
            .welcome-text h4 {
                font-size: 16px !important;
            }
            .welcome-text p {
                font-size: 11px !important;
            }
            .header-left .dashboard_bar {
                font-size: 14px !important;
                max-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .profile-avatar {
                width: 34px !important;
                height: 34px !important;
                font-size: 14px !important;
            }
        }

        /* Nav label (section titles) - compact */
        .deznav .metismenu .nav-label {
            font-size: 10px !important;
            padding: 8px 16px 3px !important;
            letter-spacing: 0.8px;
        }

        /* Menu links - tighter spacing, flexbox for horizontal alignment */
        .deznav .metismenu > li > a {
            padding: 7px 16px !important;
            font-size: 12px !important;
            line-height: 1.4 !important;
            display: flex !important;
            align-items: center !important;
        }

        /* SVG icon inside links */
        .deznav .metismenu > li > a svg {
            width: 16px !important;
            height: 16px !important;
            margin-right: 7px !important;
            flex-shrink: 0;
        }

        /* FontAwesome icon inside links */
        .deznav .metismenu > li > a i {
            width: 18px !important;
            text-align: center;
            margin-right: 8px !important;
            flex-shrink: 0;
        }

        /* FIX: Keep FontAwesome Solid weight (900) on active - prevent template from changing icon */
        .deznav .metismenu > li.mm-active > a i,
        .deznav .metismenu > li:hover > a i,
        [data-sidebar-style=full][data-layout=vertical] .deznav .metismenu > li.mm-active > a i {
            font-weight: 900 !important;
        }

        /* FIX: nav-text stays on one line */
        .deznav .metismenu > li > a .nav-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        /* Hamburger Menu Icon Styling */
        .hamburger .line {
            background-color: #1E33F2 !important;
            height: 3px !important;
            border-radius: 2px !important;
        }

        /* Footer Compact Styling */
        .footer {
            padding: 8px 15px !important;
        }
        .footer .copyright p {
            font-size: 11px !important;
            margin-bottom: 0 !important;
        }

        /* Dashboard bar compact title */
        .header-left .dashboard_bar {
            font-size: 17px !important;
            font-weight: 700 !important;
        }

        /* Header Profile Pill Overrides */
        .header-profile .nav-link {
            background: transparent !important;
            padding: 0 !important;
        }
        .header-profile .nav-link:after {
            display: none !important;
        }
        .header-profile .header-info {
            padding-left: 0 !important;
        }
        .profile-card-pill:hover {
            border-color: #cbd5e1 !important;
            background-color: #f8fafc !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
        }
    </style>
</head>
<body class="show">

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" data-theme-version="light" data-layout="vertical" data-navheaderbg="color_1" data-headerbg="color_1" data-sidebar-style="full" data-sidebar-position="fixed" data-header-position="fixed" data-container-body-width="full">

        @include('layouts.header')
        @include('layouts.sidebar')

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                
                <!-- Flash Messages (Fallback Banners) -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                        <i class="fa fa-check-circle me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                        <i class="fa fa-exclamation-triangle me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                        <i class="fa fa-info-circle me-2"></i> <strong>Informasi:</strong> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                        <i class="fa fa-exclamation-circle me-2"></i> <strong>Terjadi Kesalahan Input:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        @include('layouts.footer')

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!-- Required vendors -->
    <script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/deznav-init.js') }}"></script>

    <script>
        // SweetAlert Toast helper
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{!! addslashes(session('success')) !!}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{!! addslashes(session('error')) !!}"
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: "{!! addslashes(session('info')) !!}"
            });
        @endif

        // Global SweetAlert Delete Confirmation Helper
        window.confirmDelete = function(e, message) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const form = e ? e.target.closest('form') : null;
            const msgText = message || 'Apakah Anda yakin ingin menghapus data ini?';

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: msgText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0',
                    confirmButton: 'btn btn-danger px-4 py-2 font-w600 me-2',
                    cancelButton: 'btn btn-light px-4 py-2 font-w600 text-dark'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });

            return false;
        };
    </script>

    @stack('scripts')
</body>
</html>
