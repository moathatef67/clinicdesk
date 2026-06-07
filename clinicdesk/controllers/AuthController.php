<?php
// controllers/AuthController.php

class AuthController {

    /**
     * معالجة عملية تسجيل الدخول
     */
    public function loginProcess() {

        if (!isset($_POST['csrf_token']) || !CSRF::validate($_POST['csrf_token'])) {
            $_SESSION['error'] = "انتهت صلاحية الجلسة.";
            redirect('auth', 'login');
        }

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            if ((int)$user['is_active'] === 0) {
                $_SESSION['error'] = "الحساب معطل من قبل الإدارة.";
                redirect('auth', 'login');
            }

            Auth::login($user);

            redirect('dashboard', 'index');
        }

        $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
        redirect('auth', 'login');
    }

    /**
     * تسجيل الخروج من النظام
     */
    public function logout() {

        Auth::logout();

        redirect('auth', 'login');
    }



    public function register() {
        // 1. نبدأ بالتحقق من أن الطلب هو POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // نأخذ البيانات من النموذج
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new UserModel();

            // 2. التحقق أولاً: هل الإيميل موجود؟
            if ($userModel->emailExists($email)) {
                $_SESSION['error'] = "عذراً، هذا البريد مسجل مسبقاً!";
            } 
            // 3. إذا لم يكن موجوداً، نقوم بالتسجيل
            else {
                if ($userModel->registerUser($name, $email, $password)) {
                    $_SESSION['success'] = "تم التسجيل بنجاح، يمكنك تسجيل الدخول.";
                    header('Location: index.php?page=auth&action=login');
                    exit; // مهم جداً التوقف بعد التحويل
                } else {
                    $_SESSION['error'] = "حدث خطأ غير متوقع أثناء التسجيل.";
                }
            }
        }

        // 4. عرض صفحة التسجيل (هذا السطر خارج الـ IF لأنه يجب أن يظهر في الحالتين)
        require_once __DIR__ . '/../views/auth/register.php';
    }
}