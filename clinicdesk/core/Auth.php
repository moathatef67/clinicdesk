<?php
// core/Auth.php

class Auth {

    // حفظ بيانات المستخدم بعد تسجيل الدخول
    public static function login($user) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
    }

    // التحقق إذا كان المستخدم مسجل دخول
    public static function check() {

        return isset($_SESSION['user_id']);
    }

    // جلب بيانات المستخدم الحالي
    public static function user($key = null) {

        if (!self::check()) {
            return null;
        }

        // إرجاع قيمة محددة إذا تم تمرير مفتاح
        if ($key) {
            return $_SESSION['user_' . $key] ?? null;
        }

        // إرجاع جميع بيانات المستخدم
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role'  => $_SESSION['user_role']
        ];
    }

    // التحقق من صلاحية المستخدم للدخول للصفحة
    public static function requireRole($allowedRoles) {

        // إذا لم يكن مسجل دخول
        if (!self::check()) {
            redirect('auth', 'login');
        }

        // التحقق من الدور المسموح
        if (!in_array($_SESSION['user_role'], (array) $allowedRoles)) {
            redirect('errors', '403');
        }
    }

    // تسجيل الخروج
    public static function logout() {

        // حذف بيانات الجلسة
        $_SESSION = [];

        // حذف الكوكي الخاصة بالجلسة
        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // إنهاء الجلسة
        session_destroy();
    }
}