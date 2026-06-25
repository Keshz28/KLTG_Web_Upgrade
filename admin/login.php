<?php
/* ============================================================================
 *                                login.php
 *
 *   Admin login form.
 *
 *   Visual redesign (split hero / "Welcome Back" panel) based on the
 *   "Millineal" sign-in reference. IMPORTANT: the authentication contract is
 *   unchanged — this form still POSTs name="username", name="password" and the
 *   submit button name="login_user" to login.php, and surfaces validation via
 *   errors.php exactly as functions.php expects. Only the markup/styling changed.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('functions.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #1a1a1a;
            --muted: #6b7280;
            --line: #d8dadf;
            --field-bg: #ffffff;
            /* Swap this path to change the hero photo (relative to /admin/). */
            --hero-img: url("../assets/img/hero-fullscreen-bg.jpg");
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            color: var(--ink);
            background: #fff;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ---------- Left hero ---------- */
        .login-hero {
            position: relative;
            flex: 0 0 56%;
            max-width: 56%;
            background: var(--hero-img) center/cover no-repeat;
            overflow: hidden;
        }
        .login-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10,20,28,.35) 0%, rgba(10,20,28,.55) 100%);
        }
        .login-hero-inner {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 64px;
            color: #fff;
        }
        .login-hero-inner h2 {
            font-weight: 800;
            font-size: clamp(28px, 3.4vw, 48px);
            line-height: 1.1;
            letter-spacing: -.5px;
            margin: 0;
            max-width: 16ch;
            text-shadow: 0 2px 24px rgba(0,0,0,.35);
        }
        .login-hero-footer {
            position: absolute;
            z-index: 2;
            left: 64px;
            right: 110px;
            bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
        }
        .login-hero-footer a { color: #fff; text-decoration: none; opacity: .92; }
        .login-hero-footer a:hover { opacity: 1; }
        .login-hero-footer .brand i { margin-right: 8px; }
        .login-hero-footer .activities { display: inline-flex; align-items: center; gap: 10px; }

        /* thin decorative rings, bottom-left of hero */
        .hero-rings {
            position: absolute;
            z-index: 2;
            left: -60px;
            bottom: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.45);
        }
        .hero-rings::before,
        .hero-rings::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.35);
        }
        .hero-rings::before { inset: 22px; }
        .hero-rings::after  { inset: 44px; }

        /* ---------- Curved divider ---------- */
        .login-wave {
            position: absolute;
            top: 0;
            left: -118px;
            width: 120px;
            height: 100%;
            z-index: 3;
            pointer-events: none;
        }
        .login-wave svg { display: block; width: 100%; height: 100%; }

        /* ---------- Right panel ---------- */
        .login-panel {
            position: relative;
            flex: 1 1 44%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            overflow: hidden;
        }
        .login-menu {
            position: absolute;
            top: 34px;
            right: 40px;
            font-size: 22px;
            color: var(--ink);
            background: none;
            border: 0;
            cursor: pointer;
            line-height: 1;
        }

        .login-form { width: 100%; max-width: 360px; }
        .login-form h1 {
            font-weight: 800;
            font-size: 42px;
            line-height: 1.05;
            letter-spacing: -1px;
            margin: 0 0 32px;
        }

        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--ink);
        }
        .field .control { position: relative; }
        .field input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            font-size: 15px;
            color: var(--ink);
            background: var(--field-bg);
            border: 1px solid var(--line);
            border-radius: 10px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: inherit;
        }
        .field input::placeholder { color: #9aa0a6; }
        .field input:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(26,26,26,.08);
        }
        .field .toggle-pw {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: none;
            border: 0;
            color: #9aa0a6;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 26px;
            font-size: 14px;
        }
        .login-options label { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); cursor: pointer; margin: 0; font-weight: 500; }
        .login-options input { width: 16px; height: 16px; accent-color: var(--ink); }
        .login-options a { color: var(--ink); text-decoration: none; font-weight: 600; }
        .login-options a:hover { text-decoration: underline; }

        .btn-primary-dark {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: 10px;
            background: var(--ink);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s, transform .05s;
        }
        .btn-primary-dark:hover { background: #000; }
        .btn-primary-dark:active { transform: translateY(1px); }

        /* decorative rings + quarter circle, bottom-right of panel */
        .panel-deco {
            position: absolute;
            right: -70px;
            bottom: -70px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 1px solid rgba(26,26,26,.18);
            z-index: 1;
            pointer-events: none;
        }
        .panel-deco::before,
        .panel-deco::after {
            content: "";
            position: absolute;
            border-radius: 50%;
        }
        .panel-deco::before { inset: 18px; border: 1px solid rgba(26,26,26,.18); }
        .panel-deco::after  { inset: 40px; background: var(--ink); }

        .alert-box {
            background: #fff8e6;
            border: 1px solid #f3d889;
            color: #7a5b00;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
        }
        /* errors.php renders an .alert.alert-danger style block */
        .login-form .alert-danger,
        .login-form .error {
            background: #fdecec;
            border: 1px solid #f3b4b4;
            color: #a12626;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
            list-style: none;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 991.98px) {
            .login-wrapper { flex-direction: column; }
            .login-hero { flex-basis: 220px; max-width: 100%; }
            .login-hero-inner { padding: 28px 28px; justify-content: flex-end; }
            .login-hero-inner h2 { font-size: 24px; }
            .login-hero-footer, .hero-rings { display: none; }
            .login-wave { display: none; }
            .login-panel { padding: 36px 24px 56px; }
            .login-form h1 { font-size: 34px; }
        }
    </style>

</head>

<body>

    <div class="login-wrapper">

        <!-- ===================== Left hero ===================== -->
        <section class="login-hero">
            <div class="login-hero-inner">
                <h2>KL The Guide — your city, beautifully managed in one place.</h2>
            </div>
            <div class="hero-rings"></div>
            <div class="login-hero-footer">
                <span class="brand"><i class="fab fa-instagram"></i> KL The Guide</span>
                <a class="activities" href="https://kltheguide.com.my" target="_blank" rel="noopener">
                    Visit the site <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- ===================== Right panel ===================== -->
        <section class="login-panel">

            <!-- Curved divider (sits over the hero seam) -->
            <div class="login-wave" aria-hidden="true">
                <svg viewBox="0 0 120 1000" preserveAspectRatio="none">
                    <path fill="#ffffff"
                        d="M120,0 L70,0
                           C 110,130 20,210 65,330
                           C 110,450 20,540 65,660
                           C 110,780 25,860 70,1000
                           L120,1000 Z" />
                </svg>
            </div>

            <button type="button" class="login-menu" aria-label="Menu"><i class="fas fa-bars"></i></button>

            <form class="login-form user" method="post" action="login.php">

                <h1>Welcome<br>Back</h1>

                <?php if (!empty($_GET['timeout'])): ?>
                <div class="alert-box" role="alert">
                    Your session expired after 30 minutes of inactivity. Please log in again.
                </div>
                <?php endif; ?>

                <?php include('errors.php'); ?>

                <div class="field">
                    <label for="inputUsername">Username</label>
                    <div class="control">
                        <input type="text" id="inputUsername" name="username"
                            placeholder="Enter your username" autocomplete="username">
                    </div>
                </div>

                <div class="field">
                    <label for="inputPassword">Password</label>
                    <div class="control">
                        <input type="password" id="inputPassword" name="password"
                            placeholder="Enter your password" autocomplete="current-password">
                        <button type="button" class="toggle-pw" onclick="togglePw()" aria-label="Show password">
                            <i id="pwIcon" class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="login-options">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#">Forgot Password</a>
                </div>

                <button type="submit" name="login_user" class="btn-primary-dark">Sign in</button>

            </form>

            <div class="panel-deco" aria-hidden="true"></div>

        </section>

    </div>

    <script>
        function togglePw() {
            var input = document.getElementById('inputPassword');
            var icon = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>

</body>

</html>
