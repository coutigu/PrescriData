<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1a3a6b">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>HViso — Hidratação Venosa Isotônica</title>
    <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <i data-lucide="droplet" style="color: var(--primary); width: 24px; height: 24px;"></i>
                <h2 class="logo-text">HViso Pro</h2>
                <button class="close-sidebar" onclick="document.getElementById('sidebar').classList.remove('active')">✕</button>
            </div>
            <div class="sidebar-nav">
                <a href="index.php" class="nav-item <?= empty($_GET['route']) || $_GET['route'] == 'patients' ? 'active' : '' ?>">
                    <i data-lucide="users" style="width: 18px; height: 18px;"></i> Pacientes
                </a>
                <a href="index.php?route=patient/add" class="nav-item <?= (isset($_GET['route']) && $_GET['route'] == 'patient/add') ? 'active' : '' ?>">
                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i> Novo Paciente
                </a>
                <a href="index.php?route=stats" class="nav-item <?= (isset($_GET['route']) && $_GET['route'] == 'stats') ? 'active' : '' ?>" style="margin-top: 0.5rem;">
                    <i data-lucide="pie-chart" style="width: 18px; height: 18px;"></i> Estatísticas
                </a>
            </div>
            <div class="sidebar-footer" style="padding-top: 1rem; margin-top: auto; border-top: 1px solid var(--border);">
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="index.php?route=audit" class="nav-item <?= (isset($_GET['route']) && $_GET['route'] == 'audit') ? 'active' : '' ?>">
                        <i data-lucide="clipboard-list" style="width: 18px; height: 18px;"></i> Auditoria Global
                    </a>
                    <a href="index.php?route=users" class="nav-item <?= (isset($_GET['route']) && strpos($_GET['route'], 'user') === 0 && $_GET['route'] !== 'user/profile') ? 'active' : '' ?>">
                        <i data-lucide="shield" style="width: 18px; height: 18px;"></i> Gestão de Usuários
                    </a>
                <?php endif; ?>
                <a href="index.php?route=profile" class="nav-item <?= (isset($_GET['route']) && $_GET['route'] == 'profile') ? 'active' : '' ?>">
                    <i data-lucide="user" style="width: 18px; height: 18px;"></i> Meu Perfil
                </a>
                <a href="index.php?route=logout" class="nav-item danger" style="margin-top: 0.5rem;">
                    <i data-lucide="log-out" style="width: 18px; height: 18px;"></i> Sair
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar Mobile -->
            <header class="topbar">
                <button class="menu-btn" onclick="document.getElementById('sidebar').classList.add('active')">☰</button>
                <h2 class="topbar-title">HViso Pro</h2>
            </header>

            <main class="page-content">
                <?php echo $content; ?>
            </main>
        </div>
    </div>
    <?php else: ?>
        <!-- Login Page layout -->
        <?php echo $content; ?>
    <?php endif; ?>
    

    <div class="spacer-3lines" aria-hidden="true"></div>

    <?php if (isset($extra_js)) echo $extra_js; ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
