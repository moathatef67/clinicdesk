<?php
// views/reports/all_appointments.php
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid mb-2 text-right">
      <h1 class="m-0 text-dark">📋 سجل الحجوزات الشامل</h1>
      <p class="text-muted">عرض كافة الحالات والمواعيد المسجلة في العيادة</p>
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
        <div class="card-header bg-warning">
          <h3 class="card-title" style="float: right;">كافة المواعيد (مقبول / معلق / ملغي)</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover table-striped text-center mb-0">
            <thead>
              <tr>
                <th>اسم المريض</th>
                <th>الطبيب المعالج</th>
                <th>التاريخ والوقت</th>
                <th>الحالة</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($allAppointments)): ?>
                <?php foreach ($allAppointments as $app): ?>
                  <tr>
                    <td><b><?= sanitize($app['patient_name'] ?? 'مريض مسجل'); ?></b></td>
                    <td>د. <?= sanitize($app['doctor_name'] ?? 'غير محدد'); ?></td>
                    <td><?= $app['appt_date'] . ' ' . $app['appt_time']; ?></td>
                    <td>
                      <?php if($app['status'] === 'confirmed' || $app['status'] === 'approved'): ?>
                        <span class="badge badge-success">مقبول</span>
                      <?php elseif($app['status'] === 'pending'): ?>
                        <span class="badge badge-warning">قيد الانتظار</span>
                      <?php else: ?>
                        <span class="badge badge-danger">ملغي</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-muted p-4">لا توجد مواعيد مسجلة في النظام حتى الآن.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>