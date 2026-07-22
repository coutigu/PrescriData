<?php
namespace Models;
use Database;
use PDO;

class Calculation {
    public static function create($patientId, $userId, $weight, $rateUnit, $nhdPercent, $resultsJson) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("INSERT INTO calculations (patient_id, user_id, weight, rate_unit, nhd_percent, results_json) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patientId, $userId, $weight, $rateUnit, $nhdPercent, $resultsJson]);
        return $db->getPdo()->lastInsertId();
    }

    public static function getByPatient($patientId) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("SELECT * FROM calculations WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllWithDetails() {
        $db = new Database();
        $sql = "
            SELECT 
                c.*, 
                p.name as patient_name,
                p.record_number,
                u.username as doctor_name
            FROM calculations c
            LEFT JOIN patients p ON c.patient_id = p.id
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
        ";
        $stmt = $db->getPdo()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
