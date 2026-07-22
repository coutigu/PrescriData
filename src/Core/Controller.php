<?php
namespace Core;

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layout.php';
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($route) {
        header('Location: index.php?route=' . $route);
        exit;
    }

    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('');
            exit;
        }
    }
}
