<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Klinik Prima Medika</title>
    <!-- Bootstrap 5 (lightweight utilities) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Inter font – clean, modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;450;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ——————————————————————————————————————————————————————————
           NOTION FOUNDATION + FRESH BLUEISH ACCENT – NOT PLAIN.
           Clean, light but with personality. Inputs 100% working.
           All original functions preserved (loading, icon focus, etc).
           —————————————————————————————————————————————————————————— */
        :root {
            /* New blueish accent palette – calm, professional, friendly */
            --brand-bg: #f9fcff;         /* very light blue-tinted page background */
            --brand-card: #ffffff;
            --brand-accent: #2b6c9e;     /* sophisticated denim blue – not boring */
            --brand-accent-light: #e5f0fa;
            --brand-accent-soft: #d9e9f5;
            --brand-border: #e2e9f0;
            --brand-border-hover: #b8ccdb;
            --brand-text-dark: #1e2b3c;
            --brand-text-medium: #3e4f62;
            --brand-text-light: #6a7e92;
            --brand-gray-bg: #f3f7fb;
            --brand-shadow-sm: 0 4px 10px rgba(34, 84, 122, 0.02), 0 1px 2px rgba(0,0,0,0.02);
            --brand-shadow-md: 0 12px 30px rgba(0,60,110,0.04);
            --brand-radius-card: 24px;
            --brand-radius-field: 12px;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--brand-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            color: var(--brand-text-dark);
            line-height: 1.5;
        }

        /* gentle animated wave – very subtle, adds life without being "AI" */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(43, 108, 158, 0.02) 0%, transparent 40%),
                        radial-gradient(circle at 90% 70%, rgba(43, 108, 158, 0.02) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        .login-container {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 15;
        }

        /* ----- CARD: crisp white, soft blue border, lifted shadow ----- */
        .login-card {
            background: white;
            border-radius: var(--brand-radius-card);
            padding: 44px 40px;
            box-shadow: var(--brand-shadow-md);
            border: 1px solid rgba(43, 108, 158, 0.08);
            backdrop-filter: none;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .login-card:hover {
            border-color: rgba(43, 108, 158, 0.15);
            box-shadow: 0 18px 36px rgba(43, 108, 158, 0.06);
        }

        /* ----- LOGO: soft blue circle, refined depth ----- */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(145deg, #f6fbfe, white);
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border: 1px solid rgba(43, 108, 158, 0.15);
            box-shadow: 0 6px 14px rgba(43, 108, 158, 0.04);
            transition: all 0.2s;
        }

        .logo-icon i {
            font-size: 34px;
            color: var(--brand-accent);
        }

        .logo-section h1 {
            font-size: 23px;
            font-weight: 620;
            letter-spacing: -0.018em;
            color: #1a2c3b;
            margin-bottom: 6px;
        }

        .logo-section p {
            font-size: 15px;
            font-weight: 400;
            color: var(--brand-text-light);
            margin: 0;
        }

        /* ————————————————————————————————————
           INPUT FIELDS – FULLY FUNCTIONAL.
           Refined blue accent on focus.
           ———————————————————————————————————— */
        .form-label {
            font-size: 13px;
            font-weight: 550;
            color: var(--brand-text-medium);
            margin-bottom: 6px;
            display: block;
            letter-spacing: -0.01em;
        }

        .input-group {
            position: relative;
            display: block !important;
            width: 100%;
        }

        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #92a6b7;
            font-size: 15px;
            z-index: 15;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control {
            width: 100% !important;
            border: 1.5px solid #e4ecf2;
            border-radius: var(--brand-radius-field);
            padding: 14px 18px 14px 50px !important;
            font-size: 15px;
            font-weight: 420;
            background-color: white;
            color: var(--brand-text-dark);
            transition: border 0.2s, box-shadow 0.2s, background 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.01);
            height: auto;
            line-height: 1.5;
            display: block;
        }

        .form-control::placeholder {
            color: #b1c3d0;
            font-weight: 380;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--brand-accent);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(43, 108, 158, 0.08);
            outline: none;
        }

        /* icon turns brand blue on focus */
        .form-control:focus + i,
        .input-group .form-control:focus ~ i {
            color: var(--brand-accent) !important;
        }

        /* ----- Checkbox – fresh blue accent ----- */
        .form-check {
            padding-left: 0;
            margin: 28px 0 24px;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1.5px solid var(--brand-border-hover);
            border-radius: 6px;
            background-color: white;
            margin: 0 10px 0 0;
            transition: all 0.15s;
            cursor: pointer;
            float: none;
            flex-shrink: 0;
        }

        .form-check-input:checked {
            background-color: var(--brand-accent);
            border-color: var(--brand-accent);
        }

        .form-check-label {
            font-size: 14px;
            font-weight: 420;
            color: var(--brand-text-medium);
            cursor: pointer;
            user-select: none;
        }

        /* ----- BUTTON: fresh blue gradient, lively but classy ----- */
        .btn-login {
            background: linear-gradient(105deg, #2b6c9e 0%, #3787b8 100%);
            border: none;
            color: white;
            padding: 14px 20px;
            width: 100%;
            border-radius: 14px;
            font-weight: 560;
            font-size: 15.5px;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(43, 108, 158, 0.15);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: -0.01em;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-login:hover {
            background: linear-gradient(105deg, #2a6190 0%, #317cab 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(43, 108, 158, 0.2);
        }

        .btn-login:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(43, 108, 158, 0.2);
        }

        .btn-login i {
            font-size: 15px;
        }

        /* ----- ALERT – refined red with blue undertone? keep clean ----- */
        .alert {
            border: none;
            border-radius: 14px;
            padding: 14px 20px;
            margin-bottom: 30px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background-color: #fff4f0;
            color: #753b2b;
            border-left: 4px solid #c24a30;
        }

        .alert i {
            font-size: 16px;
            color: #c24a30;
        }

        /* ----- FOOTER – soft blue-gray borders, badge with blue ----- */
        .footer-text {
            text-align: center;
            margin-top: 16px;
            padding-top: 24px;
            border-top: 1px solid #eaf0f6;
        }

        .footer-text small {
            font-size: 12.7px;
            color: var(--brand-text-light);
            display: block;
            font-weight: 400;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #f2f8fd;
            padding: 9px 22px;
            border-radius: 60px;
            font-size: 12.8px;
            color: var(--brand-text-medium);
            margin-top: 16px;
            border: 1px solid #d3e3ee;
            transition: background 0.2s, border-color 0.2s;
            font-weight: 470;
        }

        .role-badge i {
            color: var(--brand-accent);
            opacity: 1;
            font-size: 13px;
        }

        .role-badge:hover {
            background: white;
            border-color: var(--brand-accent);
        }

        /* ----- loading animation – untouched ----- */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.85;
            background: #587a9c;
            box-shadow: none;
        }

        .btn-login.loading i {
            animation: spin 0.8s linear infinite;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }
            .logo-section h1 {
                font-size: 21px;
            }
        }

        /* small polish */
        .form-control {
            background-color: white !important;
        }
        .input-group {
            border: none;
        }
        /* subtle blueish focus ring */
        .form-control:focus {
            border-color: #2b6c9e;
            box-shadow: 0 0 0 4px rgba(43,108,158,0.08);
        }
    </style>
