<?php
session_start();

// Include config.php file
require_once 'config.php';

// Included classes
require_once 'classes/db.php';
require_once 'classes/user.php';

$User = new User();

// Check if user already logged
if ($User->isLoggedIn())
    $User->redirect('index.php');

// Check for new users submit
if (isset($_POST['admin']['login'])) {

    $users = array(
        'email' => $_POST['admin']['email'],
        'username' => $_POST['admin']['email'],
        'password' => md5($_POST['admin']['password']),
    );

    if ($User->login($users)) {
        $User->redirect('index.php');
    } else {
        $login = '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> بيانات الدخول غير صحيحة.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background elements */
        .bg-shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.5;
            border-radius: 50%;
            z-index: 0;
            animation: float 10s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: #1a5ca8;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: #00b5c9;
            bottom: -50px;
            left: -100px;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 440px;
            z-index: 1;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-header h3 {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .shield-icon {
            font-size: 42px;
            color: #00b5c9;
            margin-bottom: 16px;
            display: inline-block;
            background: rgba(0, 181, 201, 0.1);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
        }

        .form-floating {
            margin-bottom: 20px;
            text-align: right;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            height: 56px;
            padding: 1rem 1.25rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #00b5c9;
            box-shadow: 0 0 0 0.25rem rgba(0, 181, 201, 0.25);
            color: white;
        }

        .form-floating > label {
            color: rgba(255, 255, 255, 0.6);
            padding: 1rem 1.25rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #00b5c9;
            transform: scale(.85) translateY(-.5rem) translateX(0.15rem);
        }

        .form-control::placeholder {
            color: transparent;
        }

        .btn-login {
            background: linear-gradient(135deg, #1a5ca8, #00b5c9);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 92, 168, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 92, 168, 0.4);
            background: linear-gradient(135deg, #154c8c, #0099ab);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border-radius: 12px;
            font-size: 14px;
            padding: 12px;
            margin-bottom: 24px;
        }

        .footer-text {
            margin-top: 32px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }

        @media (max-width: 576px) {
            .login-card {
                margin: 20px;
                padding: 32px 24px;
                border-radius: 20px;
            }
            .shape-1 { width: 300px; height: 300px; }
            .shape-2 { width: 200px; height: 200px; }
        }
    </style>
</head>

<body>
    <!-- Animated background shapes -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="login-card">
        <div class="login-header">
            <div class="shield-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h3>بوابة الإدارة</h3>
            <p>سجل الدخول للوصول إلى لوحة التحكم الآمنة</p>
        </div>

        <?php
        if (isset($login)) {
            echo $login;
        }
        ?>

        <form action="" method="post">
            <div class="form-floating">
                <input type="email" name="admin[email]" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">البريد الإلكتروني</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="admin[password]" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">كلمة المرور</label>
            </div>
            
            <button type="submit" class="btn btn-login" name="admin[login]">
                تسجيل الدخول <i class="bi bi-box-arrow-in-left ms-1"></i>
            </button>
        </form>

        <div class="footer-text">
            &copy; 2025 نظام الإدارة الآمن. جميع الحقوق محفوظة.
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>