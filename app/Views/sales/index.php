<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>المبيعات</h2>
            <p>عمليات بيع البطاقات</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>العمليات</h3><span>🛒</span></div>
        <?php if (empty($sales)): ?>
            <div class="empty-state">لا توجد عمليات بيع حتى الآن</div>
        <?php else: ?>
            <?php foreach ($sales as $s): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($s['distributor_name'] ?? 'نقدي') ?></strong>
                        <small><?= e($s['created_at'] ?? '') ?></small>
                    </div>
                    <span class="positive"><?= money($s['total'] ?? 0) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
