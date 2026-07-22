

<div class="page-header">
    <div>
        <h1 class="page-title"><?= isset($patient) ? 'Editar Paciente' : 'Novo Paciente' ?></h1>
        <p class="page-subtitle"><?= isset($patient) ? 'Atualize as informações do paciente' : 'Cadastre um novo paciente para iniciar o acompanhamento' ?></p>
    </div>
    <a href="index.php" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Voltar
    </a>
</div>

<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title">Ficha do Paciente</h2>
    </div>
    <div class="panel-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?route=<?= isset($patient) ? 'patient/edit&id=' . $patient['id'] : 'patient/add' ?>">
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="Ex.: João Silva" value="<?= isset($patient) ? htmlspecialchars($patient['name']) : '' ?>">
            </div>

            <div class="grid grid-cols-2" style="margin-bottom: 1.25rem;">
                <div>
                    <label for="record_number">Prontuário</label>
                    <input type="text" id="record_number" name="record_number" class="form-control" placeholder="Opcional" value="<?= isset($patient) ? htmlspecialchars($patient['record_number']) : '' ?>">
                </div>
                <div>
                    <label for="dob">Data de Nascimento</label>
                    <input type="date" id="dob" name="dob" class="form-control" value="<?= isset($patient) ? htmlspecialchars($patient['dob']) : '' ?>">
                </div>
            </div>

            <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
                <div>
                    <label for="age">Idade Escrita</label>
                    <input type="text" id="age" name="age" class="form-control" required placeholder="Ex.: 3 anos e 2 meses" value="<?= isset($patient) ? htmlspecialchars($patient['age']) : '' ?>">
                </div>
                <div>
                    <label for="sex">Sexo biológico</label>
                    <select id="sex" name="sex" class="form-control">
                        <option value="">Não informado</option>
                        <option value="M" <?= (isset($patient) && $patient['sex'] === 'M') ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= (isset($patient) && $patient['sex'] === 'F') ? 'selected' : '' ?>>Feminino</option>
                    </select>
                </div>
            </div>

            <?php if (!isset($patient)): ?>
            <div class="form-group" style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                <label style="display: flex; gap: 12px; align-items: flex-start; cursor: pointer; margin: 0;">
                    <input type="checkbox" name="lgpd_consent" required style="margin-top: 3px; width: 18px; height: 18px; accent-color: var(--primary);">
                    <span style="font-size: 0.9rem; color: #475569; line-height: 1.5; font-weight: 400;">
                        <strong>Termo de Consentimento (LGPD):</strong> Confirmo que o paciente (ou responsável legal) autorizou o registro e processamento destes dados clínicos sensíveis no sistema para fins exclusivos de acompanhamento médico e terapêutico, em conformidade com a Lei nº 13.709/2018 (Lei Geral de Proteção de Dados).
                    </span>
                </label>
            </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i data-lucide="save" style="width: 18px; height: 18px;"></i> Salvar Prontuário
            </button>
        </form>
    </div>
</div>
