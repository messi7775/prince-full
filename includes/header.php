<?php
$pageTitle = $pageTitle ?? 'لوحة التحكم';
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b1224">
    <title><?= htmlspecialchars($pageTitle) ?> | شبكة البرنس</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-side">
                <button class="icon-btn" type="button" aria-label="الوضع الداكن">☾</button>
                <button class="icon-btn notification-btn" type="button" aria-label="التنبيهات" data-notifications>♧<i>3</i></button>
            </div>

            <div class="topbar-brand">
                <strong>شبكة البرنس</strong>
                <span>⌘</span>
            </div>

            <button class="icon-btn menu-toggle" type="button" aria-label="فتح القائمة" data-menu-toggle>☰</button>
        </header>

