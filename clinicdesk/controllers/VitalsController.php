<?php
// controllers/VitalsController.php

require_once __DIR__ . '/../models/VitalsModel.php';

class VitalsController {

    private $vitalsModel;

    public function __construct() {

        // نظام العلامات الحيوية مخصص للممرض فقط
        Auth::requireRole('nurse');

        $this->vitalsModel = new VitalsModel();
    }

    /**
     * عرض صفحة تسجيل العلامات الحيوية
     */
    public function index() {

        $patients = $this->vitalsModel->getPatientsList();

        require_once __DIR__ . '/../views/vitals/index.php';
    }

    /**
     * حفظ العلامات الحيوية للمريض
     */
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('vitals', 'index');
        }

        if (!CSRF::validate($_POST['csrf_token'] ?? '')) {

            $_SESSION['error'] = "انتهت صلاحية الجلسة.";

            redirect('vitals', 'index');
        }

        $data = [
            'patient_id'      => intval($_POST['patient_id']),
            'blood_pressure'  => sanitize($_POST['blood_pressure']),
            'heart_rate'      => sanitize($_POST['heart_rate']),
            'temperature'     => sanitize($_POST['temperature'])
        ];

        if ($this->vitalsModel->insertVitals($data)) {

            $_SESSION['success'] =
                "تم تسجيل العلامات الحيوية بنجاح.";

        } else {

            $_SESSION['error'] =
                "حدث خطأ أثناء حفظ البيانات.";
        }

        redirect('vitals', 'index');
    }
}