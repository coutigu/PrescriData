<?php
namespace Models;
use Database;
use PDO;

class Patient {
    public static function all() {
        $db = new Database();
        $stmt = $db->getPdo()->query("SELECT * FROM patients ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name, $age, $record_number = null, $dob = null, $sex = null, $lgpd_consent_date = null) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("INSERT INTO patients (name, age, record_number, dob, sex, lgpd_consent_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $record_number, $dob, $sex, $lgpd_consent_date]);
        return $db->getPdo()->lastInsertId();
    }

    public static function update($id, $name, $age, $record_number = null, $dob = null, $sex = null) {
        $db = new Database();
        return $db->updatePatient($id, $name, $age, $record_number, $dob, $sex);
    }

    public static function delete($id) {
        $db = new Database();
        return $db->deletePatient($id);
    }
}
