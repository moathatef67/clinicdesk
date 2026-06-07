<?php
// views/reports/index.php

// استدعاء الهيدر والقوائم الجانبية لضمان تحميل الـ CSS والـ AdminLTE كاملاً
require_once dirname(__DIR__) . '/partials/header.php';
require_once dirname(__DIR__) . '/partials/navbar.php';
require_once dirname(__DIR__) . '/partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid mb-4 text-right">
      <h1 class="m-0 text-dark">التقارير والإحصائيات العامة للنظام</h1>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?= $pendingAppointments ?? 0; ?></h3>
              <p>مواعيد قيد الانتظار</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="index.php?page=reports&action=pending" class="small-box-footer">
               تنتظر إجراء الطبيب <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $totalAppointments ?? 0; ?></h3>
              <p>إجمالي الحجوزات</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <a href="index.php?page=reports&action=all_appointments" class="small-box-footer" style="color: #1f2d3d !important;">
               كل الحالات <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $totalPatients ?? 0; ?></h3>
              <p>إجمالي المرضى</p>
            </div>
            <div class="icon">
              <i class="fas fa-accessible-icon"></i>
            </div>
            <a href="index.php?page=reports&action=patients" class="small-box-footer">
               جاهز للعرض <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $totalDoctors ?? 0; ?></h3>
              <p>الأطباء المسجلين</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-md"></i>
            </div>
            <a href="index.php?page=doctors&action=index" class="small-box-footer">
               إدارة الأطباء <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div></div></section>
</div><script>
document.querySelectorAll('.small-box').forEach(box => {
    box.style.cursor = 'pointer';
    box.addEventListener('click', function(e) {
        if (!e.target.closest('.small-box-footer')) {
            const link = this.querySelector('.small-box-footer');
            if (link) window.location.href = link.getAttribute('href');
        }
    });
});
</script>

<?php 
// استدعاء الفوتر لإغلاق الـ tags بشكل سليم لـ AdminLTE
require_once dirname(__DIR__) . '/partials/footer.php'; 
?>