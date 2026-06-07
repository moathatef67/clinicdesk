<?php
// استدعاء الهيدر (تأكد أن المسار صحيح)
require_once 'views/partials/header.php';
?>

<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Clinic</b>Desk</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">إنشاء حساب مريض جديد</p>

      <form action="<?php echo BASE_URL; ?>index.php?page=auth&action=registerProcess" method="post">
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="الاسم الكامل" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">تسجيل الحساب</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1 text-center">
        <a href="<?php echo BASE_URL; ?>index.php?page=auth&action=login">لديك حساب؟ سجل دخولك</a>
      </p>
    </div>
  </div>
</div>

<?php
// استدعاء الفوتر (المحتوي على ملفات الـ JavaScript)
require_once 'views/partials/footer.php';
?>