<?php
/**
 * Shared "section coming-soon" partial.
 *
 * Variables expected: $pageTitle, $active, $hint (optional)
 *
 * The schema currently only has the `admins` table; the business modules
 * (packages, sales, ...) will get their own tables and views in later
 * phases. Until then every section renders a clean empty state rather
 * than mock data, per README §13.
 */
?>
<div class="dashboard">
    <section class="dashboard-title">
        <div>
            <h2><?= e($pageTitle) ?></h2>
            <p><?= e($hint ?? 'هذا القسم سيتم تفعيله في المرحلة القادمة من التطوير.') ?></p>
        </div>
    </section>

    <section class="dashboard-panel">
        <div class="empty-state">لا توجد بيانات حتى الآن</div>
    </section>
</div>
