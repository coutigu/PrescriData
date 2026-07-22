(() => {
    'use strict';

    const DOM = {
        form: document.getElementById('formHViso'),
        patientId: document.getElementById('patientId'),
        weight: document.getElementById('peso'),
        nhdPercent: document.getElementById('nhdPercent'),
        rateUnit: document.getElementById('unidadeTaxa'),
        alertBox: document.getElementById('alerta'),
        
        stages: document.getElementById('etapas'),
        rate: document.getElementById('taxa'),
        rateLabel: document.getElementById('taxaLabel'),
        rateInfo: document.getElementById('taxaInfo'),
        sg5Stage: document.getElementById('sg5Etapa'),
        nacl20Stage: document.getElementById('nacl20Etapa'),
        kcl10Stage: document.getElementById('kcl10Etapa'),
        
        standardStages: document.getElementById('etapasPadrao'),
        standardRate: document.getElementById('taxaPadrao'),
        standardRateLabel: document.getElementById('taxaPadraoLabel'),
        standardRateInfo: document.getElementById('taxaPadraoInfo'),
        sg5Standard: document.getElementById('sg5Padrao'),
        nacl20Standard: document.getElementById('nacl20Padrao'),
        kcl10Standard: document.getElementById('kcl10Padrao'),
        
        classicRate: document.getElementById('classicRate'),
        classicRateLabel: document.getElementById('classicRateLabel'),
        classicRateInfo: document.getElementById('classicRateInfo'),
        classicStages: document.getElementById('classicStages'),
        classicSg5Stage: document.getElementById('classicSg5Stage'),
        classicNacl20Stage: document.getElementById('classicNacl20Stage'),
        classicKcl10Stage: document.getElementById('classicKcl10Stage'),
        
        realPreparationCard: document.getElementById('realPreparationCard'),
        realPreparationToggle: document.getElementById('realPreparationToggle')
    };

    function parseLocaleNumber(value) {
        if (typeof value !== 'string') return Number(value);
        let normalized = value.trim().replace(/\s+/g, '');
        if (normalized === '') return NaN;
        const hasComma = normalized.includes(',');
        const hasDot = normalized.includes('.');
        if (hasComma && hasDot) {
            if (normalized.lastIndexOf(',') > normalized.lastIndexOf('.')) {
                normalized = normalized.replace(/\./g, '').replace(',', '.');
            } else {
                normalized = normalized.replace(/,/g, '');
            }
        } else if (hasComma) {
            normalized = normalized.replace(',', '.');
        }
        return Number(normalized);
    }

    function formatNumber(value, decimals) {
        if (!Number.isFinite(value)) return '';
        return value.toLocaleString('pt-BR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function sanitizeWeightInput(rawValue) {
        const rawText = String(rawValue ?? '').replace(/\s+/g, '');
        let sanitized = '';
        let separatorUsed = false;
        for (const char of rawText) {
            if (/\d/.test(char)) {
                sanitized += char;
                continue;
            }
            if ((char === ',' || char === '.') && !separatorUsed) {
                sanitized += char;
                separatorUsed = true;
            }
        }
        return sanitized.slice(0, 5);
    }

    function clearResults() {
        ['stages', 'rate', 'sg5Stage', 'nacl20Stage', 'kcl10Stage',
         'standardStages', 'standardRate', 'sg5Standard', 'nacl20Standard', 'kcl10Standard',
         'classicRate', 'classicStages', 'classicSg5Stage', 'classicNacl20Stage', 'classicKcl10Stage'].forEach(id => {
            if (DOM[id]) DOM[id].value = '';
        });
    }

    function showError(message) {
        DOM.alertBox.textContent = message;
        DOM.alertBox.classList.add('show');
    }

    function clearError() {
        DOM.alertBox.textContent = '';
        DOM.alertBox.classList.remove('show');
    }

    function renderCalculation(result) {
        const stagePrep = result.stagePreparation;
        const rate = result.rate;
        const classic = result.classic;
        const classicRate = result.classicRate;
        const std = result.standardSolution;

        DOM.stages.value = stagePrep.stages.toLocaleString('pt-BR');
        DOM.rate.value = rate.valueText;
        DOM.rateLabel.textContent = rate.label;
        DOM.rateInfo.textContent = rate.info;
        DOM.sg5Stage.value = formatNumber(stagePrep.sg5Ml, 1);
        DOM.nacl20Stage.value = formatNumber(stagePrep.nacl20Ml, 1);
        DOM.kcl10Stage.value = formatNumber(stagePrep.kcl10Ml, 1);

        DOM.standardStages.value = stagePrep.stages.toLocaleString('pt-BR');
        DOM.standardRate.value = rate.valueText;
        DOM.standardRateLabel.textContent = rate.label;
        DOM.sg5Standard.value = formatNumber(std.sg5Ml, 0);
        DOM.nacl20Standard.value = formatNumber(std.nacl20Ml, 0);
        DOM.kcl10Standard.value = formatNumber(std.kcl10Ml, 0);

        DOM.classicRate.value = classicRate.valueText;
        DOM.classicRateLabel.textContent = classicRate.label;
        DOM.classicRateInfo.textContent = classicRate.info;
        DOM.classicStages.value = classic.stages.toLocaleString('pt-BR');
        DOM.classicSg5Stage.value = `${formatNumber(classic.sg5MlPerStage, 1)} mL`;
        DOM.classicNacl20Stage.value = `${formatNumber(classic.nacl20MlPerStage, 1)} mL`;
        DOM.classicKcl10Stage.value = `${formatNumber(classic.kcl10MlPerStage, 1)} mL`;
    }

    async function runCalculation() {
        clearError();
        clearResults();

        const sanitized = sanitizeWeightInput(DOM.weight.value);
        if (DOM.weight.value !== sanitized) {
            DOM.weight.value = sanitized;
        }

        const parsedWeight = parseLocaleNumber(DOM.weight.value);
        if (isNaN(parsedWeight)) return;

        const payload = {
            patient_id: DOM.patientId.value,
            weight: parsedWeight,
            rate_unit: DOM.rateUnit.value,
            nhd_percent: parseInt(DOM.nhdPercent.value)
        };

        try {
            const response = await fetch('index.php?route=api/calculate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Erro ao calcular.');
            }

            renderCalculation(data);
            
            if (data.rate && data.rate.warning) {
                showError(data.rate.warning);
            }

            // Scroll
            if (DOM.realPreparationCard.classList.contains('is-minimized')) {
                DOM.realPreparationCard.classList.remove('is-minimized');
                DOM.realPreparationToggle.setAttribute('aria-expanded', 'true');
            }

        } catch (error) {
            showError(error.message);
        }
    }

    function bindMinimizableCards() {
        document.querySelectorAll('[data-minimizable-card]').forEach(card => {
            const toggle = card.querySelector('[data-minimizable-toggle]');
            if (!toggle) return;
            toggle.setAttribute('aria-expanded', String(!card.classList.contains('is-minimized')));
            toggle.addEventListener('click', () => {
                const isMinimized = card.classList.toggle('is-minimized');
                toggle.setAttribute('aria-expanded', String(!isMinimized));
            });
        });
    }

    if (DOM.form) {
        DOM.form.addEventListener('submit', (e) => {
            e.preventDefault();
            runCalculation();
        });

        DOM.weight.addEventListener('input', () => clearError());
        DOM.weight.addEventListener('change', runCalculation);
        DOM.nhdPercent.addEventListener('change', runCalculation);
        DOM.rateUnit.addEventListener('change', runCalculation);

        DOM.form.addEventListener('reset', () => {
            setTimeout(clearError, 0);
        });

        bindMinimizableCards();
    }
})();
