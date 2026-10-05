<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الموزعون</h2>
            <p>إدارة الموزعين وأرصدتهم</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>قائمة الموزعين</h3><span>♙</span></div>
        <?php if (empty($distributors)): ?>
            <div class="empty-state">لا يوجد موزعون حتى الآن</div>
        <?php else: ?>
            <?php foreach ($distributors as $d): ?>
                <div class="inventory-row">
                    <span><?= e($d['name']) ?></span>
                    <span class="stock-count ok"><?= e($d['phone'] ?? '') ?></span>
                    <em><?= money($d['balance'] ?? 0) ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
