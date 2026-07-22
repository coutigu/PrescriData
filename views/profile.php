<div class="page-header">
    <div>
        <h1 class="page-title">Meu Perfil</h1>
        <p class="page-subtitle">Altere suas credenciais de acesso</p>
    </div>
</div>

<div class="panel" style="max-width: 500px;">
    <div class="panel-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mb-4">
                <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert mb-4" style="background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Usuário logado: <strong style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($user['username']) ?></strong>
                <br>
                Perfil: <span class="badge <?= $user['role'] === 'admin' ? 'badge-primary' : '' ?>" style="<?= $user['role'] === 'admin' ? '' : 'background: #e2e8f0; color: #475569;' ?>"><?= $user['role'] === 'admin' ? 'Administrador' : 'Usuário Padrão' ?></span>
            </p>
        </div>

        <form method="POST" autocomplete="off">
            <div class="form-group mt-4">
                <label for="current_password">Senha Atual</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group mt-4">
                <label for="new_password">Nova Senha</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>

            <div class="mt-4 pt-4 border-t" style="border-top: 1px solid var(--border);">
                <button type="submit" class="btn btn-primary w-full justify-center">
                    <i data-lucide="key" style="width: 18px; height: 18px;"></i> Atualizar Senha
                </button>
            </div>
        </form>
    </div>
</div>
