<?php
namespace Controllers;
use Core\Controller;
use Models\Calculation;

class AuditController extends Controller {
    public function index() {
        $this->requireAdmin(); // LGPD/Auditoria deve ser restrita
        $audits = Calculation::getAllWithDetails();
        $this->render('audit', ['audits' => $audits]);
    }
}
