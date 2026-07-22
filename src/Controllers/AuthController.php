<?php
namespace Controllers;
use Core\Controller;
use Models\User;

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = User::verify($username, $password);
            
            if ($user) {
                // Previne fixação de sessão regenerando o ID no login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $this->redirect('');
            } else {
                $error = "Credenciais inválidas.";
                $this->render('login', ['error' => $error]);
                return;
            }
        }
        $this->render('login');
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}
