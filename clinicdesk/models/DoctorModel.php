<?php
// models/DoctorModel.php

require_once 'BaseModel.php';

class DoctorModel extends BaseModel {

    // جلب جميع الأطباء
    public function getAllDoctors() {

        $sql = "SELECT doctors.*,
                       users.name,
                       users.email,
                       users.is_active,
                       specializations.name as specialization_name
                FROM doctors
                JOIN users
                    ON doctors.user_id = users.id
                JOIN specializations
                    ON doctors.specialization_id = specializations.id
                ORDER BY doctors.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // جلب جميع التخصصات
    public function getAllSpecializations() {

        $stmt = $this->db->prepare("
            SELECT *
            FROM specializations
            ORDER BY name ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // إضافة طبيب جديد
    public function createDoctor($data) {

        try {

            // بدء المعاملة
            $this->db->beginTransaction();

            // إضافة المستخدم
            $sqlUser = "INSERT INTO users
                        (name, email, password, role)
                        VALUES (?, ?, ?, 'doctor')";

            $stmtUser = $this->db->prepare($sqlUser);

            $stmtUser->execute([
                $data['name'],
                $data['email'],
                $data['password']
            ]);

            // الحصول على رقم المستخدم الجديد
            $userId = $this->db->lastInsertId();

            // إضافة بيانات الطبيب
            $sqlDoc = "INSERT INTO doctors
                       (
                           user_id,
                           specialization_id,
                           bio,
                           consultation_fee,
                           available_days
                       )
                       VALUES (?, ?, ?, ?, ?)";

            $stmtDoc = $this->db->prepare($sqlDoc);

            $stmtDoc->execute([
                $userId,
                $data['specialization_id'],
                $data['bio'],
                $data['consultation_fee'],
                $data['available_days']
            ]);

            // حفظ التغييرات
            $this->db->commit();

            return true;

        } catch (Exception $e) {

            // التراجع في حال حدوث خطأ
            $this->db->rollBack();

            return false;
        }
    }
}