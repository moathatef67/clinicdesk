<?php
// views/doctor/upcoming_appointments.php

// 1. استدعاء الأجزاء الثابتة للوحة التحكم
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper text-right" style="direction: rtl;">
  <div class="content-header">
    <div class="container-fluid mb-3">
      <h1 class="m-0 text-dark">🔮 المواعيد القادمة</h1>
      <p class="text-muted">هنا تجد جميع الحجوزات المستقبليّة للأيام القادمة مرتبة زمنياً.</p>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title" style="float: right;">📅 جدول المواعيد المستقبلية</h3>
          <div class="card-tools" style="float: left;">
            <span class="badge badge-primary"><?= count($upcomingAppointments); ?> موعد مستقبلي</span>
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
                          <a href="index.php?page=appointments&action=confirm&id=<?= $app['id']; ?>" class="btn btn-success btn-xs">تأكيد الموعد</a>
                        <?php endif; ?>
                        
                        <?php if ($app['status'] !== 'cancelled'): ?>
                          <a href="index.php?page=appointments&action=cancel&id=<?= $app['id']; ?>" class="btn btn-danger btn-xs">إلغاء</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-muted p-4">لا توجد مواعيد مستقبلية محجوزة حالياً.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php 
// إغلاق وسم الـ HTML بالفوتر
require_once dirname(__DIR__) . '/partials/footer.php'; 
?>