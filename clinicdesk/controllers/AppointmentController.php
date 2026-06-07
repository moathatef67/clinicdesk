<?php
// controllers/AppointmentController.php

require_once __DIR__ . '/../models/AppointmentModel.php';

class AppointmentController {

    private $appointmentModel;

    public function __construct() {

        // السماح للمريض أو الدكتور فقط بالدخول
        Auth::requireRole(['patient', 'doctor']);

        // إنشاء كائن من الموديل
        $this->appointmentModel = new AppointmentModel();
    }

    // عرض المواعيد حسب نوع المستخدم
    public function index() {

        $role = Auth::user('role');

        if ($role === 'patient') {

            // جلب مواعيد المريض
            $appointments = $this->appointmentModel
                ->getPatientAppointments(Auth::user('id'));

            require_once __DIR__ .
                '/../views/appointments/index.php';

        } elseif ($role === 'doctor') {

            // تحديد نوع المواعيد المطلوب عرضها
            $action = $_GET['action'] ?? 'index';

            if ($action === 'upcoming') {

                // جلب المواعيد القادمة
                $appointments = $this->appointmentModel
                    ->getDoctorUpcomingAppointments(Auth::user('id'));

            } else {

                // جلب مواعيد اليوم
                $appointments = $this->appointmentModel
                    ->getDoctorTodayAppointments(Auth::user('id'));
            }

            require_once __DIR__ .
                '/../views/appointments/doctor_index.php';
        }
    }

    // فتح صفحة حجز موعد جديد
    public function book() {

        Auth::requireRole('patient');

        // جلب قائمة الأطباء المتاحين
        $doctors = $this->appointmentModel
            ->getActiveDoctors();

        require_once __DIR__ .
            '/../views/appointments/book.php';
    }

