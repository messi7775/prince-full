<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>التحصيلات</h2>
            <p>تحصيل دفعات الموزعين</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ تحصيل جديد</summary>
            <form method="post" action="/payments/store" class="entity-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>الموزع
                        <select name="distributor_id" required>
                            <option value="">— اختر —</option>
                            <?php foreach ($distributors as $d): ?>
                                <?php
                                    $credit      = (int)$d['credit_total'];
                                    $installment = (int)$d['installment_total'];
                                    $installmentPaid = (int)$d['installment_paid'];
                                    $paid_total  = (int)$d['paid_total'];
                                    $settled     = $installmentPaid + $paid_total;
                                    $bal         = $credit + $installment - $settled;
                                ?>
                                <option value="<?= (int)$d['id'] ?>"><?= e($d['name']) ?> — مستحق: <?= money($bal) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>المبلغ (ريال)<input name="amount" type="number" min="1" required></label>
                    <label>ملاحظة<input name="note" placeholder="اختياري"></label>
                </div>
                <button class="btn primary" type="submit">تسجيل التحصيل</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>سجل التحصيلات</h3><span>♣</span></div>
        <?php if (empty($payments)): ?>
            <div class="empty-state">لا توجد تحصيلات حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>التاريخ</th><th>الموزع</th><th>المبلغ</th><th>ملاحظة</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= ar_date($p['created_at']) ?></td>
                        <td><?= e($p['distributor_name'] ?? '') ?></td>
                        <td><?= money($p['amount']) ?></td>
                        <td><?= e($p['note'] ?? '') ?></td>
                        <td class="actions-cell">
                            <form method="post" action="/payments/delete" class="inline-form" onsubmit="return confirm('حذف هذا التحصيل؟')">
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
