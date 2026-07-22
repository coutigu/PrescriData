

<div class="page-header">
    <div>
        <h1 class="page-title">Gestão de Pacientes</h1>
        <p class="page-subtitle">Acompanhe os pacientes cadastrados na clínica</p>
    </div>
    <a href="index.php?route=patient/add" class="btn btn-primary">
        <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Novo Paciente
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total de Pacientes</div>
        <div class="stat-value"><?= count($patients) ?></div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Lista de Pacientes</h2>
    </div>
    <div class="panel-body" style="padding: 0;">
        <?php if (empty($patients)): ?>
            <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                <div style="display: flex; justify-content: center; margin-bottom: 1rem; opacity: 0.5;">
                    <i data-lucide="folder-open" style="width: 48px; height: 48px;"></i>
                </div>
                Nenhum paciente cadastrado ainda.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Prontuário</th>
                            <th>Paciente</th>
                            <th>Idade</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $p): ?>
                            <tr>
                                <td data-label="Prontuário"><span class="badge badge-primary"><?= htmlspecialchars($p['record_number'] ?: '#'.$p['id']) ?></span></td>
                                <td data-label="Paciente">
                                    <div class="patient-cell">
                                        <div class="avatar">
                                            <?= strtoupper(substr($p['name'], 0, 1)) ?>
                                        </div>
                                        <div style="font-weight: 500;">
                                            <?= htmlspecialchars($p['name']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Idade"><span class="text-muted"><?= htmlspecialchars($p['age']) ?></span></td>
                                <td data-label="Ações" class="text-right">
                                    <div class="flex justify-end gap-2" style="justify-content: flex-end;">
                                        <a href="index.php?route=patient/<?= $p['id'] ?>" class="btn btn-secondary">
                                            <i data-lucide="activity" style="width: 16px; height: 16px;"></i> <span>Abrir Calculadora</span>
                                        </a>
                                        <a href="index.php?route=patient/edit&id=<?= $p['id'] ?>" class="btn btn-secondary" title="Editar Paciente" style="padding: 8px;">
                                            <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <a href="index.php?route=patient/delete&id=<?= $p['id'] ?>" class="btn" title="Excluir Paciente" style="padding: 8px; background: #fee2e2; color: #b91c1c;" onclick="return confirm('ATENÇÃO: Em cumprimento à LGPD (Direito ao Esquecimento), esta ação excluirá permanentemente o paciente e TODO o seu histórico de cálculos. Esta ação é irreversível. Deseja continuar?');">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


