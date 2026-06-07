<?php
// models/AppointmentModel.php

require_once 'BaseModel.php';

class AppointmentModel extends BaseModel {


public function getAppointmentById($id) {
    $db = Database::getInstance()->getConnection();
    
    // تأكد من الربط بين الموعد (appointments)، المريض (patients)، والمستخدم (users)
    $stmt = $db->prepare("
        SELECT a.*, 
               u.name AS patient_name
        FROM appointments a
        JOIN patients pt ON a.patient_id = pt.id
        JOIN users u ON pt.user_id = u.id
        WHERE a.id = ?
    ");
    
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    // جلب الأطباء المتاحين للحجز
    public function getActiveDoctors() {

        $sql = "SELECT doctors.id,
                       users.name,
                       specializations.name as specialization_name,
                       doctors.consultation_fee,
                       doctors.available_days
                FROM doctors
                JOIN users
                    ON doctors.user_id = users.id
                JOIN specializations
                    ON doctors.specialization_id = specializations.id
                WHERE users.is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // إنشاء موعد جديد
    public function createAppointment($data) {

        $sql = "INSERT INTO appointments
                (patient_id, doctor_id, appt_date, appt_time, status)
                VALUES (?, ?, ?, ?, 'pending')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['patient_id'],
            $data['doctor_id'],
            $data['appointment_date'],
            $data['appointment_time']
        ]);
    }

    // التحقق إذا كان الموعد محجوزاً مسبقاً
    public function isSlotBooked(
        $doctorId,
        $apptDate,
        $apptTime
    ) {

        $sql = "SELECT id
                FROM appointments
                WHERE doctor_id = ?
                AND appt_date = ?
                AND appt_time = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $doctorId,
            $apptDate,
            $apptTime
        ]);

        return $stmt->fetch() ? true : false;
    }

    // جلب مواعيد المريض
    public function getPatientAppointments($patientUserId) {

        $sql = "SELECT appointments.*,
                       users.name as doctor_name,
                       specializations.name as spec_name
                FROM appointments
                JOIN doctors
                    ON appointments.doctor_id = doctors.id
                JOIN users
                    ON doctors.user_id = users.id
                JOIN specializations
                    ON doctors.specialization_id = specializations.id
                WHERE appointments.patient_id =
                    (
                        SELECT id
                        FROM patients
                        WHERE user_id = ?
                        LIMIT 1
                    )
                ORDER BY appointments.appt_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$patientUserId]);

        return $stmt->fetchAll();
    }

    // جلب جميع مواعيد الطبيب
    public function getDoctorAppointments($doctorUserId) {

        $sql = "SELECT appointments.*,
                       users.name as patient_name
                FROM appointments
                JOIN patients
                    ON appointments.patient_id = patients.id
                JOIN users
                    ON patients.user_id = users.id
                WHERE appointments.doctor_id =
                    (
                        SELECT id
                        FROM doctors
                        WHERE user_id = ?
                        LIMIT 1
                    )
                ORDER BY appointments.appt_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorUserId]);

        return $stmt->fetchAll();
    }

    // تحديث حالة الموعد
    public function updateStatus($appointmentId, $status) {

        $sql = "UPDATE appointments
                SET status = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $status,
            $appointmentId
        ]);
    }

    // جلب مواعيد اليوم للطبيب
    public function getDoctorTodayAppointments($doctorUserId) {

        $sql = "SELECT appointments.*,
                       users.name as patient_name
                FROM appointments
                JOIN patients
                    ON appointments.patient_id = patients.id
                JOIN users
                    ON patients.user_id = users.id
                WHERE appointments.doctor_id =
                    (
                        SELECT id
                        FROM doctors
                        WHERE user_id = ?
                        LIMIT 1
                    )
                AND appointments.appt_date = CURDATE()
                ORDER BY appointments.appt_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorUserId]);

        return $stmt->fetchAll();
    }

    // جلب المواعيد القادمة للطبيب
    public function getDoctorUpcomingAppointments($doctorUserId) {

        $sql = "SELECT appointments.*,
                       users.name as patient_name
                FROM appointments
                JOIN patients
                    ON appointments.patient_id = patients.id
                JOIN users
                    ON patients.user_id = users.id
                WHERE appointments.doctor_id =
                    (
                        SELECT id
                        FROM doctors
                        WHERE user_id = ?
                        LIMIT 1
                    )
                AND appointments.appt_date > CURDATE()
                ORDER BY appointments.appt_date ASC,
                         appointments.appt_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorUserId]);

        return $stmt->fetchAll();
    }


    // إضافة الروشتة لقاعدة البيانات
    public function addPrescription($appointment_id, $text, $doctor_id, $patient_id, $filePath) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            INSERT INTO prescriptions (appointment_id, doctor_id, patient_id, prescription_text, file_path) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([$appointment_id, $doctor_id, $patient_id, $text, $filePath]);
    }
        
    public function getPrescriptionByAppointmentId($appointment_id) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT p.*, u.name AS doctor_name 
            FROM prescriptions p
            JOIN users u ON p.doctor_id = u.id
            WHERE p.appointment_id = ?
        ");
        
        $stmt->execute([$appointment_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}