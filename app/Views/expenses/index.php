<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>المصروفات</h2>
            <p>مصروفات الشبكة</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>المصروفات</h3><span>▣</span></div>
        <?php if (empty($expenses)): ?>
            <div class="empty-state">لا توجد مصروفات حتى الآن</div>
        <?php else: ?>
            <?php foreach ($expenses as $ex): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($ex['category'] ?? '') ?></strong>
                        <small><?= e($ex['created_at'] ?? '') ?></small>
                    </div>
                    <span class="positive" style="color:#e6466a"><?= money($ex['amount'] ?? 0) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
