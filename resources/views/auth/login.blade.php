<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — DISIPLIN System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url("{{ asset('assets/images/login-bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .minimal-login-card {
            background: #ffffff !important;
            border-radius: 22px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-top: 5px solid #1E33F2 !important;
            box-shadow: 0 25px 60px -15px rgba(30, 51, 242, 0.22), 0 15px 35px -10px rgba(15, 23, 42, 0.1) !important;
            width: 100%;
            max-width: 415px;
            padding: 42px 36px;
            position: relative;
            z-index: 10;
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            background: #1E33F2;
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .form-control-minimal {
            height: 44px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding-left: 38px;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }
        .form-control-minimal:focus {
            border-color: #1E33F2;
            box-shadow: 0 0 0 3px rgba(30, 51, 242, 0.12);
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }
        .btn-minimal {
            height: 44px;
            background: #1E33F2;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            transition: background 0.2s ease;
        }
        .btn-minimal:hover {
            background: #1626c7;
        }
    </style>
</head>

<body>
    <div class="minimal-login-card">
        <!-- Brand Header -->
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center mb-2">
                <img src="{{ asset('assets/images/logo-smk.png') }}" alt="Logo SMK" style="height: 52px; width: auto; object-fit: contain;">
            </div>
            <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">DISIPLIN</h4>
            <p class="text-muted fs-12 mb-0">Sistem Digitalisasi Pelanggaran & Kedisiplinan</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info py-2 fs-12 mb-3 rounded-3 border-0">
                <i class="fa fa-info-circle me-1"></i> {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 fs-12 mb-3 rounded-3 border-0">
                <i class="fa fa-exclamation-circle me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-dark font-w600 fs-12 mb-1">Username / Email</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-user"></i>
                    <input type="text" name="login" class="form-control form-control-minimal" placeholder="Username atau email" value="{{ old('login') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-dark font-w600 fs-12 mb-1">Password</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" class="form-control form-control-minimal" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check mb-0">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted fs-12" for="remember">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-minimal w-100 shadow-sm">
                Masuk ke Sistem <i class="fa fa-arrow-right ms-1 fs-12"></i>
            </button>
        </form>
    </div>
</body>
</html>
