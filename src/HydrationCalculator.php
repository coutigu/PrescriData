<?php

class HydrationCalculator {
    const MIN_WEIGHT_KG = 3;
    const MAX_WEIGHT_KG = 95;
    const MAX_DAILY_VOLUME_ML = 3000;
    const MIN_NHD_PERCENT = 10;
    const MAX_NHD_PERCENT = 160;
    const REFERENCE_STAGE_VOLUME_ML = 530;
    const NACL_20_MEQ_PER_ML = 3.4;
    const KCL_10_MEQ_PER_ML = 1.34;
    const CLASSIC_SODIUM_MAX_MEQ_PER_DAY = 125;
    const CLASSIC_POTASSIUM_MAX_MEQ_PER_DAY = 67.5;
    
    const ALLOWED_STAGE_COUNTS_PER_DAY = [1, 2, 3, 4, 6, 8, 12];
    
    const STANDARD_SOLUTION = [
        'totalMl' => 530,
        'sg5Ml' => 500,
        'nacl20Ml' => 20,
        'kcl10Ml' => 10
    ];

    const STAGE_MIX = [
        'sg5' => 500 / 530,
        'nacl20' => 20 / 530,
        'kcl10' => 10 / 530
    ];

    public static function calculateDailyMaintenanceVolume($weightKg) {
        if ($weightKg <= 10) return $weightKg * 100;
        if ($weightKg <= 20) return 1000 + (($weightKg - 10) * 50);
        return min(1500 + (($weightKg - 20) * 20), self::MAX_DAILY_VOLUME_ML);
    }

    public static function calculateRate($volumePerDayMl, $unit) {
        $mlPerHour = $volumePerDayMl / 24;

        if ($unit === 'gtt') {
            $rawDropsPerMinute = ($mlPerHour * 20) / 60;
            $dropsPerMinute = round($rawDropsPerMinute);
            $lowMacroDripWarning = $rawDropsPerMinute > 0 && $rawDropsPerMinute < 1
                ? 'Taxa muito baixa para equipo macro. Preferir bomba de infusão em mL/h ou microgotas.'
                : '';

            return [
                'unit' => $unit,
                'label' => 'Gotas/minuto',
                'valueText' => number_format($dropsPerMinute, 0, ',', '.') . ' gotas/min',
                'info' => 'conversão usando equipo macro de 20 gotas/mL',
                'numericValue' => $dropsPerMinute,
                'rawNumericValue' => $rawDropsPerMinute,
                'warning' => $lowMacroDripWarning
            ];
        }

        if ($unit === 'microgtt') {
            $microdropsPerMinute = round($mlPerHour);
            return [
                'unit' => $unit,
                'label' => 'Microgotas/minuto',
                'valueText' => number_format($microdropsPerMinute, 0, ',', '.') . ' microgotas/min',
                'info' => 'conversão usando equipo micro de 60 microgotas/mL',
                'numericValue' => $microdropsPerMinute,
                'warning' => ''
            ];
        }

        return [
            'unit' => 'mlh',
            'label' => 'mL/hora',
            'valueText' => number_format($mlPerHour, 1, ',', '.') . ' mL/h',
            'info' => 'taxa contínua das 24 horas',
            'numericValue' => $mlPerHour,
            'warning' => ''
        ];
    }

    public static function resolveStageCount($volumePerDayMl) {
        $minimumStagesNeeded = max(1, ceil($volumePerDayMl / self::REFERENCE_STAGE_VOLUME_ML));

        foreach (self::ALLOWED_STAGE_COUNTS_PER_DAY as $stageCount) {
            if ($stageCount >= $minimumStagesNeeded) {
                return $stageCount;
            }
        }
        return end(self::ALLOWED_STAGE_COUNTS_PER_DAY);
    }

    public static function buildStagePreparation($volumePerDayMl) {
        $stages = self::resolveStageCount($volumePerDayMl);
        $stageVolumeMl = $volumePerDayMl / $stages;
        
        return [
            'stages' => $stages,
            'stageVolumeMl' => $stageVolumeMl,
            'hoursPerStage' => 24 / $stages,
            'sg5Ml' => $stageVolumeMl * self::STAGE_MIX['sg5'],
            'nacl20Ml' => $stageVolumeMl * self::STAGE_MIX['nacl20'],
            'kcl10Ml' => $stageVolumeMl * self::STAGE_MIX['kcl10']
        ];
    }

