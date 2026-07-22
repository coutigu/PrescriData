<style>
    /* Full-screen reset for the login page */
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Inter', system-ui, sans-serif;
        background-color: #ffffff;
    }

    /* Hide the default app-container or spacers if they leak */
    .app-container { display: none; }
    .spacer-3lines { display: none; }

    .login-container {
        display: flex;
        min-height: 100vh;
        width: 100vw;
        background-color: #ffffff;
        position: absolute;
        top: 0;
        left: 0;
    }

    /* Left Side - Colored Branding */
    .login-left {
        flex: 1;
        background: var(--primary, #0369a1);
        position: relative;
        display: none;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        color: white;
        overflow: hidden;
    }

    @media (min-width: 900px) {
        .login-left {
            display: flex;
            max-width: 45%;
        }
    }

    .login-left-content {
        position: relative;
        z-index: 10;
        max-width: 400px;
    }

    .login-left-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .login-left-subtitle {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .login-left-text {
        font-size: 0.95rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.75);
    }

    /* The S-Curve wave effect using SVG */
    .login-wave {
        position: absolute;
        right: -1px; /* Prevent 1px gap */
        top: 0;
        height: 100%;
        width: 250px;
        z-index: 1;
    }

    /* Right Side - Login Form */
    .login-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        background-color: #ffffff;
    }

    .login-form-wrapper {
        width: 100%;
        max-width: 420px;
        padding: 2rem;
    }

    .login-title {
        font-size: 2.2rem;
        color: var(--primary, #0369a1);
        margin-bottom: 2.5rem;
        font-weight: 300;
        text-align: center;
    }

    .auth-input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .auth-input-group i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .auth-input {
        width: 100%;
        height: 52px;
        padding-left: 48px;
        padding-right: 16px;
        border: none;
        border-radius: 8px;
        background-color: #f8fafc;
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }

    .auth-input:focus {
        outline: none;
        background-color: #ffffff;
        box-shadow: 0 0 0 2px var(--primary-light, #bae6fd), inset 0 1px 2px rgba(0,0,0,0.02);
    }
    
    .auth-input::placeholder {
        color: #94a3b8;
    }

    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 2.5rem;
    }

    .auth-options label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .auth-options input[type="checkbox"] {
        accent-color: var(--primary, #0369a1);
        width: 16px;
        height: 16px;
    }

    .forgot-link {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: var(--primary, #0369a1);
    }

    .btn-login {
        width: 100%;
        height: 52px;
        background-color: var(--primary, #0369a1);
        color: white;
        border: none;
        border-radius: 26px; /* Pill shape */
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
        box-shadow: 0 4px 14px rgba(3, 105, 161, 0.25);
    }

    .btn-login:hover {
        background-color: #0284c7;
        box-shadow: 0 6px 20px rgba(3, 105, 161, 0.35);
    }

    .btn-login:active {
        transform: scale(0.98);
    }
</style>

<div class="login-container">
    
    <!-- Left Panel (Brand) -->
    <div class="login-left">
        <div class="login-left-content">
            <h1 class="login-left-title">HViso Pro</h1>
            <div class="login-left-subtitle">Gestão de Hidratação Venosa Pediátrica</div>
            <p class="login-left-text">
                Otimize o cálculo terapêutico com precisão clínica. O HViso Pro garante segurança na prescrição de soluções isotônicas, mantendo o histórico epidemiológico dos seus atendimentos perfeitamente organizado.
            </p>
        </div>
        
        <!-- S-Curve Wave Divider -->
        <svg class="login-wave" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M100,0 C100,50 0,50 0,100 L100,100 L100,0 Z" />
        </svg>
    </div>

    <!-- Right Panel (Form) -->
    <div class="login-right">
        <div class="login-form-wrapper">
            
            <h2 class="login-title">Entrar no HViso Pro</h2>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 1.5rem; border-radius: 8px;">
                    <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?route=login">
                
                <div class="auth-input-group">
                    <i data-lucide="mail"></i>
                    <input type="text" id="username" name="username" class="auth-input" required autocomplete="username" placeholder="Usuário">
                </div>

                <div class="auth-input-group">
                    <i data-lucide="lock"></i>
                    <input type="password" id="password" name="password" class="auth-input" required autocomplete="current-password" placeholder="Senha">
                </div>

                <div class="auth-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Lembrar de mim
                    </label>
                    <a href="#" class="forgot-link">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn-login">
                    ENTRAR
                </button>
                
            </form>
        </div>
        
        
    </div>
</div>
