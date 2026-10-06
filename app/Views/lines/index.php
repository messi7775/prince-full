<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الخطوط</h2>
            <p>إدارة الخطوط وأرصدتها</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ إضافة خط جديد</summary>
            <form method="post" action="/lines/store" class="entity-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>اسم الخط<input name="name" required></label>
                    <label>المزود<input name="provider" placeholder="اختياري"></label>
                    <label>ملاحظة<input name="note" placeholder="اختياري"></label>
                </div>
                <button class="btn primary" type="submit">حفظ</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>قائمة الخطوط</h3><span>⌁</span></div>
        <?php if (empty($lines)): ?>
            <div class="empty-state">لا توجد خطوط حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>الاسم</th><th>المزود</th><th>الرصيد</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($lines as $l): ?>
                    <tr>
                        <td><?= e($l['name']) ?></td>
                        <td><?= e($l['provider'] ?? '') ?></td>
                        <td><span class="badge <?= (int)$l['balance'] >= 0 ? 'ok' : 'zero' ?>"><?= money($l['balance']) ?></span></td>
                        <td class="actions-cell">
                            <form method="post" action="/lines/delete" class="inline-form" onsubmit="return confirm('حذف هذا الخط؟')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$l['id'] ?>">
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
