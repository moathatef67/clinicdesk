<?php
// صفحة حجز موعد جديد للمريض

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">

  <!-- عنوان الصفحة -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0 text-dark text-right">حجز موعد جديد</h1>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-success">

        <div class="card-header">
          <h3 class="card-title" style="float: right;">
            اختر الطبيب والتاريخ المناسب
          </h3>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
          <!-- عرض رسالة الخطأ -->
          <div class="alert alert-danger alert-dismissible fade show text-right">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <!-- نموذج الحجز -->
        <form action="index.php?page=appointments&action=store" method="POST">

          <?= CSRF::generateInput(); ?>

          <div class="card-body text-right" style="direction: rtl;">

            <!-- اختيار الطبيب -->
            <div class="form-group">
              <label>
                اختر الطبيب والتخصص
                <span class="text-danger">*</span>
              </label>

              <select name="doctor_id" class="form-control" required>

                <option value="">
                  -- اختر من الأطباء المتاحين --
                </option>

                <?php foreach($doctors as $doc): ?>
                  <option value="<?= $doc['id']; ?>">

                    <?= sanitize($doc['name']); ?>
                    [<?= sanitize($doc['specialization_name']); ?>]

                    - سعر الكشفية:
                    $<?= $doc['consultation_fee']; ?>

                    (أيام الدوام:
                    <?= $doc['available_days']; ?>)

                  </option>
                <?php endforeach; ?>

              </select>
            </div>

            <!-- اختيار التاريخ -->
            <div class="form-group">
              <label>
                تاريخ الموعد
                <span class="text-danger">*</span>
              </label>

              <input
                  type="date"
                  name="appointment_date"
                  class="form-control"
                  min="<?= date('Y-m-d'); ?>"
                  required>
            </div>

            <!-- اختيار الوقت -->
            <div class="form-group">

              <label>
                وقت الموعد المتاح بالعيادة
                <span class="text-danger">*</span>
              </label>

              <select name="appointment_time" class="form-control" required>

                <option value="">
                  -- اختر وقتاً من أوقات الدوام الرسمي --
                </option>

                <?php

                // توليد أوقات الدوام من 9 صباحاً إلى 3 مساءً
                for ($hour = 9; $hour <= 15; $hour++) {

                    $formatted_hour = str_pad(
                        $hour,
                        2,
                        "0",
                        STR_PAD_LEFT
                    );

                    // تحويل الوقت لصيغة AM / PM
                    $display_hour = $hour;
                    $period = 'AM';

                    if ($hour > 12) {
                        $display_hour = $hour - 12;
                        $period = 'PM';
                    } elseif ($hour === 12) {
                        $period = 'PM';
                    }

                    $display_hour = str_pad(
                        $display_hour,
                        2,
                        "0",
                        STR_PAD_LEFT
                    );

                    // موعد على رأس الساعة
                    echo "<option value='{$formatted_hour}:00:00'>
                            {$display_hour}:00 {$period}
                          </option>";

                    // موعد على النصف ساعة
                    if ($hour < 15) {
                        echo "<option value='{$formatted_hour}:30:00'>
                                {$display_hour}:30 {$period}
                              </option>";
                    }
                }

                ?>

              </select>

            </div>

          </div>

          <div class="card-footer text-left">
            <button type="submit" class="btn btn-success">
              تأكيد حجز الموعد
            </button>
          </div>

        </form>

      </div>

    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>