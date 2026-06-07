<?php
// views/vitals/index.php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid"><h1 class="m-0 text-dark text-right">قسم التمريض - تسجيل العلامات الحيوية</h1></div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">
      
      <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>

      <div class="card card-info">
        <div class="card-header"><h3 class="card-title" style="float: right;">إدخال القراءات الطبية للمريض</h3></div>
        <form action="index.php?page=vitals&action=store" method="POST">
          <?= CSRF::generateInput(); ?>
          <div class="card-body">
            <div class="row">
              
              <div class="col-md-4 form-group">
                <label>اختر المريض <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-control" required>
                  <option value="">-- اختر المريض --</option>
                  <?php foreach($patients as $p): ?>
                    <option value="<?= $p['patient_id']; ?>"><?= sanitize($p['name']); ?> (<?= sanitize($p['email']); ?>)</option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3 form-group">
                <label>ضغط الدم (مثال: 120/80)</label>
                <input type="text" name="blood_pressure" class="form-control" placeholder="120/80">
              </div>

              <div class="col-md-3 form-group">
                <label>نبض القلب (نبضة/د)</label>
                <input type="text" name="heart_rate" class="form-control" placeholder="75">
              </div>

              <div class="col-md-2 form-group">
                <label>الحرارة (C°)</label>
                <input type="text" name="temperature" class="form-control" placeholder="36.5">
              </div>

            </div>
          </div>
          <div class="card-footer text-left">
            <button type="submit" class="btn btn-info">حفظ العلامات الحيوية</button>
          </div>
        </form>
      </div>

    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>