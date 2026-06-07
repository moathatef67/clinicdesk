<?php
// views/doctors/index.php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">إدارة الأطباء</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="index.php?page=doctors&action=create" class="btn btn-primary">
            <i class="fas fa-user-plus mr-1"></i> إضافة طبيب جديد
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-right"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>

      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title" style="float: right;">قائمة الأطباء المسجلين في النظام</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover table-striped text-right">
            <thead>
              <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>التخصص</th>
                <th>سعر الكشفية</th>
                <th>أيام الدوام</th>
                <th>الحالة</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($doctors)): ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">لا يوجد أطباء مسجلين حالياً.</td>
                </tr>
              <?php else: ?>
                <?php foreach($doctors as $doc): ?>
                  <tr>
                    <td><?= $doc['id']; ?></td>
                    <td><b><?= sanitize($doc['name']); ?></b></td>
                    <td><?= sanitize($doc['email']); ?></td>
                    <td><span class="badge badge-info"><?= sanitize($doc['specialization_name']); ?></span></td>
                    <td>$<?= number_format($doc['consultation_fee'], 2); ?></td>
                    <td><small><?= sanitize($doc['available_days']); ?></small></td>
                    <td>
                      <?php if($doc['is_active'] == 1): ?>
                        <span class="badge badge-success">نشط</span>
                      <?php else: ?>
                        <span class="badge badge-danger">معطل</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>