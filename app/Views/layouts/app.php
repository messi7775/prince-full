<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b1224">
    <title><?= htmlspecialchars($pageTitle ?? 'لوحة التحكم') ?> | شبكة البرنس</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <?php require __DIR__ . '/topbar.php'; ?>

        <?= $content ?? '' ?>

        <nav class="mobile-bottom-nav" aria-label="التنقل السريع">
            <a href="/search"><span>⌕</span><small>البحث</small></a>
            <a href="/cash"><span>▥</span><small>الصندوق</small></a>
            <a href="/sales"><span>🛒</span><small>المبيعات</small></a>
            <a href="/packages"><span>◇</span><small>الباقات</small></a>
            <a class="active" href="/dashboard"><span>▦</span><small>الرئيسية</small></a>
        </nav>
    </main>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
