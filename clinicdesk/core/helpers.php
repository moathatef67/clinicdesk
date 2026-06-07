<?php
// core/helpers.php

// تنظيف البيانات المدخلة
if (!function_exists('sanitize')) {

    function sanitize($data) {

        return htmlspecialchars(
            trim($data),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

// دالة إعادة التوجيه بين الصفحات
if (!function_exists('redirect')) {

    function redirect($page, $action = '') {

        $url = 'index.php?page=' . $page;

        if ($action) {
            $url .= '&action=' . $action;
        }

        header("Location: " . $url);
        exit();
    }
}