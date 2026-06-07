<?php
// controllers/ReportController.php

class ReportController {

    public function __construct() {

        // السماح للأدمن فقط بالدخول إلى صفحة التقارير
        Auth::requireRole('admin');
    }

    // الصفحة الرئيسية للتقارير والإحصائيات
    public function index() {

        $db = Database::getInstance()->getConnection();

        // حساب عدد الأطباء
        $stmt = $db->query("
            SELECT COUNT(*) as total
            FROM doctors
        ");
        $totalDoctors = $stmt->fetch()['total'];

        // حساب عدد المرضى
        $stmt = $db->query("
            SELECT COUNT(*) as total
            FROM users
            WHERE role = 'patient'
        ");
        $totalPatients = $stmt->fetch()['total'];

        try {

            // حساب إجمالي عدد المواعيد
            $stmt = $db->query("
                SELECT COUNT(*) as total
                FROM appointments
            ");
            $totalAppointments = $stmt->fetch()['total'];

            // حساب عدد المواعيد المعلقة
            $stmt = $db->query("
                SELECT COUNT(*) as total
                FROM appointments
                WHERE status = 'pending'
            ");
            $pendingAppointments = $stmt->fetch()['total'];

        } catch (Exception $e) {

            $totalAppointments = 0;
            $pendingAppointments = 0;
        }

        // عرض صفحة التقارير الرئيسية
        require_once dirname(__DIR__) .
            '/views/reports/index.php';
    }

    // عرض جميع المواعيد المعلقة
    public function pendingAppointmentsList() {

        Auth::requireRole('admin');

        $db = Database::getInstance()->getConnection();

        try {

            // جلب المواعيد التي حالتها معلقة
            $stmt = $db->prepare("
                SELECT
                    a.*,
                    u1.name as doctor_name,
                    u2.name as patient_name
                FROM appointments a
                LEFT JOIN users u1
                    ON a.doctor_id = u1.id
                LEFT JOIN users u2
                    ON a.patient_id = u2.id
                WHERE a.status = 'pending'
                ORDER BY a.appt_date ASC,
                         a.appt_time ASC
            ");

            $stmt->execute();

            $filteredAppointments =
                $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            $filteredAppointments = [];
        }

        // عرض صفحة المواعيد المعلقة
        require_once dirname(__DIR__) .
            '/views/reports/pending_appointments.php';
    }

    // عرض جميع المواعيد المسجلة بالنظام
    public function allAppointmentsList() {

        Auth::requireRole('admin');

        $db = Database::getInstance()->getConnection();

        try {

            // جلب جميع المواعيد مع اسم الطبيب والمريض
            $stmt = $db->query("
                SELECT
                    a.*,
                    u1.name as doctor_name,
                    u2.name as patient_name
                FROM appointments a
                LEFT JOIN users u1
                    ON a.doctor_id = u1.id
                LEFT JOIN users u2
                    ON a.patient_id = u2.id
                ORDER BY a.appt_date DESC
            ");

            $allAppointments =
                $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            $allAppointments = [];
        }

        // عرض صفحة جميع المواعيد
        require_once dirname(__DIR__) .
            '/views/reports/all_appointments.php';
    }

    // عرض قائمة المرضى المسجلين
    public function patientsList() {

        Auth::requireRole('admin');

        $db = Database::getInstance()->getConnection();

        try {

            // جلب جميع المستخدمين من نوع مريض
            $stmt = $db->query("
                SELECT
                    id,
                    name,
                    email,
                    created_at
                FROM users
                WHERE role = 'patient'
                ORDER BY id DESC
            ");

            $patients =
                $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            $patients = [];
        }

        // عرض صفحة المرضى
        require_once dirname(__DIR__) .
            '/views/reports/patients_list.php';
    }
}