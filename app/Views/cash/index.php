<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الصندوق</h2>
            <p>الحركة النقدية</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>الرصيد الحالي</h3><span>▥</span></div>
        <div class="inventory-row">
            <span>رصيد الصندوق</span>
            <span class="stock-count ok"><?= money($balance) ?></span>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>الحركات</h3><span>▥</span></div>
        <?php if (empty($movements)): ?>
            <div class="empty-state">لا توجد حركات نقدية حتى الآن</div>
        <?php else: ?>
            <?php foreach ($movements as $m): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($m['reason'] ?? '') ?></strong>
                        <small><?= e($m['created_at'] ?? '') ?></small>
                    </div>
                    <span class="positive" style="color:<?= ($m['direction'] ?? '') === 'in' ? '#18a078' : '#e6466a' ?>"><?= money($m['amount'] ?? 0) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
