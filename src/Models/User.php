<?php
namespace Models;
use Database;
use PDO;

class User {
    public static function verify($username, $password) {
        $db = new Database();
        $pdo = $db->getPdo();
        $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public static function getAll() {
        $db = new Database();
        return $db->getPdo()->query("SELECT id, username, role FROM users ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("SELECT id, username, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($username, $password, $role) {
        $db = new Database();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getPdo()->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $role]);
    }

    public static function updateRole($id, $role) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public static function updatePassword($id, $password) {
        $db = new Database();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getPdo()->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public static function delete($id) {
        $db = new Database();
        $stmt = $db->getPdo()->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
