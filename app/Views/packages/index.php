<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2>الباقات</h2>
            <p>إدارة باقات بطاقات الإنترنت — البيع بالشدة</p>
        </div>
    </section>

    <section class="dashboard-panel">
        <details class="form-collapse">
            <summary class="btn primary">+ إضافة باقة جديدة</summary>
            <form method="post" action="/packages/store" class="entity-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>اسم الباقة<input name="name" required placeholder="مثال: 100 ريال"></label>
                    <label>سعر الكرت (ريال)<input name="price" type="number" min="0" required></label>
                    <label>عدد الكروت في الشدة<input name="bundle_size" type="number" min="1" required value="50"></label>
                    <label>سعر الشدة (ريال)<input name="bundle_price" type="number" min="0" required></label>
                    <label>عدد الساعات<input name="hours" type="number" min="0" placeholder="اختياري"></label>
                    <label>المدة<input name="duration" placeholder="مثال: 30 يوم"></label>
                    <label>الحالة
                        <select name="status">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </label>
                </div>
                <button class="btn primary" type="submit">حفظ</button>
            </form>
        </details>
    </section>

    <section class="dashboard-panel">
        <div class="section-title"><h3>قائمة الباقات</h3><span>◇</span></div>
        <?php if (empty($packages)): ?>
            <div class="empty-state">لا توجد باقات حتى الآن</div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>الاسم</th><th>سعر الكرت</th><th>الشدة =</th><th>سعر الشدة</th><th>المدة</th><th>الحالة</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $pkg): ?>
                    <tr>
                        <td><?= e($pkg['name']) ?></td>
                        <td><?= money($pkg['price']) ?></td>
                        <td><?= int_num($pkg['bundle_size']) ?> كرت</td>
                        <td><?= money($pkg['bundle_price']) ?></td>
                        <td><?= e($pkg['duration'] ?? '') ?></td>
                        <td><span class="badge <?= $pkg['status'] === 'active' ? 'ok' : 'zero' ?>"><?= $pkg['status'] === 'active' ? 'نشط' : 'متوقف' ?></span></td>
                        <td class="actions-cell">
                            <form method="post" action="/packages/delete" class="inline-form" onsubmit="return confirm('حذف هذه الباقة؟')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$pkg['id'] ?>">
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
