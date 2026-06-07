<?php
// صفحة عرض جميع المواعيد الخاصة بالمريض

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">

  <!-- عنوان الصفحة -->
  <div class="content-header">
    <div class="container-fluid mb-2">
      <h1 class="m-0 text-dark text-right">مواعيدي وحجوزاتي</h1>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">

      <!-- رسائل النجاح والخطأ -->
      <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="card">

        <div class="card-header bg-info">
          <h3 class="card-title" style="float:right;color:white;">
            سجل مواعيدك الطبية
          </h3>
        </div>

        <div class="card-body p-0">

          <table class="table table-hover table-striped">

            <thead>
              <tr>
                <th>الطبيب</th>
                <th>التخصص</th>
                <th>التاريخ والوقت</th>
                <th>الحالة</th>
              </tr>
            </thead>

            <tbody>

            <?php if(empty($appointments)): ?>

              <tr>
                <td colspan="4" class="text-center text-muted">
                  لم تقم بحجز أي مواعيد طبية بعد.
                </td>
              </tr>

            <?php else: ?>

              <?php foreach($appointments as $app): ?>

                <tr>

                  <!-- اسم الطبيب -->
                  <td>
                    <b><?= sanitize($app['doctor_name'] ?? $app['doctor_id']); ?></b>
                  </td>

                  <!-- التخصص -->
                  <td>
                    <?= sanitize($app['specialization_name'] ?? $app['specialty'] ?? 'General'); ?>
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
                        مكتمل ومغلق
                      </span>

                      <br>

                      <!-- إظهار الروشتة بعد انتهاء الموعد -->
                      <a href="index.php?page=appointments&action=viewPrescription&id=<?= $app['id']; ?>"
                         class="btn btn-sm btn-outline-primary mt-1"
                         style="font-size:0.8rem;">

                         👁️ عرض الروشتة الطبية

                      </a>

                    <?php else: ?>

                      <span class="badge badge-danger">
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