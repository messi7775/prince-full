<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>التحصيلات</h2>
            <p>تحصيل دفعات الموزعين</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>التحصيلات</h3><span>♧</span></div>
        <?php if (empty($payments)): ?>
            <div class="empty-state">لا توجد تحصيلات حتى الآن</div>
        <?php else: ?>
            <?php foreach ($payments as $p): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($p['distributor_name'] ?? '') ?></strong>
                        <small><?= e($p['created_at'] ?? '') ?></small>
                    </div>
                    <span class="positive"><?= money($p['amount'] ?? 0) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
