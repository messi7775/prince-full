<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'لوحة التحكم';
require __DIR__ . '/includes/header.php';
?>

<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>لوحة التحكم</h2>
            <p>مرحباً بك في شبكة البرنس</p>
        </div>
    </section>

    <section class="alerts-list" aria-label="التنبيهات">
        <div class="empty-alert">لا توجد تنبيهات حالياً</div>
    </section>

    <section class="kpi-grid">
        <article class="kpi-card blue"><div class="kpi-icon">↗</div><div class="kpi-content"><span>مبيعات اليوم</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card green"><div class="kpi-icon">▦</div><div class="kpi-content"><span>مبيعات الشهر</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card purple"><div class="kpi-icon">♧</div><div class="kpi-content"><span>تحصيلات اليوم</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">⚠</div><div class="kpi-content"><span>ديون الموزعين</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card teal"><div class="kpi-icon">▣</div><div class="kpi-content"><span>رصيد الصندوق</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card amber"><div class="kpi-icon">▦</div><div class="kpi-content"><span>قيمة المخزون</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card violet"><div class="kpi-icon">♧</div><div class="kpi-content"><span>عدد الموزعين</span><strong>0</strong></div></article>
        <article class="kpi-card blue"><div class="kpi-icon">◇</div><div class="kpi-content"><span>عدد الباقات</span><strong>0 <small>(0 نشطة)</small></strong></div></article>
        <article class="kpi-card amber"><div class="kpi-icon">↓</div><div class="kpi-content"><span>سحوبات المالك</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">▣</div><div class="kpi-content"><span>إجمالي المصروفات</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">↓</div><div class="kpi-content"><span>مدفوعات الصندوق</span><strong>0 ر.ي</strong></div></article>
        <article class="kpi-card teal"><div class="kpi-icon">↗</div><div class="kpi-content"><span>مقبوضات الصندوق</span><strong>0 ر.ي</strong></div></article>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>العمليات الأخيرة</h3><span>⌁</span></div>
        <div class="empty-state">لا توجد عمليات حتى الآن</div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>حالة المخزون</h3><span>◇</span></div>
        <div class="inventory-row"><span>1000 ريال</span><span class="stock-count zero">0</span><em>0 ر.ي</em></div>
        <div class="inventory-row"><span>500 ريال</span><span class="stock-count zero">0</span><em>0 ر.ي</em></div>
        <div class="inventory-row"><span>250 ريال</span><span class="stock-count zero">0</span><em>0 ر.ي</em></div>
        <div class="inventory-row"><span>200 ريال</span><span class="stock-count zero">0</span><em>0 ر.ي</em></div>
        <div class="inventory-row"><span>100 ريال</span><span class="stock-count zero">0</span><em>0 ر.ي</em></div>
    </section>
</div>

<nav class="mobile-bottom-nav" aria-label="التنقل السريع">
    <a href="#"><span>⌕</span><small>البحث</small></a>
    <a href="#"><span>▥</span><small>الصندوق</small></a>
    <a href="#"><span>🛒</span><small>المبيعات</small></a>
    <a href="#"><span>◇</span><small>الباقات</small></a>
    <a class="active" href="dashboard.php"><span>▦</span><small>الرئيسية</small></a>
</nav>

<?php require __DIR__ . '/includes/footer.php'; ?>
