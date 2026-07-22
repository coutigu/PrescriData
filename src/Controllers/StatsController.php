<?php
namespace Controllers;
use Core\Controller;
use Models\Stats;

class StatsController extends Controller {
    public function index() {
        $totals = Stats::getGeneralTotals();
        $sexData = Stats::getPatientsBySex();
        $weightData = Stats::getCalculationsByWeightRange();
        $nhdData = Stats::getCalculationsByNhd();
        $ageData = Stats::getPatientsByAgeGroup();

        $this->render('stats', [
            'totals' => $totals,
            'sexData' => json_encode($sexData),
            'weightData' => json_encode($weightData),
            'nhdData' => json_encode($nhdData),
            'ageData' => json_encode($ageData)
        ]);
    }
}
