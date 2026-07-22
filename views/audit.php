<div class="page-header">
    <div>
        <h1 class="page-title">Auditoria Global de Resultados</h1>
        <p class="page-subtitle">Histórico consolidado de todos os cálculos e prescrições realizadas no sistema</p>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Registro Clínico (LGPD)</h2>
    </div>
    <div class="panel-body" style="padding: 0;">
        <?php if (empty($audits)): ?>
            <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                <div style="display: flex; justify-content: center; margin-bottom: 1rem; opacity: 0.5;">
                    <i data-lucide="clipboard-list" style="width: 48px; height: 48px;"></i>
                </div>
                Nenhum cálculo registrado no sistema ainda.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Data / Hora</th>
                            <th>Paciente (Prontuário)</th>
                            <th>Peso / NHD</th>
                            <th>Prescritor (Médico)</th>
                            <th>Detalhes Técnicos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($audits as $audit): ?>
                            <?php 
                                $date = date('d/m/Y H:i:s', strtotime($audit['created_at']));
                                $patientName = $audit['patient_name'] ?? 'Paciente Removido';
                                $recordNum = $audit['record_number'] ? '(#'.$audit['record_number'].')' : '';
                                $doctorName = $audit['doctor_name'] ?? 'Desconhecido';
                                
                                // Parse JSON to get some key info if needed
                                $res = json_decode($audit['results_json'], true);
                                $totalVol = isset($res['results']['total_volume']) ? number_format($res['results']['total_volume'], 1, ',', '.') . ' mL' : 'N/D';
                            ?>
                            <tr>
                                <td data-label="Data / Hora"><span style="font-weight: 500;"><?= $date ?></span></td>
                                <td data-label="Paciente">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="user" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                                        <span><?= htmlspecialchars($patientName) ?> <span class="text-muted" style="font-size: 0.8rem;"><?= htmlspecialchars($recordNum) ?></span></span>
                                    </div>
                                </td>
                                <td data-label="Peso / NHD">
                                    <span class="badge badge-primary"><?= number_format($audit['weight'], 1, ',', '.') ?> kg</span>
                                    <span class="badge badge-warning"><?= $audit['nhd_percent'] ?>% NHD</span>
                                </td>
                                <td data-label="Prescritor">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="stethoscope" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                                        <?= htmlspecialchars($doctorName) ?>
                                    </div>
                                </td>
                                <td data-label="Volume Total">
                                    <span style="color: var(--primary); font-weight: 600;">Vol: <?= $totalVol ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
