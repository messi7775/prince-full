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
        <article class="kpi-card blue"><div class="kpi-icon">↗</div><div class="kpi-content"><span>مبيعات اليوم</span><strong><?= money($kpis['sales_today']) ?></strong></div></article>
        <article class="kpi-card green"><div class="kpi-icon">▦</div><div class="kpi-content"><span>مبيعات الشهر</span><strong><?= money($kpis['sales_month']) ?></strong></div></article>
        <article class="kpi-card purple"><div class="kpi-icon">♧</div><div class="kpi-content"><span>تحصيلات اليوم</span><strong><?= money($kpis['collections_today']) ?></strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">⚠</div><div class="kpi-content"><span>ديون الموزعين</span><strong><?= money($kpis['distributor_debt']) ?></strong></div></article>
        <article class="kpi-card teal"><div class="kpi-icon">▣</div><div class="kpi-content"><span>رصيد الصندوق</span><strong><?= money($kpis['cash_balance']) ?></strong></div></article>
        <article class="kpi-card amber"><div class="kpi-icon">▦</div><div class="kpi-content"><span>قيمة المخزون</span><strong><?= money($kpis['inventory_value']) ?></strong></div></article>
        <article class="kpi-card violet"><div class="kpi-icon">♧</div><div class="kpi-content"><span>عدد الموزعين</span><strong><?= int_num($kpis['distributors_count']) ?></strong></div></article>
        <article class="kpi-card blue"><div class="kpi-icon">◇</div><div class="kpi-content"><span>عدد الباقات</span><strong><?= int_num($kpis['packages_count']) ?> <small>(<?= int_num($kpis['packages_active']) ?> نشطة)</small></strong></div></article>
        <article class="kpi-card amber"><div class="kpi-icon">↓</div><div class="kpi-content"><span>سحوبات المالك</span><strong><?= money($kpis['owner_withdrawals']) ?></strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">▣</div><div class="kpi-content"><span>إجمالي المصروفات</span><strong><?= money($kpis['expenses_total']) ?></strong></div></article>
        <article class="kpi-card pink"><div class="kpi-icon">↓</div><div class="kpi-content"><span>مدفوعات الصندوق</span><strong><?= money($kpis['cash_payments_out']) ?></strong></div></article>
        <article class="kpi-card teal"><div class="kpi-icon">↗</div><div class="kpi-content"><span>مقبوضات الصندوق</span><strong><?= money($kpis['cash_receipts_in']) ?></strong></div></article>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>العمليات الأخيرة</h3><span>⌁</span></div>
        <?php if (empty($operations)): ?>
            <div class="empty-state">لا توجد عمليات حتى الآن</div>
        <?php else: ?>
            <?php foreach ($operations as $op): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($op['distributor_name'] ?? 'نقدي') ?></strong>
                        <small><?= e($op['created_at'] ?? '') ?></small>
                    </div>
                    <span class="positive"><?= money($op['total'] ?? 0) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>حالة المخزون</h3><span>◇</span></div>
        <?php if (empty($inventory)): ?>
            <div class="empty-state">لا يوجد مخزون</div>
        <?php else: ?>
            <?php foreach ($inventory as $row): ?>
                <div class="inventory-row">
                    <span><?= e($row['name']) ?> — <?= money($row['price']) ?></span>
                    <span class="stock-count <?= ((int)$row['quantity']) > 0 ? 'ok' : 'zero' ?>"><?= int_num($row['quantity']) ?></span>
                    <em><?= money($row['value']) ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