    public static function calculateClassicSodiumMeq($weightKg) {
        if ($weightKg <= 10) return $weightKg * 3;
        if ($weightKg <= 20) return 30 + (($weightKg - 10) * 2);
        return 50 + (($weightKg - 20) * 1);
    }

    public static function calculateClassicPotassiumMeq($weightKg) {
        if ($weightKg <= 10) return $weightKg * 2;
        if ($weightKg <= 20) return 20 + (($weightKg - 10) * 1);
        return 30 + (($weightKg - 20) * 0.5);
    }

    public static function calculateClassicHydration($weightKg, $nhdPercent = 100) {
        $nhdFactor = $nhdPercent / 100;
        $dailyVolumeMl = self::calculateDailyMaintenanceVolume($weightKg) * $nhdFactor;
        
        $sodiumMeqPerDay = min(
            self::calculateClassicSodiumMeq($weightKg) * $nhdFactor,
            self::CLASSIC_SODIUM_MAX_MEQ_PER_DAY
        );
        $potassiumMeqPerDay = min(
            self::calculateClassicPotassiumMeq($weightKg) * $nhdFactor,
            self::CLASSIC_POTASSIUM_MAX_MEQ_PER_DAY
        );
        
        $nacl20MlPerDay = $sodiumMeqPerDay / self::NACL_20_MEQ_PER_ML;
        $kcl10MlPerDay = $potassiumMeqPerDay / self::KCL_10_MEQ_PER_ML;
        $sg5MlPerDay = max(0, $dailyVolumeMl - $nacl20MlPerDay - $kcl10MlPerDay);

        $minimumStagesNeeded = max(1, ceil($sg5MlPerDay / 500));
        $stages = 1;
        foreach (self::ALLOWED_STAGE_COUNTS_PER_DAY as $c) {
            if ($c >= $minimumStagesNeeded) {
                $stages = $c;
                break;
            }
        }
        if ($stages < $minimumStagesNeeded) {
             $stages = end(self::ALLOWED_STAGE_COUNTS_PER_DAY);
        }

        return [
            'dailyVolumeMl' => $dailyVolumeMl,
            'rateMlPerHour' => $dailyVolumeMl / 24,
            'stages' => $stages,
            'sg5MlPerStage' => $stages > 0 ? $sg5MlPerDay / $stages : 0,
            'nacl20MlPerStage' => $stages > 0 ? $nacl20MlPerDay / $stages : 0,
            'kcl10MlPerStage' => $stages > 0 ? $kcl10MlPerDay / $stages : 0,
            'sodiumMeqPerDay' => $sodiumMeqPerDay,
            'potassiumMeqPerDay' => $potassiumMeqPerDay
        ];
    }

    public static function calculateHydration($weightKg, $rateUnit, $nhdPercent = 100) {
        if (!is_numeric($weightKg) || $weightKg < self::MIN_WEIGHT_KG || $weightKg > self::MAX_WEIGHT_KG) {
            throw new Exception("Peso inválido. Deve estar entre " . self::MIN_WEIGHT_KG . " e " . self::MAX_WEIGHT_KG . " kg.");
        }
        if (!is_numeric($nhdPercent) || $nhdPercent < self::MIN_NHD_PERCENT || $nhdPercent > self::MAX_NHD_PERCENT) {
            throw new Exception("NHD inválida. Deve estar entre " . self::MIN_NHD_PERCENT . "% e " . self::MAX_NHD_PERCENT . "%.");
        }

        $baseDailyVolumeMl = self::calculateDailyMaintenanceVolume($weightKg);
        $dailyVolumeMl = $baseDailyVolumeMl * ($nhdPercent / 100);
        $stagePreparation = self::buildStagePreparation($dailyVolumeMl);
        $rate = self::calculateRate($dailyVolumeMl, $rateUnit);
        $classic = self::calculateClassicHydration($weightKg, $nhdPercent);
        $classicRate = self::calculateRate($classic['dailyVolumeMl'], $rateUnit);

        return [
            'weightKg' => $weightKg,
            'nhdPercent' => $nhdPercent,
            'baseDailyVolumeMl' => $baseDailyVolumeMl,
            'dailyVolumeMl' => $dailyVolumeMl,
            'rate' => $rate,
            'stagePreparation' => $stagePreparation,
            'standardSolution' => self::STANDARD_SOLUTION,
            'classic' => $classic,
            'classicRate' => $classicRate
        ];
    }
}
