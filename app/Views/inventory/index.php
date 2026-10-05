<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>المخزون</h2>
            <p>إدارة مخزون البطاقات</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>دفعات المخزون</h3><span>♧</span></div>
        <?php if (empty($items)): ?>
            <div class="empty-state">لا يوجد مخزون حتى الآن</div>
        <?php else: ?>
            <?php foreach ($items as $row): ?>
                <div class="inventory-row">
                    <span><?= e($row['package_name'] ?? '') ?></span>
                    <span class="stock-count ok"><?= int_num($row['quantity'] ?? 0) ?></span>
                    <em><?= money($row['unit_price'] ?? 0) ?></em>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
