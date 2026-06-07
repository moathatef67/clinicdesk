<?php
// views/doctor/dashboard.php

// استدعاء الهيدر، النظارات، والقائمة الجانبية لـ AdminLTE
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="content-wrapper text-right" style="direction: rtl;">
  <div class="content-header">
    <div class="container-fluid mb-3">
      <h1 class="m-0 text-dark">👨‍⚕️ لوحة تحكم الطبيب</h1>
      <p class="text-muted">مرحباً بك، يمكنك متابعة وإدارة مواعيد العيادة لليوم وللأيام القادمة.</p>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid"></div>

    <div class="card card-danger card-outline mb-4">
        <div class="card-header">
          <h3 class="card-title" style="float: right;">📅 مواعيد اليوم الحالي</h3>
          <div class="card-tools" style="float: left;">
            <span class="badge badge-danger"><?= count($todayAppointments); ?> مواعيد</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover text-center mb-0">
              <thead>
                <tr>
                  <th>اسم المريض</th>
                  <th>وقت الموعد</th>
                  <th>حالة الموعد</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($todayAppointments)): ?>
                  <?php foreach ($todayAppointments as $app): ?>
                    <tr>
                      <td><b><?= htmlspecialchars($app['patient_name'] ?? 'مريض مسجل'); ?></b></td>
                      <td><?= date('h:i A', strtotime($app['appt_time'])); ?></td>
                      <td>
                        <?php if ($app['status'] === 'pending'): ?>
                          <span class="badge badge-warning">قيد الانتظار</span>
                        <?php elseif ($app['status'] === 'confirmed'): ?>
                          <span class="badge badge-success">مؤكد</span>
                        <?php elseif ($app['status'] === 'completed'): ?>
                          <span class="badge badge-info">مكتمل</span>
                        <?php else: ?>
                          <span class="badge badge-secondary">ملغي</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($app['status'] === 'pending'): ?>
                          <a href="index.php?page=appointments&action=confirm&id=<?= $app['id']; ?>" class="btn btn-success btn-xs">تأكيد</a>
                        <?php endif; ?>
                        
                        <?php if ($app['status'] === 'confirmed'): ?>
                          <a href="index.php?page=appointments&action=complete&id=<?= $app['id']; ?>" class="btn btn-info btn-xs">إنهاء الكشف</a>
                        <?php endif; ?>

                        <?php if ($app['status'] !== 'cancelled' && $app['status'] !== 'completed'): ?>
                          <a href="index.php?page=appointments&action=cancel&id=<?= $app['id']; ?>" class="btn btn-danger btn-xs">إلغاء</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="4" class="text-muted p-4">لا توجد مواعيد مجدولة لليوم.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title" style="float: right;">🔮 المواعيد القادمة (المستقبلية)</h3>
          <div class="card-tools" style="float: left;">
            <span class="badge badge-primary"><?= count($upcomingAppointments); ?> مواعيد قادمة</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover text-center mb-0">
              <thead>
                <tr>
                  <th>اسم المريض</th>
                  <th>تاريخ الموعد</th>
                  <th>وقت الموعد</th>
                  <th>حالة الموعد</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($upcomingAppointments)): ?>
                  <?php foreach ($upcomingAppointments as $app): ?>
                    <tr>
                      <td><b><?= htmlspecialchars($app['patient_name'] ?? 'مريض مسجل'); ?></b></td>
                      <td><span class="badge badge-dark"><?= $app['appt_date']; ?></span></td>
                      <td><?= date('h:i A', strtotime($app['appt_time'])); ?></td>
                      <td>
                        <?php if ($app['status'] === 'pending'): ?>
                          <span class="badge badge-warning">قيد الانتظار</span>
                        <?php elseif ($app['status'] === 'confirmed'): ?>
                          <span class="badge badge-success">مؤكد</span>
                        <?php else: ?>
                          <span class="badge badge-secondary"><?= $app['status']; ?></span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($app['status'] === 'pending'): ?>
                          <a href="index.php?page=appointments&action=confirm&id=<?= $app['id']; ?>" class="btn btn-success btn-xs">تأكيد</a>
                        <?php endif; ?>
                        <?php if ($app['status'] !== 'cancelled'): ?>
                          <a href="index.php?page=appointments&action=cancel&id=<?= $app['id']; ?>" class="btn btn-danger btn-xs">إلغاء</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="5" class="text-muted p-4">لا توجد مواعيد مستقبلية محجوزة حالياً.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div></section>
</div><?php 
// استدعاء الفوتر لإغلاق كافة وسوم الـ HTML القياسية لـ AdminLTE
require_once dirname(__DIR__) . '/partials/footer.php'; 
?>


</body>
</html>