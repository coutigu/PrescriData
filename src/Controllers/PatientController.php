<?php
namespace Controllers;
use Core\Controller;
use Models\Patient;

class PatientController extends Controller {
    public function index() {
        $patients = Patient::all();
        $this->render('patients', ['patients' => $patients]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $age = $_POST['age'] ?? '';
            // New optional fields
            $record_number = $_POST['record_number'] ?? null;
            $dob = $_POST['dob'] ?? null;
            $sex = $_POST['sex'] ?? null;
            $lgpd_consent = isset($_POST['lgpd_consent']) ? true : false;

            if ($name && $age && $lgpd_consent) {
                $lgpd_consent_date = date('Y-m-d H:i:s');
                Patient::create($name, $age, $record_number, $dob, $sex, $lgpd_consent_date);
                $this->redirect('');
            } else {
                $error = "Preencha todos os campos obrigatórios e aceite os termos da LGPD.";
                $this->render('patient_add', ['error' => $error]);
                return;
            }
        }
        $this->render('patient_add');
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('');
            return;
        }

        $patient = Patient::find($id);
        if (!$patient) {
            $this->redirect('');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $age = $_POST['age'] ?? '';
            $record_number = $_POST['record_number'] ?? null;
            $dob = $_POST['dob'] ?? null;
            $sex = $_POST['sex'] ?? null;

            if ($name && $age) {
                Patient::update($id, $name, $age, $record_number, $dob, $sex);
                $this->redirect('');
            } else {
                $error = "Preencha todos os campos obrigatórios (Nome e Idade).";
                $this->render('patient_add', ['patient' => $patient, 'error' => $error]);
                return;
            }
        }
        $this->render('patient_add', ['patient' => $patient]);
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Patient::delete($id);
        }
        $this->redirect('');
    }
}
