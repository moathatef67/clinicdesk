<?php
// views/users/index.php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-right">إدارة المستخدمين</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title" style="float: right;">البحث والتصفية</h3></div>
                <div class="card-body">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="users">
                        <input type="hidden" name="action" value="index">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="البحث بالاسم أو البريد..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-control">
                                    <option value="">كل الصلاحيات</option>
                                    <option value="admin" <?= ($_GET['role'] ?? '') == 'admin' ? 'selected' : '' ?>>مشرف</option>
                                    <option value="doctor" <?= ($_GET['role'] ?? '') == 'doctor' ? 'selected' : '' ?>>طبيب</option>
                                    <option value="patient" <?= ($_GET['role'] ?? '') == 'patient' ? 'selected' : '' ?>>مريض</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">بحث وتصفية</button>
                                <a href="index.php?page=users&action=index" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover table-striped text-center">
                        <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>الاسم</th>
                                <th>الصلاحية</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($users)): ?>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?= $user['id']; ?></td>
                                    <td><b><?= sanitize($user['name']); ?></b></td>
                                    <td>
                                        <?php if($user['role'] === 'admin'): ?>
                                            <span class="badge badge-danger">مشرف</span>
                                        <?php elseif($user['role'] === 'doctor'): ?>
                                            <span class="badge badge-primary">طبيب</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">مريض</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $user['is_active'] ? '<span class="badge badge-success">نشط</span>' : '<span class="badge badge-danger">موقوف</span>'; ?>
                                    </td>
                                    <td>
                                        <form action="index.php?page=users&action=toggleActive" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                            <button type="submit" class="btn btn-sm <?= $user['is_active'] ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <?= $user['is_active'] ? '🚫 إيقاف' : '✅ تفعيل'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-muted">لا يوجد مستخدمين مسجلين حالياً.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
<div class="card-footer clearfix">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm m-0 float-right">
            <?php 
            // نلتقط القيم الحالية من الرابط لضمان بقائها عند الضغط على أي صفحة
            $search_val = urlencode($_GET['search'] ?? '');
            $role_val = urlencode($_GET['role'] ?? '');

            for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?page=users&action=index&p=<?= $i ?>&search=<?= $search_val ?>&role=<?= $role_val ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
            </div>

        </div>
    </section>
</div> <?php require_once __DIR__ . '/../partials/footer.php'; ?>