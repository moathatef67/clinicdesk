<?php
// core/CSRF.php

class CSRF {

    // إنشاء رمز حماية للفورم
    public static function generateInput() {

        // التأكد من بدء الجلسة
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // إنشاء التوكن إذا لم يكن موجوداً
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // إرجاع حقل مخفي يحتوي على التوكن
        return '<input type="hidden" name="csrf_token" value="' .
            $_SESSION['csrf_token'] . '">';
    }

    // التحقق من صحة التوكن
    public static function validate($token) {

        // التأكد من بدء الجلسة
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // مقارنة التوكن القادم مع المخزن في الجلسة
        if (
            !isset($_SESSION['csrf_token']) ||
            empty($token) ||
            $token !== $_SESSION['csrf_token']
        ) {
            return false;
        }

        return true;
    }
}