<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en QualityDoc para gestionar y visualizar los documentos del sistema de control de calidad.">
    <title>Iniciar Sesión - QualityDoc</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --bg-gradient: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e1b4b 100%);
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 24px;
        }

        /* Ambient background glow elements */
        .glow-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: 1;
            pointer-events: none;
            opacity: 0.15;
        }

        .glow-sphere-1 {
            width: 400px;
            height: 400px;
            background: #4f46e5;
            top: -100px;
            left: -100px;
        }

        .glow-sphere-2 {
            width: 500px;
            height: 500px;
            background: #3b82f6;
            bottom: -150px;
            right: -150px;
        }

        /* Glassmorphism Card Container */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            padding: 48px 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 10;
            position: relative;
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-logo {
            font-size: 2.2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 36px;
        }

        /* Premium Form Controls */
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-weight: 500;
            font-size: 0.85rem;
            color: #e2e8f0;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group-custom i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: var(--transition-smooth);
            z-index: 5;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            padding: 14px 16px 14px 48px;
            font-size: 0.95rem;
            color: #ffffff;
            width: 100%;
            transition: var(--transition-smooth);
        }

        .form-control-custom::placeholder {
            color: #475569;
        }

        .form-control-custom:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        .form-control-custom:focus + i {
            color: var(--primary);
        }

        /* Custom Button */
        .btn-submit {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            color: white;
            border-radius: var(--radius-md);
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.45);
        }

        .btn-submit:hover::after {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Glass Alerts */
        .alert-glass {
            background: rgba(239, 68, 68, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: alertShake 0.4s ease;
        }

        @keyframes alertShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        .footer-text {
            text-align: center;
            margin-top: 36px;
            color: var(--text-muted);
            font-size: 0.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Spheres for background decoration -->
    <div class="glow-sphere glow-sphere-1"></div>
    <div class="glow-sphere glow-sphere-2"></div>

    <main class="login-card">
        <!-- Brand identity -->
        <div class="text-center">
            <div class="brand-logo">
                <i class="fa-solid fa-shield-halved"></i>
                <span>QualityDoc</span>
            </div>
            <h1>Acceso al Portal</h1>
            <p class="subtitle">Ingresa tus credenciales para visualizar el control documental</p>
        </div>

        <!-- Error messages -->
        <?php if (!empty($error)): ?>
            <div class="alert-glass" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="index.php?action=login" id="loginForm">
            <div>
                <label for="inputEmail" class="form-label">Correo Electrónico</label>
                <div class="input-group-custom">
                    <input type="email" id="inputEmail" name="Email" class="form-control-custom" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($email) ?>" required autocomplete="email" autofocus>
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>

            <div>
                <label for="inputPassword" class="form-label">Contraseña</label>
                <div class="input-group-custom">
                    <input type="password" id="inputPassword" name="Password" class="form-control-custom" placeholder="••••••••" required autocomplete="current-password">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <span>Iniciar Sesión</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="footer-text">
            &copy; 2026 QualityDoc. Todos los derechos reservados.
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth form submission UI feedback
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span>Verificando...</span>';
            }
        });
    </script>
</body>

</html>
