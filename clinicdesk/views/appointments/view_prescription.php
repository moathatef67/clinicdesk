<?php
// صفحة عرض الروشتة الطبية للمريض
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';

// تأكد أن $prescription معرفة (تم تمريرها من الكونترولر)
$prescription = $prescription ?? []; 
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid mb-2">
      <h1 class="m-0 text-dark text-right">تفاصيل الروشتة الطبية</h1>
    </div>
  </div>

  <section class="content text-right" style="direction: rtl;">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title" style="float:right;">
            روشتة صادرة عن الطبيب:
            <b><?= isset($prescription['doctor_name']) ? sanitize($prescription['doctor_name']) : 'غير معروف'; ?></b>
          </h3>
        </div>

        <div class="card-body">
          <h5><b>التشخيص الطبي والعلاجات المقررة:</b></h5>
          <div class="p-3 bg-light rounded mb-4" style="white-space:pre-line;border-right:4px solid #007bff;">
            <?= isset($prescription['prescription_text']) ? sanitize($prescription['prescription_text']) : 'لا يوجد تفاصيل.'; ?>
          </div>

          <?php if(!empty($prescription['file_path'])): ?>
            <h5><b>الملفات والمستندات المرفقة:</b></h5>
            <div class="mb-3">
              <a href="<?= sanitize($prescription['file_path']); ?>" target="_blank" class="btn btn-info text-white">
                📂 استعراض وتحميل الملف المرفق
              </a>
            </div>
          <?php endif; ?>

          <hr>
          <a href="index.php?page=appointments" class="btn btn-secondary">العودة لجدول المواعيد</a>
        </div>

        <div class="card-footer text-muted" style="font-size:0.85rem;">
          تاريخ إصدار الروشتة:
          <?= isset($prescription['created_at']) ? date('Y-m-d h:i A', strtotime($prescription['created_at'])) : 'غير محدد'; ?>
        </div>
      </div>
    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>