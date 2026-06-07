<?php $role = Auth::user('role'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Document</title>
</head>
<body>
  
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.php" class="brand-link text-center">
    <span class="brand-text font-weight-light">Clinic<b>Desk</b></span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info text-center w-100">
        <a href="#" class="d-block"><i class="fas fa-user-circle mr-2"></i> <?= Auth::user('name'); ?> (<?= strtoupper($role); ?>)</a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        
        <li class="nav-item">
          <a href="index.php?page=dashboard" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>لوحة التحكم</p>
          </a>
        </li>

        <?php if ($role === 'admin'): ?>
          <li class="nav-header">إدارة النظام</li>
          <li class="nav-item">
            <a href="index.php?page=users" class="nav-link">
              <i class="nav-icon fas fa-users"></i> <p>إدارة المستخدمين</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?page=doctors" class="nav-link">
              <i class="nav-icon fas fa-user-md"></i> <p>إدارة الأطباء</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?page=reports" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i> <p>التقارير والإحصائيات</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'doctor'): ?>
          <li class="nav-header">العيادة</li>
          <li class="nav-item">
            <a href="index.php?page=appointments" class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i> <p>مواعيد اليوم</p>
            </a>
            <li>



            <li>
            <a href="index.php?page=doctor_dashboard&action=upcoming" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>المواعيد القادمة</p>
            </a>
            </li>
          </li>
        <?php endif; ?>

        <?php if ($role === 'patient'): ?>
          <li class="nav-header">حسابي</li>
          <li class="nav-item">
            <a href="index.php?page=appointments&action=book" class="nav-link">
              <i class="nav-icon fas fa-plus-circle"></i> <p>حجز موعد جديد</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?page=appointments" class="nav-link">
              <i class="nav-icon fas fa-history"></i> <p>تاريخ مواعيدي</p>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>
</body>
</html>