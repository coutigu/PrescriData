<?php
namespace Controllers;
use Core\Controller;
use Models\Patient;
use Models\Calculation;
use HydrationCalculator;
use Exception;

class CalculatorController extends Controller {
    public function view($patientId) {
        $patient = Patient::find($patientId);
        if (!$patient) {
            $this->redirect('');
        }
        $calculations = Calculation::getByPatient($patientId);
        $this->render('calculator', [
            'patient' => $patient,
            'calculations' => $calculations
        ]);
    }

    public function apiCalculate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        $patientId = $data['patient_id'] ?? null;
        $weight = $data['weight'] ?? null;
        $nhdPercent = $data['nhd_percent'] ?? 100;
        $rateUnit = $data['rate_unit'] ?? 'mlh';
        $userId = $_SESSION['user_id'] ?? null;

        if (!$patientId || !$weight) {
            $this->json(['error' => 'Dados inválidos'], 400);
        }

        try {
            // Require the original calculator script which isn't namespaced currently
            require_once __DIR__ . '/../HydrationCalculator.php';
            $result = \HydrationCalculator::calculateHydration($weight, $rateUnit, $nhdPercent);
            
            Calculation::create($patientId, $userId, $weight, $rateUnit, $nhdPercent, json_encode($result));
            
            $this->json($result);
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
