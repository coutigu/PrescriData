<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-header">
    <div>
        <h1 class="page-title">Estatísticas Clínicas</h1>
        <p class="page-subtitle">Análise epidemiológica e perfil de atendimentos</p>
    </div>
</div>

<!-- Key Indicators -->
<div class="grid grid-cols-3" style="gap: 20px; margin-bottom: 24px;">
    <div class="panel" style="margin-bottom: 0;">
        <div class="panel-body flex items-center" style="gap: 16px;">
            <div style="background: var(--primary-light); color: var(--primary); padding: 12px; border-radius: 12px;">
                <i data-lucide="users" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total de Pacientes</p>
                <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-main); font-weight: 700;"><?= $totals['total_patients'] ?: 0 ?></h3>
            </div>
        </div>
    </div>
    
    <div class="panel" style="margin-bottom: 0;">
        <div class="panel-body flex items-center" style="gap: 16px;">
            <div style="background: #e0e7ff; color: #4338ca; padding: 12px; border-radius: 12px;">
                <i data-lucide="calculator" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Prescrições Realizadas</p>
                <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-main); font-weight: 700;"><?= $totals['total_calculations'] ?: 0 ?></h3>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-bottom: 0;">
        <div class="panel-body flex items-center" style="gap: 16px;">
            <div style="background: #dcfce7; color: #15803d; padding: 12px; border-radius: 12px;">
                <i data-lucide="activity" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Peso Médio Atendido</p>
                <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-main); font-weight: 700;"><?= $totals['avg_weight'] ? number_format($totals['avg_weight'], 1, ',', '.') . ' kg' : 'N/D' ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-2" style="gap: 20px;">
    <!-- Sex Chart -->
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Atendimentos por Sexo biológico</h2>
        </div>
        <div class="panel-body" style="position: relative; height: 300px; display: flex; justify-content: center;">
            <canvas id="sexChart"></canvas>
        </div>
    </div>

    <!-- Age Chart -->
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Perfil por Faixa Etária</h2>
        </div>
        <div class="panel-body" style="position: relative; height: 300px;">
            <canvas id="ageChart"></canvas>
        </div>
    </div>

    <!-- Weight Chart -->
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Distribuição de Pesos (Clínica)</h2>
        </div>
        <div class="panel-body" style="position: relative; height: 300px;">
            <canvas id="weightChart"></canvas>
        </div>
    </div>

    <!-- NHD Chart -->
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Preferência Terapêutica (NHD %)</h2>
        </div>
        <div class="panel-body" style="position: relative; height: 300px;">
            <canvas id="nhdChart"></canvas>
        </div>
    </div>
</div>

<?php 
// Chart.js Initialization Script
$extra_js = "
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { family: 'Inter, system-ui, sans-serif' } }
            }
        }
    };

    // Sex Data (Pie Chart)
    const sexData = " . $sexData . ";
    if (sexData.length > 0) {
        new Chart(document.getElementById('sexChart'), {
            type: 'doughnut',
            data: {
                labels: sexData.map(d => d.sex === 'M' ? 'Masculino' : (d.sex === 'F' ? 'Feminino' : 'Não Informado')),
                datasets: [{
                    data: sexData.map(d => d.count),
                    backgroundColor: ['#3b82f6', '#ec4899', '#94a3b8'],
                    borderWidth: 0
                }]
            },
            options: { ...commonOptions, cutout: '65%' }
        });
    }

    // Age Data (Bar Chart)
    const ageData = " . $ageData . ";
    if (ageData.length > 0) {
        new Chart(document.getElementById('ageChart'), {
            type: 'bar',
            data: {
                labels: ageData.map(d => d.age_group),
                datasets: [{
                    label: 'Pacientes',
                    data: ageData.map(d => d.count),
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6
                }]
            },
            options: { ...commonOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }

    // Weight Data (Bar Chart)
    const weightData = " . $weightData . ";
    if (weightData.length > 0) {
        new Chart(document.getElementById('weightChart'), {
            type: 'bar',
            data: {
                labels: weightData.map(d => d.weight_range),
                datasets: [{
                    label: 'Cálculos Realizados',
                    data: weightData.map(d => d.count),
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: { ...commonOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }

    // NHD Data (Bar/Horizontal Chart)
    const nhdData = " . $nhdData . ";
    if (nhdData.length > 0) {
        new Chart(document.getElementById('nhdChart'), {
            type: 'bar',
            data: {
                labels: nhdData.map(d => d.nhd_percent + '%'),
                datasets: [{
                    label: 'Vezes Prescrito',
                    data: nhdData.map(d => d.count),
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }]
            },
            options: { 
                ...commonOptions, 
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
});
</script>
";
?>
