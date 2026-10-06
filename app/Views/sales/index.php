<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>المبيعات</h2>
            <p>بيع البطاقات بالشدة</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ عملية بيع جديدة</summary>
            <form method="post" action="/sales/store" class="entity-form" id="sale-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>الباقة
                        <select name="package_id" id="sale-package" required>
                            <option value="">— اختر —</option>
                            <?php foreach ($packages as $p): ?>
                                <option value="<?= (int)$p['id'] ?>" data-bundle-price="<?= (int)$p['bundle_price'] ?>"><?= e($p['name']) ?> — شدة <?= money($p['bundle_price']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>الموزع
                        <select name="distributor_id" id="sale-distributor" required>
                            <option value="">— اختر موزع —</option>
                            <?php foreach ($distributors as $d): ?>
                                <?php
                                    $credit      = (int)$d['credit_total'];
                                    $installment = (int)$d['installment_total'];
                                    $installmentPaid = (int)$d['installment_paid'];
                                    $paid_total  = (int)$d['paid_total'];
                                    $settled     = $installmentPaid + $paid_total;
                                    $bal         = $credit + $installment - $settled;
                                ?>
                                <option value="<?= (int)$d['id'] ?>"><?= e($d['name']) ?> — رصيد: <?= money($bal) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>عدد الشدات<input name="bundles_count" id="sale-bundles" type="number" min="1" required value="1"></label>
                    <label>سعر الشدة (ريال)<input name="bundle_price" id="sale-price" type="number" min="0" required></label>
                    <label>نوع الدفع
                        <select name="payment_type" id="sale-type">
                            <option value="cash">نقدي</option>
                            <option value="credit">آجل</option>
                            <option value="installment">تقسيط</option>
                        </select>
                    </label>
                    <label id="paid-amount-field" style="display:none">المبلغ المدفوع<input name="paid_amount" id="sale-paid" type="number" min="0" value="0"></label>
                    <label>ملاحظة<input name="note" placeholder="اختياري"></label>
                </div>
                <div class="sale-total">الإجمالي: <strong id="sale-total-display">0</strong> ريال</div>
                <div id="sale-remaining-display" style="display:none" class="sale-total">الباقي عليه: <strong>0</strong> ريال</div>
                <button class="btn primary" type="submit">تسجيل البيع</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>العمليات</h3><span>🛒</span></div>
        <?php if (empty($sales)): ?>
            <div class="empty-state">لا توجد عمليات بيع حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>التاريخ</th><th>الموزع</th><th>الباقة</th><th>الشدات</th><th>سعر(ش)</th><th>الإجمالي</th><th>المدفوع</th><th>المتبقي</th><th>نوع الدفع</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $s): ?>
                        <?php
                            $remaining = match($s['payment_type']) {
                                'cash'       => 0,
                                'credit'     => (int)$s['total'],
                                'installment'=> (int)$s['total'] - (int)$s['paid_amount'],
                            };
                            $typeLabel = match($s['payment_type']) {
                                'cash'       => 'نقدي',
                                'credit'     => 'آجل',
                                'installment'=> 'تقسيط',
                            };
                            $typeBadge = match($s['payment_type']) {
                                'cash'       => 'ok',
                                'installment'=> 'warn',
                                default      => 'zero',
                            };
                        ?>
                    <tr>
                        <td><?= ar_date($s['created_at']) ?></td>
                        <td><?= e($s['distributor_name'] ?? '') ?></td>
                        <td><?= e($s['package_name'] ?? '') ?></td>
                        <td><?= int_num($s['bundles_count']) ?></td>
                        <td><?= money($s['bundle_price']) ?></td>
                        <td><?= money($s['total']) ?></td>
                        <td><?= money($s['paid_amount']) ?></td>
                        <td><?= money($remaining) ?></td>
                        <td><span class="badge <?= $typeBadge ?>"><?= $typeLabel ?></span></td>
                        <td class="actions-cell">
                            <form method="post" action="/sales/delete" class="inline-form" onsubmit="return confirm('حذف هذه العملية؟')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
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

<script>
(function() {
    var pkg     = document.getElementById('sale-package');
    var bundles = document.getElementById('sale-bundles');
    var price   = document.getElementById('sale-price');
    var type    = document.getElementById('sale-type');
    var paid    = document.getElementById('sale-paid');
    var paidField  = document.getElementById('paid-amount-field');
    var display    = document.getElementById('sale-total-display');
    var remainingD = document.getElementById('sale-remaining-display');

    function calc() {
        var b = parseInt(bundles.value) || 0;
        var p = parseInt(price.value) || 0;
        var total = b * p;
        display.textContent = total.toLocaleString();

        var t = type.value;
        if (t === 'installment') {
            paidField.style.display = '';
            paid.max = total;
            var paidVal = parseInt(paid.value) || 0;
            if (paidVal > total) { paidVal = total; paid.value = total; }
            remainingD.style.display = '';
            remainingD.querySelector('strong').textContent = (total - paidVal).toLocaleString();
        } else {
            paidField.style.display = 'none';
            remainingD.style.display = 'none';
            paid.value = 0;
        }
    }

    pkg.addEventListener('change', function() {
        var opt = pkg.options[pkg.selectedIndex];
        var bp = opt.getAttribute('data-bundle-price');
        if (bp) price.value = bp;
        calc();
    });
    bundles.addEventListener('input', calc);
    price.addEventListener('input', calc);
    type.addEventListener('change', calc);
    paid.addEventListener('input', calc);
    calc();
})();
</script>
