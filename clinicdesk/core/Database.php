<?php
// core/Database.php

require_once __DIR__ . '/../config/database.php';

class Database {

    // تخزين نسخة واحدة من الاتصال
    private static $instance = null;

    private $pdo;

    // إنشاء الاتصال بقاعدة البيانات
    private function __construct() {

        try {

            $dsn = "mysql:host=" . DB_HOST .
                   ";dbname=" . DB_NAME .
                   ";charset=utf8mb4";

            $this->pdo = new PDO(
                $dsn,
                DB_USER,
                DB_PASS
            );

            // إعدادات PDO
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

        } catch (PDOException $e) {

            die(
                "❌ فشل الاتصال بقاعدة البيانات: " .
                $e->getMessage()
            );
        }
    }

    // الحصول على نسخة الاتصال
    public static function getInstance() {

        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // إرجاع كائن PDO
    public function getConnection() {

        return $this->pdo;
    }
}