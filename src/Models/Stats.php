<?php
namespace Models;
use Database;
use PDO;

class Stats {
    public static function getGeneralTotals() {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->query("SELECT 
            (SELECT COUNT(*) FROM patients) as total_patients,
            (SELECT COUNT(*) FROM calculations) as total_calculations,
            (SELECT AVG(weight) FROM calculations) as avg_weight,
            (SELECT AVG(nhd_percent) FROM calculations) as avg_nhd
        ");
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPatientsBySex() {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->query("SELECT 
            COALESCE(sex, 'N/I') as sex, 
            COUNT(*) as count 
            FROM patients 
            GROUP BY sex
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCalculationsByWeightRange() {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->query("SELECT 
            CASE 
                WHEN weight < 5 THEN '< 5kg'
                WHEN weight >= 5 AND weight < 10 THEN '5 - 10kg'
                WHEN weight >= 10 AND weight < 20 THEN '10 - 20kg'
                WHEN weight >= 20 AND weight < 40 THEN '20 - 40kg'
                ELSE '> 40kg'
            END as weight_range,
            COUNT(*) as count
            FROM calculations
            GROUP BY weight_range
            ORDER BY 
                CASE weight_range
                    WHEN '< 5kg' THEN 1
                    WHEN '5 - 10kg' THEN 2
                    WHEN '10 - 20kg' THEN 3
                    WHEN '20 - 40kg' THEN 4
                    ELSE 5
                END
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCalculationsByNhd() {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->query("SELECT 
            nhd_percent, 
            COUNT(*) as count 
            FROM calculations 
            GROUP BY nhd_percent 
            ORDER BY count DESC 
            LIMIT 10
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPatientsByAgeGroup() {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->query("SELECT dob FROM patients WHERE dob IS NOT NULL AND dob != ''");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $groups = [
            'Neonato (0-28 dias)' => 0,
            'Lactente (1m-2a)' => 0,
            'Pré-escolar (2a-6a)' => 0,
            'Escolar (6a-12a)' => 0,
            'Adolescente (>12a)' => 0
        ];
        
        $now = new \DateTime();
        
        foreach ($patients as $p) {
            try {
                $dob = new \DateTime($p['dob']);
                $diff = $now->diff($dob);
                $days = $diff->days;
                
                if ($days <= 28) {
                    $groups['Neonato (0-28 dias)']++;
                } elseif ($days <= (2 * 365)) {
                    $groups['Lactente (1m-2a)']++;
                } elseif ($days <= (6 * 365)) {
                    $groups['Pré-escolar (2a-6a)']++;
                } elseif ($days <= (12 * 365)) {
                    $groups['Escolar (6a-12a)']++;
                } else {
                    $groups['Adolescente (>12a)']++;
                }
            } catch (\Exception $e) {
                // Ignore invalid dates
            }
        }
        
        // Convert to array of key-value for chart
        $result = [];
        foreach ($groups as $group => $count) {
            if ($count > 0) { // Optional: only show groups with data
                $result[] = ['age_group' => $group, 'count' => $count];
            }
        }
        
        // If all are empty (no DOBs provided), just return empty
        if (empty($result)) {
            // Provide base empty structure if no one has DOB
            foreach ($groups as $group => $count) {
                 $result[] = ['age_group' => $group, 'count' => 0];
            }
        }
        
        return $result;
    }
}
