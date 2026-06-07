<?php
// controllers/UserController.php

require_once __DIR__ . '/../models/UserModel.php';

class UserController {

    // عرض المستخدمين مع البحث والفلاتر والترقيم
    public function index() {

        // السماح للأدمن فقط بالدخول
        Auth::requireRole('admin');

        // رقم الصفحة الحالية
        $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;

        // عدد العناصر بكل صفحة
        $limit = 10;

        // قيمة البحث إن وجدت
        $search = $_GET['search'] ?? null;

        // فلترة حسب نوع المستخدم
        $role = $_GET['role'] ?? null;

        // إنشاء كائن من الموديل
        $userModel = new UserModel();

        // جلب المستخدمين حسب الصفحة الحالية
        $users = $userModel->getUsersPaginated(
            $page,
            $limit,
            $search,
            $role
        );

        // حساب إجمالي عدد المستخدمين
        $totalUsers = $userModel->getTotalUsers(
            $search,
            $role
        );

        // حساب عدد الصفحات
        $totalPages = ceil($totalUsers / $limit);

        // عرض صفحة المستخدمين
        require_once __DIR__ . '/../views/users/index.php';
    }

    // تفعيل أو تعطيل حساب المستخدم
    public function toggleActive() {

        // السماح للأدمن فقط
        Auth::requireRole('admin');

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['id'])
        ) {

            // رقم المستخدم المطلوب تحديثه
            $id = intval($_POST['id']);

            // رقم الأدمن الحالي
            $currentAdminId = $_SESSION['user']['id'];

            // منع الأدمن من تعطيل حسابه الخاص
            if ($id === $currentAdminId) {

                $_SESSION['error'] =
                    "لا يمكنك إيقاف حسابك الخاص!";

            } else {

                $db = Database::getInstance()->getConnection();

                // تغيير حالة الحساب
                $stmt = $db->prepare("
                    UPDATE users
                    SET is_active = NOT is_active
                    WHERE id = ?
                ");

                $stmt->execute([$id]);

                $_SESSION['success'] =
                    "تم تحديث حالة المستخدم بنجاح.";
            }
        }

        // الرجوع إلى صفحة المستخدمين
        header("Location: index.php?page=users&action=index");
        exit();
    }
}