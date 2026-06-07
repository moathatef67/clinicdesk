<?php
// index.php



// باقي الكود الخاص بـ index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات النظام الأساسية
require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';
require_once 'core/Auth.php';
require_once 'core/CSRF.php';

// استدعاء المتحكمات
require_once 'models/AppointmentModel.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DoctorController.php';
require_once 'controllers/DoctorDashboardController.php';
require_once 'controllers/AppointmentController.php';
require_once 'controllers/ReportController.php';
require_once 'controllers/VitalsController.php';
require_once 'controllers/UserController.php';

// تحديد الصفحة والحركة المطلوبة
$page = isset($_GET['page'])
    ? sanitize($_GET['page'])
    : 'dashboard';

$action = isset($_GET['action'])
    ? sanitize($_GET['action'])
    : 'index';

// التحقق من تسجيل الدخول
if (!Auth::check() && $page !== 'auth') {

    redirect('auth', 'login');
}

// نظام التوجيه
switch ($page) {

case 'auth':

        $controller = new AuthController();

        if ($action === 'login') {

            require_once 'views/auth/login.php';

        } elseif ($action === 'loginProcess') {

            $controller->loginProcess();

        } elseif ($action === 'logout') {

            Auth::logout();
            redirect('auth', 'login');

        } elseif ($action === 'register') { // <--- أضف هذا الجزء
            $controller->register();
        }

        break;

    case 'dashboard':

        $role = Auth::user('role');

        if ($role === 'admin') {

            require_once 'views/dashboard/admin.php';

        } else {

            require_once 'views/partials/header.php';
            require_once 'views/partials/navbar.php';
            require_once 'views/partials/sidebar.php';

            echo "
            <div class='content-wrapper'>
                <div class='content-header'>
                    <div class='container-fluid text-right'>
                        <h1 class='m-0 text-dark'>
                            👋 أهلاً بك يا " . Auth::user('name') . "
                        </h1>

                        <p class='mt-2'>
                            أنت الآن داخل نظام ClinicDesk بصلاحية:
                            <b>" . strtoupper($role) . "</b>
                        </p>
                    </div>
                </div>
            </div>";

            require_once 'views/partials/footer.php';
        }

        break;

    case 'doctors':

        $controller = new DoctorController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'create') {

            $controller->create();

        } elseif ($action === 'store') {

            $controller->store();
        }

        break;

    // إدارة المستخدمين
    case 'users':

        $controller = new UserController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'create') {

            $controller->create();

        } elseif ($action === 'store') {

            $controller->store();

        } elseif ($action === 'delete') {

            $controller->delete();

        } elseif ($action === 'toggleActive') {

            $controller->toggleActive();
        }

        break;

    // إدارة المواعيد
    case 'appointments':

        $controller = new AppointmentController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'book') {

            $controller->book();

        } elseif ($action === 'store') {

            $controller->store();

        } elseif ($action === 'confirm') {

            $controller->confirm();

        } elseif ($action === 'cancel') {

            $controller->cancel();

        } elseif ($action === 'complete') {

            $controller->complete();

        } elseif ($action === 'updateStatus') {

            $controller->updateStatus();

        } elseif ($action === 'addPrescriptionView') {

            $controller->addPrescriptionView();

        } elseif ($action === 'storePrescription') {

            $controller->storePrescription();

        } elseif ($action === 'viewPrescription') {

            $controller->viewPrescription();
        }

        break;

    // إدارة التقارير
    case 'reports':

        $controller = new ReportController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'pending') {

            $controller->pendingAppointmentsList();

        } elseif ($action === 'all_appointments') {

            $controller->allAppointmentsList();

        } elseif ($action === 'patients') {

            $controller->patientsList();
        }

        break;

    // إدارة العلامات الحيوية
    case 'vitals':

        $controller = new VitalsController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'store') {

            $controller->store();
        }

        break;

    // لوحة تحكم الطبيب
    case 'doctor_dashboard':

        $controller = new DoctorDashboardController();

        if ($action === 'index') {

            $controller->index();

        } elseif ($action === 'upcoming') {

            $controller->upcoming();
        }

        break;

    default:

        require_once 'views/partials/header.php';
        require_once 'views/partials/navbar.php';
        require_once 'views/partials/sidebar.php';

        echo "
        <div class='content-wrapper text-center mt-5'>
            <h1 class='text-danger'>
                <i class='fas fa-exclamation-triangle'></i>
                الصفحة غير موجودة 404
            </h1>

            <p class='text-muted'>
                يرجى التأكد من الرابط أو الصفحة المطلوبة.
            </p>
        </div>";

        require_once 'views/partials/footer.php';

        break;
}