<?php

class Database {
    private $pdo;

    public function __construct() {
        $dbDir = __DIR__ . '/../db';
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        $dbPath = $dbDir . '/database.sqlite';
        $this->pdo = new PDO('sqlite:' . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->init();
    }

    private function init() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                role TEXT DEFAULT 'user'
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS patients (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                age TEXT NOT NULL,
                record_number TEXT,
                dob DATE,
                sex TEXT,
                lgpd_consent_date DATETIME
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS calculations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                patient_id INTEGER NOT NULL,
                user_id INTEGER,
                weight REAL NOT NULL,
                rate_unit TEXT NOT NULL,
                nhd_percent REAL NOT NULL,
                results_json TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(patient_id) REFERENCES patients(id),
                FOREIGN KEY(user_id) REFERENCES users(id)
            )
        ");

        // Simple schema migration for existing tables
        try { $this->pdo->exec("ALTER TABLE users ADD COLUMN role TEXT DEFAULT 'user'"); } catch(Exception $e) {}
        try { $this->pdo->exec("ALTER TABLE patients ADD COLUMN record_number TEXT"); } catch(Exception $e) {}
        try { $this->pdo->exec("ALTER TABLE patients ADD COLUMN dob DATE"); } catch(Exception $e) {}
        try { $this->pdo->exec("ALTER TABLE patients ADD COLUMN sex TEXT"); } catch(Exception $e) {}
        try { $this->pdo->exec("ALTER TABLE patients ADD COLUMN lgpd_consent_date DATETIME"); } catch(Exception $e) {}
        try { $this->pdo->exec("ALTER TABLE calculations ADD COLUMN user_id INTEGER"); } catch(Exception $e) {}

        // Create default admin user if none exists
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $this->createUser('admin', 'admin123', 'admin');
        }
    }

    public function createUser($username, $password, $role = 'user') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $role]);
    }

    public function verifyUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user['id'];
        }
        return false;
    }

    public function addPatient($name, $age) {
        $stmt = $this->pdo->prepare("INSERT INTO patients (name, age) VALUES (?, ?)");
        $stmt->execute([$name, $age]);
        return $this->pdo->lastInsertId();
    }

    public function getPatients() {
        $stmt = $this->pdo->query("SELECT * FROM patients ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPatient($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePatient($id, $name, $age, $record_number, $dob, $sex) {
        $stmt = $this->pdo->prepare("UPDATE patients SET name = ?, age = ?, record_number = ?, dob = ?, sex = ? WHERE id = ?");
        return $stmt->execute([$name, $age, $record_number, $dob, $sex, $id]);
    }

    public function deletePatient($id) {
        // Also delete associated calculations to maintain integrity
        $stmt1 = $this->pdo->prepare("DELETE FROM calculations WHERE patient_id = ?");
        $stmt1->execute([$id]);
        
        $stmt2 = $this->pdo->prepare("DELETE FROM patients WHERE id = ?");
        return $stmt2->execute([$id]);
    }

    public function addCalculation($patientId, $weight, $rateUnit, $nhdPercent, $resultsJson) {
        $stmt = $this->pdo->prepare("INSERT INTO calculations (patient_id, weight, rate_unit, nhd_percent, results_json) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$patientId, $weight, $rateUnit, $nhdPercent, $resultsJson]);
        return $this->pdo->lastInsertId();
    }

    public function getCalculations($patientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM calculations WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPdo() {
        return $this->pdo;
    }
}
