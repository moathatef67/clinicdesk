<?php
// views/reports/patients_list.php
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid mb-2 text-right">
      <h1 class="m-0 text-dark">👥 إدارة ملفات المرضى</h1>
      <p class="text-muted">استعراض كافة الحسابات المسجلة بالنظام بصفة مريض</p>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">
      
      <div class="mb-3">
         <a href="index.php?page=reports&action=index" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-right"></i> العودة للوحة الإحصائيات
         </a>
      </div>

      <div class="card">
        <div class="card-header bg-success">
          <h3 class="card-title text-white" style="float: right;">قائمة المرضى المسجلين</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover table-striped text-center mb-0">
            <thead>
              <tr>
                <th>رقم المريض</th>
                <th>الاسم الكامل</th>
                <th>البريد الإلكتروني</th>
                <th>تاريخ الانضمام</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($patients)): ?>
                <?php foreach ($patients as $patient): ?>
                  <tr>
                    <td>#<?= $patient['id']; ?></td>
                    <td><b><?= sanitize($patient['name']); ?></b></td>
                    <td><?= sanitize($patient['email']); ?></td>
                    <td><?= date('Y-m-d', strtotime($patient['created_at'])); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-muted p-4">لا يوجد مرضى مسجلين حالياً.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>