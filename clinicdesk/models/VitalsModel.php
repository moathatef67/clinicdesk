<?php
// models/VitalsModel.php

require_once 'BaseModel.php';

class VitalsModel extends BaseModel {

    // جلب قائمة المرضى
    public function getPatientsList() {

        $sql = "SELECT
                    patients.id as patient_id,
                    users.name,
                    users.email
                FROM patients
                JOIN users
                    ON patients.user_id = users.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // حفظ العلامات الحيوية للمريض
    public function insertVitals($data) {

        $sql = "INSERT INTO vitals
                (
                    patient_id,
                    blood_pressure,
                    heart_rate,
                    temperature,
                    recorded_at
                )
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['patient_id'],
            $data['blood_pressure'],
            $data['heart_rate'],
            $data['temperature']
        ]);
    }
}