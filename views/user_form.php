<div class="page-header">
    <div>
        <h1 class="page-title"><?= isset($user) ? 'Editar Usuário' : 'Novo Usuário' ?></h1>
        <p class="page-subtitle"><?= isset($user) ? 'Atualizar permissões ou senha' : 'Cadastre um novo acesso ao sistema' ?></p>
    </div>
    <a href="index.php?route=users" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Voltar
    </a>
</div>

<div class="panel" style="max-width: 600px;">
    <div class="panel-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mb-4">
                <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?= isset($user) ? htmlspecialchars($user['username']) : '' ?>" 
                       <?= isset($user) ? 'readonly' : 'required' ?>>
                <?php if (isset($user)): ?>
                    <small class="text-muted">O nome de usuário não pode ser alterado após a criação.</small>
                <?php endif; ?>
            </div>

            <div class="form-group mt-4">
                <label for="role">Perfil de Acesso</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="user" <?= (isset($user) && $user['role'] === 'user') ? 'selected' : '' ?>>Usuário Padrão</option>
                    <option value="admin" <?= (isset($user) && $user['role'] === 'admin') ? 'selected' : '' ?>>Administrador</option>
                </select>
                <small class="text-muted">Administradores têm acesso total, incluindo a gestão de outros usuários.</small>
            </div>

            <div class="form-group mt-4">
                <label for="password"><?= isset($user) ? 'Nova Senha (deixe em branco para não alterar)' : 'Senha' ?></label>
                <input type="password" id="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
            </div>

            <div class="mt-4 pt-4 border-t" style="border-top: 1px solid var(--border);">
                <button type="submit" class="btn btn-primary w-full justify-center">
                    <i data-lucide="save" style="width: 18px; height: 18px;"></i> Salvar Usuário
                </button>
            </div>
        </form>
    </div>
</div>
