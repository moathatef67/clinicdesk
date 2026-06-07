<?php
// models/UserModel.php

class UserModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. تسجيل مستخدم جديد (مريض)
    public function registerUser($name, $email, $password) {
        // تشفير كلمة المرور (أمان)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // إدخال المستخدم بدور 'patient' افتراضياً
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    // 2. جلب المستخدمين مع البحث والفلترة والترقيم
    public function getUsersPaginated($page, $limit, $search = null, $role = null) {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        $sql .= " LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. حساب إجمالي عدد المستخدمين (لعمل pagination صحيحة)
    public function getTotalUsers($search = null, $role = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }
    
    // إضافية: التحقق من البريد قبل التسجيل لمنع التكرار
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ? true : false;
    }
}