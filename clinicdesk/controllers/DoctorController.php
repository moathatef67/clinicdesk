<?php
// controllers/DoctorController.php

require_once __DIR__ . '/../models/DoctorModel.php';

class DoctorController {

    private $doctorModel;

    public function __construct() {

        // إدارة الأطباء متاحة للمشرف فقط
        Auth::requireRole('admin');

        $this->doctorModel = new DoctorModel();
    }

    /**
     * عرض جميع الأطباء
     */
    public function index() {

        $doctors = $this->doctorModel->getAllDoctors();

        require_once __DIR__ . '/../views/doctor/index.php';
    }

    /**
     * فتح صفحة إضافة طبيب جديد
     */
    public function create() {

        $specializations = $this->doctorModel->getAllSpecializations();

        require_once __DIR__ . '/../views/doctors/create.php';
    }

    /**
     * حفظ بيانات الطبيب الجديد
     */
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('doctors', 'index');
        }

        if (!CSRF::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "انتهت صلاحية الجلسة.";
            redirect('doctors', 'create');
        }

        $data = [
            'name' => sanitize($_POST['name']),
            'email' => sanitize($_POST['email']),
            'password' => password_hash(
                $_POST['password'],
                PASSWORD_BCRYPT
            ),
            'specialization_id' => intval($_POST['specialization_id']),
            'consultation_fee' => floatval($_POST['consultation_fee']),
            'bio' => sanitize($_POST['bio']),
            'available_days' => isset($_POST['days'])
                ? implode(',', $_POST['days'])
                : ''
        ];

        if ($this->doctorModel->createDoctor($data)) {

            $_SESSION['success'] = "تم إضافة الطبيب بنجاح.";

            redirect('doctors', 'index');
        }

        $_SESSION['error'] = "فشل إضافة الطبيب.";

        redirect('doctors', 'create');
    }
}