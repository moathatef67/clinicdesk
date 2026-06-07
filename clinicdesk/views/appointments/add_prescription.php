<?php
// views/appointments/add_prescription.php
// echo "<pre>"; print_r($appointment); echo "</pre>";

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">

  <!-- عنوان الصفحة -->
  <div class="content-header">
    <div class="container-fluid mb-2">
      <h1 class="m-0 text-dark text-right">
        إتمام الكشف الطبي وإعداد الروشتة
      </h1>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">

      <?php if(isset($_SESSION['error'])): ?>
        <!-- عرض رسالة الخطأ -->
        <div class="alert alert-danger">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="card card-info">

        <div class="card-header">
          <h3 class="card-title" style="float: right;">
            بيانات الروشتة الطبية للمريض:
            <b><?= sanitize($appointment['patient_name']); ?></b>
          </h3>
        </div>

        <!-- نموذج إضافة الروشتة -->
        <form action="index.php?page=appointments&action=storePrescription"
              method="POST"
              enctype="multipart/form-data">

          <?= CSRF::generateInput(); ?>

          <input type="hidden"
                 name="appointment_id"
                 value="<?= $appointment['id']; ?>">

          <input type="hidden"
                 name="patient_id"
                 value="<?= $appointment['patient_id']; ?>">

          <div class="card-body">

            <!-- حقل التشخيص والعلاج -->
            <div class="form-group">
              <label for="prescription_text">
                التشخيص الطبي والعلاجات المقررة
                <span class="text-danger">*</span>
              </label>

              <textarea
                  name="prescription_text"
                  id="prescription_text"
                  class="form-control"
                  rows="6"
                  required></textarea>
            </div>

            <!-- رفع ملف مرفق -->
            <div class="form-group mt-4">

              <label for="prescription_file">
                إرفاق ملف طبي أو صورة أشعة / تحاليل
              </label>

              <div class="custom-file">

                <input type="file"
                       name="prescription_file"
                       id="prescription_file"
                       class="form-control-file">

                <small class="text-muted">
                  PDF, PNG, JPG, JPEG
                </small>

              </div>
            </div>

          </div>

          <div class="card-footer text-left">

            <button type="submit"
                    class="btn btn-info"
                    style="color: white;">
              💾 حفظ الروشتة وإنهاء الموعد
            </button>

            <a href="index.php?page=appointments"
               class="btn btn-default">
              إلغاء وتراجع
            </a>

          </div>

        </form>

      </div>

    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>