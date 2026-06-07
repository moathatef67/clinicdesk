<?php
// config/setup_db.php

// استدعاء ملف إعدادات قاعدة البيانات
require_once 'database.php';

try {

    // إنشاء اتصال مع MySQL
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);

    // تفعيل إظهار الأخطاء أثناء التنفيذ
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // إنشاء قاعدة البيانات إذا لم تكن موجودة
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    // اختيار قاعدة البيانات للعمل عليها
    $pdo->exec("USE `" . DB_NAME . "`;");

    // جدول المستخدمين (مدير - طبيب - مريض)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(180) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'doctor', 'patient') NOT NULL DEFAULT 'patient',
        phone VARCHAR(20) DEFAULT NULL,
        avatar VARCHAR(255) DEFAULT NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // جدول التخصصات الطبية
    $pdo->exec("CREATE TABLE IF NOT EXISTS specializations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // جدول الأطباء وربطه مع المستخدم والتخصص
    $pdo->exec("CREATE TABLE IF NOT EXISTS doctors (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL UNIQUE,
        specialization_id INT UNSIGNED NOT NULL,
        bio TEXT DEFAULT NULL,
        consultation_fee DECIMAL(8,2) NOT NULL DEFAULT 0.00,
        available_days VARCHAR(50) NOT NULL DEFAULT 'Sun,Mon,Tue,Wed,Thu',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // جدول المواعيد الخاصة بالمرضى
    $pdo->exec("CREATE TABLE IF NOT EXISTS appointments (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        patient_id INT UNSIGNED NOT NULL,
        doctor_id INT UNSIGNED NOT NULL,
        appt_date DATE NOT NULL,
        appt_time TIME NOT NULL,
        status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
        reason VARCHAR(255) DEFAULT NULL,
        doctor_notes TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

        // منع حجز نفس الدكتور بنفس الوقت أكثر من مرة
        UNIQUE KEY no_double_booking (doctor_id, appt_date, appt_time),

        FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // جدول الروشتات الطبية
    $pdo->exec("CREATE TABLE IF NOT EXISTS prescriptions (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT UNSIGNED NOT NULL UNIQUE,
        diagnosis TEXT NOT NULL,
        medications TEXT NOT NULL,
        notes TEXT DEFAULT NULL,
        file_path VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // إذا جدول التخصصات فاضي يتم إضافة بيانات افتراضية
    $checkSpecs = $pdo->query("SELECT COUNT(*) FROM specializations")->fetchColumn();

    if ($checkSpecs == 0) {

        $specs = [
            'General Practice',
            'Cardiology',
            'Dermatology',
            'Pediatrics'
        ];

        $stmt = $pdo->prepare("INSERT INTO specializations (name) VALUES (?)");

        foreach ($specs as $spec) {
            $stmt->execute([$spec]);
        }
    }

    // التحقق إذا يوجد مدير للنظام
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

    if ($checkAdmin == 0) {

        // تشفير كلمة المرور الافتراضية
        $hashedPassword = password_hash(
            "Admin@1234",
            PASSWORD_BCRYPT,
            ['cost' => 12]
        );

        // إضافة حساب المدير الافتراضي
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            'Admin',
            'admin@clinic.local',
            $hashedPassword,
            'admin'
        ]);
    }

    // رسالة نجاح
    echo "تم إنشاء قاعدة البيانات بنجاح.";

} catch (PDOException $e) {

    // إظهار الخطأ في حال فشل التنفيذ
    die("خطأ أثناء إنشاء قاعدة البيانات: " . $e->getMessage());
}