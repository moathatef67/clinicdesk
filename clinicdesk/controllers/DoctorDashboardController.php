<?php
// controllers/DoctorDashboardController.php

require_once __DIR__ . '/../models/AppointmentModel.php';

class DoctorDashboardController {

    private $appointmentModel;

    public function __construct() {

        // السماح للطبيب فقط بالدخول للوحة التحكم الخاصة به
        Auth::requireRole('doctor');

        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * الصفحة الرئيسية للطبيب
     * تعرض مواعيد اليوم والمواعيد القادمة
     */
    public function index() {

        $doctorUserId = Auth::user('id');

        $todayAppointments = $this->appointmentModel
            ->getDoctorTodayAppointments($doctorUserId);

        $upcomingAppointments = $this->appointmentModel
            ->getDoctorUpcomingAppointments($doctorUserId);

        require_once __DIR__ . '/../views/doctor/dashboard.php';
    }

    /**
     * عرض المواعيد القادمة فقط
     */
    public function upcoming() {

        $doctorUserId = Auth::user('id');

        $upcomingAppointments = $this->appointmentModel
            ->getDoctorUpcomingAppointments($doctorUserId);

        require_once __DIR__ . '/../views/doctor/upcoming_appointments.php';
    }
}