<?php
// صفحة تسجيل الدخول للنظام
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>ClinicDesk | Log in</title>

  <link rel="stylesheet"
        href="public/assets/plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet"
        href="public/assets/dist/css/adminlte.min.css">

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <!-- شعار النظام -->
  <div class="login-logo">
    <a href="#">
      <b>Clinic</b>Desk
    </a>
  </div>

  <div class="card">

    <div class="card-body login-card-body">

      <p class="login-box-msg">
        تسجيل الدخول للنظام
      </p>

      <!-- عرض رسالة الخطأ -->
      <?php if(isset($_SESSION['error'])): ?>

        <div class="alert alert-danger text-right">

          <?= $_SESSION['error']; unset($_SESSION['error']); ?>

        </div>

      <?php endif; ?>

      <!-- نموذج تسجيل الدخول -->
      <form action="index.php?page=auth&action=loginProcess"
            method="POST">

        <?= CSRF::generateInput(); ?>

        <!-- البريد الإلكتروني -->
        <div class="input-group mb-3">

          <input type="email"
                 name="email"
                 class="form-control"
                 placeholder="البريد الإلكتروني"
                 required>

          <div class="input-group-append">

            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>

          </div>

        </div>

        <!-- كلمة المرور -->
        <div class="input-group mb-3">

          <input type="password"
                 name="password"
                 class="form-control"
                 placeholder="كلمة المرور"
                 required>

          <div class="input-group-append">

            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>

          </div>

        </div>

        <div class="row">

          <div class="col-12">

            <button type="submit"
                    class="btn btn-primary btn-block">

              دخول

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>

</div>

<a href="index.php?page=auth&action=register" class="text-center">ليس لديك حساب؟ سجل الآن</a>
</body>
</html>