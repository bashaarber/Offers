<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sanivor Offers') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 32px 16px;
                background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%);
                position: relative;
                overflow: hidden;
            }

            /* Animated background orbs */
            body::before, body::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.15;
                animation: float 8s ease-in-out infinite;
            }
            body::before {
                width: 400px; height: 400px;
                background: #3b82f6;
                top: -100px; right: -100px;
            }
            body::after {
                width: 300px; height: 300px;
                background: #6366f1;
                bottom: -80px; left: -80px;
                animation-delay: 4s;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }

            .login-wrapper {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 420px;
            }

            .logo-section {
                text-align: center;
                margin-bottom: 36px;
            }

            .logo-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 72px;
                height: 72px;
                background: linear-gradient(135deg, #3b82f6, #6366f1);
                border-radius: 20px;
                margin-bottom: 20px;
                box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
            }

            .logo-icon svg { width: 36px; height: 36px; color: #fff; }

            .logo-title {
                font-size: 28px;
                font-weight: 800;
                color: #fff;
                letter-spacing: -0.5px;
            }

            .logo-title span { color: #60a5fa; }

            .logo-subtitle {
                margin-top: 6px;
                font-size: 14px;
                color: #94a3b8;
            }

            .login-card {
                background: rgba(255, 255, 255, 0.07);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                padding: 36px 32px;
                box-shadow: 0 24px 48px rgba(0, 0, 0, 0.3);
            }

            .form-group { margin-bottom: 20px; }

            .form-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: #cbd5e1;
                margin-bottom: 8px;
            }

            .input-wrapper {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #64748b;
                pointer-events: none;
            }

            .input-icon svg { width: 18px; height: 18px; }

            .form-input {
                width: 100%;
                padding: 12px 16px 12px 44px;
                background: rgba(255, 255, 255, 0.06);
                border: 1.5px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                color: #fff;
                font-size: 14px;
                font-family: inherit;
                transition: all 0.2s ease;
                outline: none;
            }

            .form-input::placeholder { color: #64748b; }

            .form-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
                background: rgba(255, 255, 255, 0.08);
            }

            .remember-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
            }

            .remember-label {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .remember-label input[type="checkbox"] {
                width: 16px;
                height: 16px;
                border-radius: 4px;
                border: 1.5px solid rgba(255,255,255,0.2);
                background: rgba(255,255,255,0.05);
                accent-color: #3b82f6;
                cursor: pointer;
            }

            .remember-label span {
                font-size: 13px;
                color: #94a3b8;
            }

            .forgot-link {
                font-size: 13px;
                color: #60a5fa;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }

            .forgot-link:hover { color: #93c5fd; }

            .login-btn {
                width: 100%;
                padding: 13px 24px;
                background: linear-gradient(135deg, #3b82f6, #6366f1);
                color: #fff;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            }

            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
            }

            .login-btn:active { transform: translateY(0); }

            .footer-text {
                text-align: center;
                margin-top: 32px;
                font-size: 12px;
                color: #475569;
            }

            .error-text {
                color: #f87171;
                font-size: 13px;
                margin-top: 6px;
            }

            .status-msg {
                background: rgba(34, 197, 94, 0.1);
                border: 1px solid rgba(34, 197, 94, 0.2);
                color: #4ade80;
                padding: 10px 16px;
                border-radius: 10px;
                font-size: 13px;
                margin-bottom: 20px;
            }

            .toggle-password {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #64748b;
                cursor: pointer;
                padding: 4px;
                transition: color 0.2s;
            }
            .toggle-password:hover { color: #94a3b8; }
            .toggle-password svg { width: 18px; height: 18px; }
        </style>
    </head>
    <body>
        <div class="login-wrapper">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h1 class="logo-title">Sanivor <span>Offers</span></h1>
                <p class="logo-subtitle">Offer Management System</p>
            </div>

            <!-- Card -->
            <div class="login-card">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="footer-text">&copy; {{ date('Y') }} Sanivor AG. All rights reserved.</p>
        </div>
    </body>
</html>
