<?php
// views/doctors/today_appointments.php

// 1. استدعاء الأجزاء الثابتة والتنسيقات للوحة التحكم لـ AdminLTE
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper text-right" style="direction: rtl;">
  <div class="content-header">
    <div class="container-fluid mb-3">
      <h1 class="m-0 text-dark">📅 مواعيد اليوم الحالي</h1>
      <p class="text-muted">هنا تجد المواعيد المحجوزة لتاريخ اليوم فقط، ويمكنك التحكم بحالتها مباشرة.</p>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <div class="card card-danger card-outline">
        <div class="card-header">
          <h3 class="card-title" style="float: right;">📋 قائمة حجوزات اليوم</h3>
          <div class="card-tools" style="float: left;">
            <span class="badge badge-danger"><?= count($todayAppointments); ?> موعد اليوم</span>
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
                  <th>الإجراءات العملياتية</th>
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
                          <a href="index.php?page=appointments&action=confirm&id=<?= $app['id']; ?>" class="btn btn-success btn-xs">تأكيد الموعد</a>
                        <?php endif; ?>
                        
                        <?php if ($app['status'] === 'confirmed'): ?>
                          <a href="index.php?page=appointments&action=complete&id=<?= $app['id']; ?>" class="btn btn-info btn-xs">إنهاء الكشف (الوصفتة)</a>
                        <?php endif; ?>

                        <?php if ($app['status'] !== 'cancelled' && $app['status'] !== 'completed'): ?>
                          <a href="index.php?page=appointments&action=cancel&id=<?= $app['id']; ?>" class="btn btn-danger btn-xs">إلغاء</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-muted p-4">لا توجد مواعيد مجدولة لتاريخ اليوم. تمنياتنا بيوم سعيد!</td>
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
// إغلاق الصفحات بالفوتر الثابت
require_once dirname(__DIR__) . '/partials/footer.php'; 
?>