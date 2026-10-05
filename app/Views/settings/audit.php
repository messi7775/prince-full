<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>سجل التدقيق</h2>
            <p>سجل العمليات المهمة في النظام</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>السجلات</h3><span>◴</span></div>
        <?php if (empty($entries)): ?>
            <div class="empty-state">لا توجد سجلات حتى الآن</div>
        <?php else: ?>
            <?php foreach ($entries as $row): ?>
                <div class="operation">
                    <div>
                        <strong><?= e($row['action'] ?? '') ?></strong>
                        <small><?= e($row['created_at'] ?? '') ?></small>
                    </div>
                    <em><?= e($row['description'] ?? '') ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
