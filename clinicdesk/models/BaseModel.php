<?php
// models/BaseModel.php

class BaseModel {

    protected $db;

    public function __construct() {

        // الحصول على اتصال قاعدة البيانات
        $this->db = Database::getInstance()->getConnection();
    }
}