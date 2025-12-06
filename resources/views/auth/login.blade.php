<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('login_title') }} - Flux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-light: #F8FAFC;
            --surface-light: #FFFFFF;
            --primary-light: #004AAD;
            --secondary-light: #D62828;
            --accent-light: #00B4D8;
            --text-primary-light: #1E293B;
            --text-secondary-light: #475569;
            --border-light: #E2E8F0;
            
            --bg-dark: #0B1221;
            --surface-dark: #1A2235;
            --primary-dark: #4C8EF7;
            --secondary-dark: #FF5C5C;
            --accent-dark: #4FD1C5;
            --text-primary-dark: #E2E8F0;
            --text-secondary-dark: #94A3B8;
            --border-dark: #334155;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary-light);
            transition: background-color 0.3s, color 0.3s;
            min-height: 100vh;
        }
        
        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-primary-dark);
        }
        
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Navbar */
        .navbar {
            background-color: var(--surface-light);
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 0;
            transition: all 0.3s;
        }
        
        .dark-mode .navbar {
            background-color: var(--surface-dark);
            border-bottom-color: var(--border-dark);
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-light);
            text-decoration: none;
        }
        
        .dark-mode .navbar-brand { color: var(--primary-dark); }
        
        .theme-toggle, .lang-toggle {
            background: none;
            border: none;
            color: var(--text-primary-light);
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.3s;
            margin-left: 1rem;
            text-decoration: none;
        }
        
        .dark-mode .theme-toggle, .dark-mode .lang-toggle { color: var(--text-primary-dark); }
        .theme-toggle:hover, .lang-toggle:hover { color: var(--primary-light); }
        .dark-mode .theme-toggle:hover, .dark-mode .lang-toggle:hover { color: var(--primary-dark); }

        /* Login Section */
        .login-section {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            padding: 3rem 0;
            margin-top: 80px;
        }

        .login-container { max-width: 450px; margin: 0 auto; }

        .login-card {
            background-color: var(--surface-light);
            border: 1px solid var(--border-light);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .dark-mode .login-card {
            background-color: var(--surface-dark);
            border-color: var(--border-dark);
        }

        .login-header { text-align: center; margin-bottom: 2rem; }

        .login-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary-light);
            margin-bottom: 0.5rem;
        }

        .dark-mode .login-header h1 { color: var(--text-primary-dark); }

        .login-header p {
            color: var(--text-secondary-light);
            font-size: 1rem;
        }

        .dark-mode .login-header p { color: var(--text-secondary-dark); }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: var(--text-primary-light);
            margin-bottom: 0.5rem;
        }

        .dark-mode .form-label { color: var(--text-primary-dark); }

        .form-control {
            background-color: var(--bg-light);
            border: 1px solid var(--border-light);
            color: var(--text-primary-light);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .dark-mode .form-control {
            background-color: var(--bg-dark);
            border-color: var(--border-dark);
            color: var(--text-primary-dark);
        }

        .form-control:focus {
            background-color: var(--surface-light);
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(0, 74, 173, 0.1);
            color: var(--text-primary-light);
            outline: none;
        }

        .dark-mode .form-control:focus {
            background-color: var(--surface-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(76, 142, 247, 0.1);
            color: var(--text-primary-dark);
        }

        .is-invalid {
            border-color: var(--secondary-light) !important;
        }
        .dark-mode .is-invalid {
            border-color: var(--secondary-dark) !important;
        }

        .invalid-feedback {
            display: block;
            color: var(--secondary-light);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .dark-mode .invalid-feedback {
            color: var(--secondary-dark);
        }

        .password-input-wrapper { position: relative; }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary-light);
            cursor: pointer;
            padding: 0.25rem;
        }

        .dark-mode .password-toggle { color: var(--text-secondary-dark); }
        .password-toggle:hover { color: var(--primary-light); }
        .dark-mode .password-toggle:hover { color: var(--primary-dark); }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check { display: flex; align-items: center; }

        .form-check-input {
            width: 1.125rem;
            height: 1.125rem;
            margin-right: 0.5rem;
            cursor: pointer;
            border: 1px solid var(--border-light);
        }

        .dark-mode .form-check-input {
            border-color: var(--border-dark);
            background-color: var(--bg-dark);
        }

        .form-check-input:checked {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        .dark-mode .form-check-input:checked {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .form-check-label {
            color: var(--text-secondary-light);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .dark-mode .form-check-label { color: var(--text-secondary-dark); }

        .forgot-password {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .dark-mode .forgot-password { color: var(--primary-dark); }
        .forgot-password:hover { text-decoration: underline; }

        /* Button */
        .btn-primary-custom {
            background-color: var(--primary-light);
            color: white;
            padding: 0.875rem 2rem;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s;
            width: 100%;
            font-size: 1rem;
        }

        .btn-primary-custom:hover {
            background-color: #003a8c;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 74, 173, 0.3);
            color: white;
        }

        .dark-mode .btn-primary-custom {
            background-color: var(--primary-dark);
            color: var(--surface-dark);
        }

        .dark-mode .btn-primary-custom:hover {
            background-color: #6BA3FF;
            box-shadow: 0 8px 16px rgba(76, 142, 247, 0.3);
            color: var(--surface-dark);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-light);
        }

        .dark-mode .divider::before, .dark-mode .divider::after {
            border-bottom-color: var(--border-dark);
        }

        .divider span {
            padding: 0 1rem;
            color: var(--text-secondary-light);
            font-size: 0.875rem;
        }

        .dark-mode .divider span { color: var(--text-secondary-dark); }

        /* Social Buttons */
        .btn-social {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-light);
            background-color: var(--surface-light);
            color: var(--text-primary-light);
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
        }

        .dark-mode .btn-social {
            border-color: var(--border-dark);
            background-color: var(--surface-dark);
            color: var(--text-primary-dark);
        }

        .btn-social:hover {
            border-color: var(--primary-light);
            background-color: var(--bg-light);
        }

        .dark-mode .btn-social:hover {
            border-color: var(--primary-dark);
            background-color: var(--bg-dark);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary-light);
        }

        .dark-mode .login-footer { color: var(--text-secondary-dark); }

        .login-footer a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
        }

        .dark-mode .login-footer a { color: var(--primary-dark); }
        .login-footer a:hover { text-decoration: underline; }

        /* Alerts */
        .alert-custom {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }
        .alert-success {
            background-color: rgba(0, 180, 216, 0.1);
            border-color: var(--accent-light);
            color: var(--accent-light);
        }
        .dark-mode .alert-success {
            background-color: rgba(79, 209, 197, 0.1);
            border-color: var(--accent-dark);
            color: var(--accent-dark);
        }

        @media (max-width: 768px) {
            .login-card { padding: 2rem 1.5rem; }
            .login-header h1 { font-size: 1.75rem; }
            .form-options { flex-direction: column; gap: 1rem; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><i class="fas fa-wallet"></i> Flux</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('lang.switch', app()->getLocale() == 'en' ? 'id' : 'en') }}" class="lang-toggle" title="Switch Language">
                    <i class="fas fa-globe"></i>
                </a>
                <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <h1>{{ __('login_title') }}</h1>
                        <p>{{ __('login_subtitle') }}</p>
                    </div>

                    @if(session('success'))
                        <div class="alert-custom alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('label_email') }}</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email"
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('label_password') }}</label>
                            <div class="password-input-wrapper">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                <label class="form-check-label" for="rememberMe">
                                    {{ __('remember_me') }}
                                </label>
                            </div>
                            <a href="#" class="forgot-password">{{ __('forgot_password') }}</a>
                        </div>

                        <button type="submit" class="btn-primary-custom">{{ __('btn_login') }}</button>
                    </form>

                    <div class="divider">
                        <span>{{ __('divider_or') }}</span>
                    </div>

                    <button class="btn-social">
                        <i class="fab fa-google"></i>
                        <span>{{ __('btn_google') }}</span>
                    </button>

                    <button class="btn-social">
                        <i class="fab fa-github"></i>
                        <span>{{ __('btn_github') }}</span>
                    </button>

                    <div class="login-footer">
                        <span>{{ __('footer_text_login') }}</span>
                        <a href="{{ route('register') }}">{{ __('footer_link_signup') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode logic
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const isDark = localStorage.getItem('darkMode') === 'true';

        if (isDark) {
            body.classList.add('dark-mode');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDarkMode = body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
            themeToggle.innerHTML = isDarkMode ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });

        // Password toggle logic
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                togglePassword.querySelector('i').classList.toggle('fa-eye');
                togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    </script>
</body>
</html>