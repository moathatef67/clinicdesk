<?php
// views/doctors/create.php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">إضافة طبيب جديد</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-right"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title" style="float: right;">أدخل بيانات حساب الطبيب والعيادة</h3>
        </div>
        
        <form action="index.php?page=doctors&action=store" method="POST">
          <?= CSRF::generateInput(); ?>

          <div class="card-body text-right" style="direction: rtl;">
            <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>اسم الطبيب كاملاً <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="مثال: د. معاذ الشاعر" required>
                </div>
                <div class="form-group">
                  <label>البريد الإلكتروني الحس اب <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" placeholder="doctor@clinic.local" required>
                </div>
                <div class="form-group">
                  <label>كلمة المرور الافتراضية <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>التخصص الطبي <span class="text-danger">*</span></label>
                  <select name="specialization_id" class="form-control" required>
                    <option value="">اختر التخصص...</option>
                    <?php foreach($specializations as $spec): ?>
                      <option value="<?= $spec['id']; ?>"><?= sanitize($spec['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>رسوم الكشفية ($) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" name="consultation_fee" class="form-control" placeholder="50.00" required>
                </div>

                <div class="form-group">
                  <label>أيام العمل المتاحة</label><br>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="days[]" value="Sun" checked> <label class="form-check-label mr-4">الأحد</label>
                    <input class="form-check-input" type="checkbox" name="days[]" value="Mon" checked> <label class="form-check-label mr-4">الاثنين</label>
                    <input class="form-check-input" type="checkbox" name="days[]" value="Tue" checked> <label class="form-check-label mr-4">الثلاثاء</label>
                    <input class="form-check-input" type="checkbox" name="days[]" value="Wed" checked> <label class="form-check-label mr-4">الأربعاء</label>
                    <input class="form-check-input" type="checkbox" name="days[]" value="Thu" checked> <label class="form-check-label mr-4">الخميس</label>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>نبذة عن الطبيب (Bio)</label>
                  <textarea name="bio" class="form-control" rows="3" placeholder="اكتب نبذة مختصرة عن خبرات الطبيب..."></textarea>
                </div>
              </div>

            </div>
          </div>

          <div class="card-footer text-left">
            <button type="submit" class="btn btn-success">حفظ وبيانات الطبيب</button>
            <a href="index.php?page=doctors" class="btn btn-secondary">إلغاء</a>
          </div>
        </form>
      </div>

    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>