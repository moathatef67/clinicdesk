<?php
// views/dashboard/admin.php

// 1. استدعاء الأجزاء العلوية الثابتة
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">لوحة تحكم المشرف (Admin)</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>إدارة</h3>
              <p>المستخدمين والأطباء</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="index.php?page=users&action=index" class="small-box-footer">انتقل للإدارة <i class="fas fa-arrow-circle-right"></i></a>          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>تقارير</h3>
              <p>المواعيد والعيادة</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="index.php?page=reports&action=index" class="small-box-footer">عرض التقارير <i class="fas fa-arrow-circle-right"></i></a>          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
// 2. استدعاء الجزء السفلي الثابت
require_once __DIR__ . '/../partials/footer.php';
?>