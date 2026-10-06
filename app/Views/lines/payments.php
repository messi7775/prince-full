<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>تسديد الخطوط</h2>
            <p>سجل تسديدات الخطوط — دفع واستلام</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ تسديد خط جديد</summary>
            <form method="post" action="/line-payments/store" class="entity-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>الخط
                        <select name="line_id" required>
                            <option value="">— اختر —</option>
                            <?php foreach ($lines as $l): ?>
                                <option value="<?= (int)$l['id'] ?>"><?= e($l['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>المبلغ (ريال)<input name="amount" type="number" min="1" required></label>
                    <label>النوع
                        <select name="direction">
                            <option value="out">مدفوع (خرج)</option>
                            <option value="in">مستلم (دخل)</option>
                        </select>
                    </label>
                    <label>ملاحظة<input name="note" placeholder="اختياري"></label>
                </div>
                <button class="btn primary" type="submit">تسجيل التسديد</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>سجل تسديدات الخطوط</h3><span>▭</span></div>
        <?php if (empty($payments)): ?>
            <div class="empty-state">لا توجد تسديدات حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>التاريخ</th><th>الخط</th><th>المبلغ</th><th>النوع</th><th>ملاحظة</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= ar_date($p['created_at']) ?></td>
                        <td><?= e($p['line_name'] ?? '') ?></td>
                        <td><?= money($p['amount']) ?></td>
                        <td><?= $p['direction'] === 'out' ? 'خرج' : 'دخل' ?></td>
                        <td><?= e($p['note'] ?? '') ?></td>
                        <td class="actions-cell">
                            <form method="post" action="/line-payments/delete" class="inline-form" onsubmit="return confirm('حذف هذا التسديد؟')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
