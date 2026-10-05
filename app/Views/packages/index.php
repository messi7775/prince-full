<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الباقات</h2>
            <p>إدارة باقات بطاقات الإنترنت</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>قائمة الباقات</h3><span>◇</span></div>
        <?php if (empty($packages)): ?>
            <div class="empty-state">لا توجد باقات حتى الآن</div>
        <?php else: ?>
            <?php foreach ($packages as $pkg): ?>
                <div class="inventory-row">
                    <span><?= e($pkg['name']) ?> — <?= money($pkg['price']) ?></span>
                    <span class="stock-count <?= ($pkg['status'] ?? '') === 'active' ? 'ok' : 'zero' ?>"><?= e($pkg['status'] ?? '') ?></span>
                    <em><?= e($pkg['duration'] ?? '') ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
