<?php
namespace Controllers;
use Core\Controller;
use Models\User;

class UserController extends Controller {

    public function index() {
        $this->requireAdmin();
        $users = User::getAll();
        $this->render('users', ['users' => $users]);
    }

    public function add() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            if (empty($username) || empty($password)) {
                $this->render('user_form', ['error' => 'Preencha todos os campos.']);
                return;
            }
            
            try {
                User::create($username, $password, $role);
                $this->redirect('users');
            } catch (\Exception $e) {
                $this->render('user_form', ['error' => 'Erro ao criar usuário. Talvez o nome de usuário já exista.']);
            }
            return;
        }
        
        $this->render('user_form');
    }

    public function edit() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect('users');
            return;
        }
        
        $user = User::getById($id);
        if (!$user) {
            $this->redirect('users');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? 'user';
            $password = $_POST['password'] ?? '';
            
            // Cannot change own role to something else if admin, to prevent locking out
            if ($id == $_SESSION['user_id'] && $role !== 'admin') {
                $this->render('user_form', ['user' => $user, 'error' => 'Você não pode remover seus próprios privilégios de administrador.']);
                return;
            }

            User::updateRole($id, $role);
            
            if (!empty($password)) {
                User::updatePassword($id, $password);
            }
            
            $this->redirect('users');
            return;
        }
        
        $this->render('user_form', ['user' => $user]);
    }

    public function delete() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        
        if ($id && $id != $_SESSION['user_id']) {
            User::delete($id);
        }
        
        $this->redirect('users');
    }

    public function profile() {
        $id = $_SESSION['user_id'];
        $user = User::getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            
            $verified = User::verify($user['username'], $current_password);
            
            if (!$verified) {
                $this->render('profile', ['error' => 'Senha atual incorreta.']);
                return;
            }
            
            if (empty($new_password)) {
                $this->render('profile', ['error' => 'A nova senha não pode ser vazia.']);
                return;
            }

            User::updatePassword($id, $new_password);
            $this->render('profile', ['success' => 'Senha atualizada com sucesso!']);
            return;
        }

        $this->render('profile', ['user' => $user]);
    }
}
