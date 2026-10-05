<?php
declare(strict_types=1);

session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'أدخل البريد الإلكتروني وكلمة المرور بشكل صحيح.';
    } else {
        $stmt = $pdo->prepare('SELECT id, email, password_hash FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            header('Location: dashboard.php');
            exit;
        }

        $error = 'بيانات الدخول غير صحيحة.';
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111827">
    <title>تسجيل الدخول | نظام إدارة الكروت</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="brand login-brand">
            <div class="brand-mark">PN</div>
            <div>
                <strong>نظام إدارة الكروت</strong>
                <span>Prince Cards</span>
            </div>
        </div>

        <div class="login-heading">
            <h1>تسجيل الدخول</h1>
            <p>أدخل بيانات حساب المدير للمتابعة.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="on">
            <label for="email">البريد الإلكتروني</label>
            <input id="email" name="email" type="email" autocomplete="username" required>

            <label for="password">كلمة المرور</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <button class="btn primary full" type="submit">دخول</button>
        </form>
    </div>
</body>
</html>