    // حفظ بيانات الحجز
    public function store() {

        Auth::requireRole('patient');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // التحقق من رمز الحماية
            CSRF::validate($_POST['csrf_token'] ?? '');

            $db = Database::getInstance()->getConnection();

            // جلب رقم المريض
            $stmt = $db->prepare(
                "SELECT id FROM patients WHERE user_id = ? LIMIT 1"
            );

            $stmt->execute([Auth::user('id')]);

            $patient = $stmt->fetch();

            // استقبال البيانات من الفورم
            $appointment_date =
                sanitize($_POST['appointment_date'] ?? '');

            $appointment_time =
                sanitize($_POST['appointment_time'] ?? '');

            $data = [
                'patient_id' => $patient['id'],
                'doctor_id' => intval($_POST['doctor_id']),
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time
            ];

            // منع الحجز بتاريخ سابق
            $today = date('Y-m-d');

            if ($data['appointment_date'] < $today) {

                $_SESSION['error'] =
                    "❌ لا يمكن حجز موعد بتاريخ سابق.";

                redirect('appointments', 'book');
                return;
            }

            // التأكد أن الموعد غير محجوز
            if (
                $this->appointmentModel->isSlotBooked(
                    $data['doctor_id'],
                    $data['appointment_date'],
                    $data['appointment_time']
                )
            ) {

                $_SESSION['error'] =
                    "❌ هذا الموعد محجوز مسبقاً.";

                redirect('appointments', 'book');
                return;
            }

            // حفظ بيانات الموعد
            if ($this->appointmentModel->createAppointment($data)) {

                $_SESSION['success'] =
                    "✅ تم حجز الموعد بنجاح.";

                redirect('appointments', 'index');

            } else {

                $_SESSION['error'] =
                    "❌ فشل حجز الموعد.";

                redirect('appointments', 'book');
            }
        }
    }
        // دالة لتأكيد الموعد
        public function confirm() {

            Auth::requireRole('doctor');

            $appointmentId = intval($_GET['id'] ?? 0);

            if (
                $appointmentId > 0 &&
                $this->appointmentModel->updateStatus(
                    $appointmentId,
                    'confirmed'
                )
            ) {

                $_SESSION['success'] =
                    "✅ تم تأكيد الموعد بنجاح.";

            } else {

                $_SESSION['error'] =
                    "❌ فشل تأكيد الموعد.";
            }

            header("Location: index.php?page=doctor_dashboard");
            exit();
        }

    // دالة إلغاء الموعد
    public function cancel() {

        Auth::requireRole(['doctor', 'patient']);

        $appointmentId = intval($_GET['id'] ?? 0);

        if (
            $appointmentId > 0 &&
            $this->appointmentModel->updateStatus(
                $appointmentId,
                'cancelled'
            )
        ) {

            $_SESSION['success'] =
                "✅ تم إلغاء الموعد.";

        } else {

            $_SESSION['error'] =
                "❌ فشل إلغاء الموعد.";
        }

        header("Location: index.php?page=doctor_dashboard");
        exit();
    }

    // تحديث حالة الموعد
    public function updateStatus() {

        Auth::requireRole('doctor');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // التحقق من رمز الحماية
            $token = $_POST['csrf_token'] ?? '';

            if (!CSRF::validate($token)) {

                $_SESSION['error'] =
                    "❌ انتهت صلاحية الجلسة.";

                header("Location: index.php?page=appointments");
                exit();
            }

            // رقم الموعد المطلوب تحديثه
            $appointmentId =
                intval($_POST['appointment_id'] ?? 0);

            // الحالة الجديدة للموعد
            $status =
                sanitize($_POST['status'] ?? '');

            if ($appointmentId > 0 && !empty($status)) {

                if (
                    $this->appointmentModel
                        ->updateStatus($appointmentId, $status)
                ) {

                    $_SESSION['success'] =
                        "✅ تم تحديث الحالة.";

                } else {

                    $_SESSION['error'] =
                        "❌ فشل تحديث الحالة.";
                }

            } else {

                $_SESSION['error'] =
                    "❌ البيانات غير مكتملة.";
            }

            // تحديد الصفحة التي سيتم الرجوع إليها
            $source = $_POST['source'] ?? 'index';

            if ($source === 'upcoming') {

                header(
                    "Location: index.php?page=appointments&action=upcoming"
                );

            } else {

                header(
                    "Location: index.php?page=appointments"
                );
            }

            exit();
        }

        header("Location: index.php?page=appointments");
        exit();
    }
    // إنهاء الموعد وتحويله إلى مكتمل
    public function complete() {

        Auth::requireRole('doctor');

        $appointmentId = intval($_GET['id'] ?? 0);

        if (
            $appointmentId > 0 &&
            $this->appointmentModel->updateStatus(
                $appointmentId,
                'completed'
            )
        ) {

            $_SESSION['success'] =
                "✅ تم إنهاء الكشف بنجاح.";

        } else {

            $_SESSION['error'] =
                "❌ فشل إنهاء الكشف.";
        }

        header("Location: index.php?page=doctor_dashboard");
        exit();
    }

    // دالة لعرض صفحة إضافة روشتة
    public function addPrescriptionView() {
            Auth::requireRole('doctor');

            $appointmentId = intval($_GET['id'] ?? 0);
            
            $appointment = $this->appointmentModel->getAppointmentById($appointmentId);

            if (!$appointment) {
                $_SESSION['error'] = "❌ الموعد غير موجود.";
                header("Location: index.php?page=appointments");
                exit();
            }

            // تأكد من تمرير البيانات للـ View
            require_once __DIR__ . '/../views/appointments/add_prescription.php';
    }


    public function storePrescription() {
        Auth::requireRole('doctor');

        // 1. التحقق من أن الطلب POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=appointments");
            exit();
        }

        // 2. التحقق من رمز الحماية (مهم جداً)
        if (!CSRF::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "❌ جلسة غير صالحة.";
            header("Location: index.php?page=appointments");
            exit();
        }

        $appointment_id = intval($_POST['appointment_id'] ?? 0);
        $text           = $_POST['prescription_text'] ?? '';
        $doctor_id      = $_SESSION['user_id'] ?? null;
        $filePath       = null; // متغير لتخزين مسار الملف

        // 3. معالجة رفع الملف
        if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/prescriptions/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileName = time() . '_' . basename($_FILES['prescription_file']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['prescription_file']['tmp_name'], $targetPath)) {
                $filePath = $targetPath; // تخزين المسار للذهاب لقاعدة البيانات
            }
        }

        // 4. جلب بيانات الموعد للتأكد من وجوده
        $appointment = $this->appointmentModel->getAppointmentById($appointment_id);
        if (!$appointment) {
            $_SESSION['error'] = "❌ الموعد غير موجود.";
            header("Location: index.php?page=appointments");
            exit();
        }
        
        $patient_id = $appointment['patient_id'];

        // 5. الحفظ في قاعدة البيانات (تأكد أن الموديل يستقبل الـ filePath)
        // لاحظ أننا أضفنا $filePath للدالة
        $result = $this->appointmentModel->addPrescription($appointment_id, $text, $doctor_id, $patient_id, $filePath);

        if ($result) {
            $this->appointmentModel->updateStatus($appointment_id, 'completed');
            $_SESSION['success'] = "✅ تم حفظ الروشتة بنجاح.";
            header("Location: index.php?page=appointments");
        } else {
            $_SESSION['error'] = "❌ حدث خطأ أثناء حفظ الروشتة.";
            header("Location: index.php?page=appointments&action=addPrescriptionView&id=" . $appointment_id);
        }
        exit();
    }

// عرض تفاصيل الروشتة
    public function viewPrescription() {
        $id = intval($_GET['id'] ?? 0);

        if ($id === 0) {
            header("Location: index.php?page=appointments");
            exit();
        }

        $appointment = $this->appointmentModel->getAppointmentById($id);

        if (!$appointment) {
            $_SESSION['error'] = "❌ الموعد غير موجود.";
            header("Location: index.php?page=appointments");
            exit();
        }

        // --- التعديل هنا ---
        // نمرر البيانات للمتغير الذي تتوقعه صفحة الـ View
        $prescription = $appointment; 
        $prescription = $this->appointmentModel->getPrescriptionByAppointmentId($id);

        require_once 'views/appointments/view_prescription.php';
    }


}