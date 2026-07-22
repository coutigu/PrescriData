<div class="page-header flex justify-between items-center">
    <div>
        <h1 class="page-title">Gestão de Usuários</h1>
        <p class="page-subtitle">Administre os acessos ao sistema</p>
    </div>
    <a href="index.php?route=user/add" class="btn btn-primary">
        <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Adicionar Usuário
    </a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Perfil</th>
                    <th style="width: 150px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td style="font-weight: 500;"><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge badge-primary">Administrador</span>
                            <?php else: ?>
                                <span class="badge" style="background: #e2e8f0; color: #475569;">Usuário Padrão</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="index.php?route=user/edit&id=<?= $user['id'] ?>" class="btn btn-secondary" style="padding: 4px 8px;">
                                    <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?route=user/delete&id=<?= $user['id'] ?>" class="btn" style="padding: 4px 8px; background: #fee2e2; color: #b91c1c;" onclick="return confirm('Tem certeza que deseja remover este usuário?');">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
