<?php
// صفحة إدارة المواعيد الخاصة بالطبيب

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">

  <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success">
          <?= $_SESSION['success']; ?>
      </div>
      <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
          <?= $_SESSION['error']; ?>
      </div>
      <?php unset($_SESSION['error']); ?>
  <?php endif; ?>


  <div class="content-header">
    <div class="container-fluid mb-2">
      <h1 class="m-0 text-dark text-right">
        إدارة المواعيد المحجوزة في عيادتك
      </h1>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">

    <div class="container-fluid">

      <!-- رسائل النجاح -->
      <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <!-- رسائل الخطأ -->
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="card">

        <div class="card-header bg-primary">
          <h3 class="card-title" style="float: right;">
            طلبات الحجز الحالية
          </h3>
        </div>

        <div class="card-body p-0">

          <table class="table table-hover table-striped">

            <thead>
              <tr>
                <th>اسم المريض</th>
                <th>التاريخ والوقت</th>
                <th>الحالة الحالية</th>
                <th class="text-left">العمليات</th>
              </tr>
            </thead>

            <tbody>

            <?php if(empty($appointments)): ?>

              <tr>
                <td colspan="4" class="text-center text-muted">
                  لا يوجد طلبات حجز حالياً.
                </td>
              </tr>

            <?php else: ?>

              <?php foreach($appointments as $app): ?>

                <tr>

                  <!-- اسم المريض -->
                  <td>
                    <b><?= sanitize($app['patient_name']); ?></b>
                  </td>

                  <!-- عرض التاريخ والوقت -->
                  <td>

                    <?php
                    $full_date =
                        $app['appt_date'] .
                        ' ' .
                        $app['appt_time'];

                    echo date(
                        'Y-m-d h:i A',
                        strtotime($full_date)
                    );
                    ?>

                  </td>

                  <!-- حالة الموعد -->
                  <td>

                    <?php

                    // توحيد قيمة الحالة قبل المقارنة
                    $status = strtolower(
                        trim($app['status'])
                    );

                    if($status === 'pending'):
                    ?>

                      <span class="badge badge-warning">
                        قيد الانتظار
                      </span>

                    <?php elseif($status === 'confirmed' || $status === 'approved'): ?>

                      <span class="badge badge-success">
                        تم القبول
                      </span>

                    <?php elseif($status === 'completed'): ?>

                      <span class="badge badge-info">
                        مكتمل
                      </span>

                    <?php else: ?>

                      <span class="badge badge-danger">
                        ملغي
                      </span>

                    <?php endif; ?>

                  </td>

                  <!-- الأزرار الخاصة بكل حالة -->
                  <td class="text-left">

                    <?php if ($status === 'pending'): ?>

                      <!-- قبول الموعد -->
                      <a href="index.php?page=appointments&action=confirm&id=<?php echo $app['id']; ?>&source=<?php echo $_GET['action'] ?? 'index'; ?>"
                         class="btn btn-success btn-sm">

                         ✅ قبول

                      </a>

                      <!-- إلغاء الموعد -->
                      <a href="index.php?page=appointments&action=cancel&id=<?php echo $app['id']; ?>&source=<?php echo $_GET['action'] ?? 'index'; ?>"
                         class="btn btn-danger btn-sm">

                         ❌ إلغاء

                      </a>

                    <?php elseif ($status === 'confirmed' || $status === 'approved'): ?>

                      <!-- فتح صفحة الروشتة -->
                      <a href="index.php?page=appointments&action=addPrescriptionView&id=<?php echo $app['id']; ?>"
                         class="btn btn-info btn-sm"
                         style="color:white;">

                         📝 إتمام وإضافة روشتة

                      </a>

                      <a href="index.php?page=appointments&action=cancel&id=<?php echo $app['id']; ?>&source=<?php echo $_GET['action'] ?? 'index'; ?>"
                         class="btn btn-danger btn-sm">

                         ❌ إلغاء

                      </a>

                    <?php elseif ($status === 'completed'): ?>

                      <span class="badge badge-secondary">
                        ✅ موعد مكتمل ومغلق
                      </span>

                    <?php else: ?>

                      <span class="text-muted">
                        ملغي
                      </span>

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