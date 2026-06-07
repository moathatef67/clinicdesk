<?php
// views/reports/pending_appointments.php
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid mb-2 text-right">
      <h1 class="m-0 text-dark">🗂️ المواعيد المعلقة (قيد الانتظار)</h1>
      <p class="text-muted">مراجعة شاملة لكافة طلبات الحجز التي تنتظر إجراء الأطباء</p>
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
        <div class="card-header bg-danger">
          <h3 class="card-title" style="float: right;">قائمة طلبات الحجز المعلقة بالنظام</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover table-striped text-center mb-0">
            <thead>
              <tr>
                <th>اسم المريض</th>
                <th>الطبيب المعالج</th>
                <th>التاريخ والوقت</th>
                <th>الحالة الحالية</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($filteredAppointments)): ?>
                <?php foreach ($filteredAppointments as $app): ?>
                  <tr>
                    <td><b><?= sanitize($app['patient_name'] ?? 'مريض مسجل'); ?></b></td>
                    <td>د. <?= sanitize($app['doctor_name'] ?? 'غير محدد'); ?></td>
                    <td>
                      <?php 
                        $full_datetime = $app['appt_date'] . ' ' . $app['appt_time'];
                        echo date('Y-m-d h:i A', strtotime($full_datetime)); 
                      ?>
                    </td>
                    <td><span class="badge badge-warning">قيد الانتظار</span></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-muted p-4">رائع! لا توجد مواعيد معلقة قيد الانتظار حالياً.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>