</head>
<body>
    <!-- clean, no floating blobs – just quiet radial hints of blue -->
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-hospital-user"></i> <!-- medical + fresh -->
                </div>
                <h1>Klinik Prima Medika</h1>
                <p>Selamat datang kembali</p>
            </div>
            
            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-circle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- FORM – INPUTS 100% WORKING (padding, icons, focus) -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="nama@email.com"
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password Anda"
                               required>
                    </div>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="remember" 
                           name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya selama 30 hari
                    </label>
                </div>
                
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    Masuk ke Dashboard
                </button>
                
                <div class="footer-text">
                    <small>
                        <i class="fas fa-shield me-1"></i>
                        Hak akses berdasarkan role pengguna
                    </small>
                    <div class="role-badge">
                        <i class="fas fa-user-doctor"></i>
                        Admin • Dokter • Kasir • Petugas
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ████████████████████████████████████████████████████████
        // 100% ORIGINAL FUNCTIONS – loading + icon focus.
        // Only color values updated to match new blue accent.
        // ████████████████████████████████████████████████████████

        // 1. LOADING STATE (exact same logic)
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner"></i> Memproses...';
        });

        // 2. FOCUS EFFECT – icon changes to brand blue (preserved)
        document.querySelectorAll('.input-group .form-control').forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = '#2b6c9e';   // brand blue
                }
            });
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = '#92a6b7';   // soft gray-blue
                }
            });
        });

        // Pre-fill icon color on load if focused
        window.addEventListener('load', function() {
            document.querySelectorAll('.form-control:focus').forEach(input => {
                const icon = input.parentElement.querySelector('i');
                if (icon) icon.style.color = '#2b6c9e';
            });
        });
    </script>

    <!-- 
        =========  DESIGN NOTES  =========
        - Blueish accent: #2b6c9e (denim blue) – present on logo, focus, button, badge.
        - Not plain: subtle radial gradients, refined borders, hover effects.
        - Card has soft blue border and lifted shadow.
        - Button uses fresh blue gradient, not flat black.
        - All original functions & backend code untouched.
        - Inputs fully clickable, no overlap, perfect padding.
        ===================================
    -->
</body>
</html>