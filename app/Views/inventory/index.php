<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>المخزون</h2>
            <p>إدارة دفعات مخزون البطاقات — بالشدات</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ إضافة دفعة مخزون</summary>
            <form method="post" action="/inventory/store" class="entity-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>الباقة
                        <select name="package_id" required>
                            <option value="">— اختر —</option>
                            <?php foreach ($packages as $p): ?>
                                <option value="<?= (int)$p['id'] ?>"><?= e($p['name']) ?> — شدة <?= money($p['bundle_price']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>عدد الشدات<input name="quantity" type="number" min="1" required></label>
                    <label>سعر الشدة (تكلفة)<input name="bundle_price" type="number" min="0" required></label>
                    <label>ملاحظة<input name="note" placeholder="اختياري"></label>
                </div>
                <button class="btn primary" type="submit">حفظ</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>دفعات المخزون</h3><span>♧</span></div>
        <?php if (empty($items)): ?>
            <div class="empty-state">لا يوجد مخزون حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>الباقة</th><th>عدد الشدات</th><th>سعر الشدة</th><th>القيمة</th><th>الحالة</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $row): ?>
                    <tr>
                        <td><?= e($row['package_name']) ?></td>
                        <td><?= int_num($row['quantity']) ?></td>
                        <td><?= money($row['bundle_price']) ?></td>
                        <td><?= money($row['quantity'] * $row['bundle_price']) ?></td>
                        <td><span class="badge <?= $row['status'] === 'active' ? 'ok' : 'zero' ?>"><?= $row['status'] === 'active' ? 'مفتوح' : 'مغلق' ?></span></td>
                        <td class="actions-cell">
                            <form method="post" action="/inventory/delete" class="inline-form" onsubmit="return confirm('حذف هذه الدفعة؟')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                <button class="btn sm danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
