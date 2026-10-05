<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الخطوط</h2>
            <p>إدارة الخطوط وأرصدتها</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>الخطوط</h3><span>⌁</span></div>
        <?php if (empty($lines)): ?>
            <div class="empty-state">لا توجد خطوط حتى الآن</div>
        <?php else: ?>
            <?php foreach ($lines as $l): ?>
                <div class="inventory-row">
                    <span><?= e($l['name'] ?? '') ?></span>
                    <span class="stock-count ok"><?= e($l['provider'] ?? '') ?></span>
                    <em><?= money($l['balance'] ?? 0) ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
