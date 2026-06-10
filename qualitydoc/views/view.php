<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizando: <?= htmlspecialchars($document['title']) ?> - QualityDoc</title>
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
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #4f46e5;
            --primary-light: #e0e7ff;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --border-color: #e2e8f0;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Header / Navbar Styling */
        .app-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 16px 0;
        }

        .brand-logo {
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        /* View Container */
        .viewer-container {
            padding: 30px 0 50px 0;
        }

        /* Modern Back Button */
        .btn-back {
            color: var(--text-muted);
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition-smooth);
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        .btn-back:hover {
            color: var(--primary);
            border-color: var(--primary);
            background-color: var(--primary-light);
            transform: translateX(-4px);
        }

        /* Main Document Card */
        .doc-viewer-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
        }

        .doc-viewer-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background-color: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .doc-viewer-body {
            height: 700px;
            background-color: #f1f5f9;
            position: relative;
        }

        .doc-iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }


        /* Side Cards Panels */
        .sidebar-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            margin-bottom: 24px;
            transition: var(--transition-smooth);
        }

        /* Custom badge styling in details */
        .custom-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 30px;
            letter-spacing: 0.3px;
        }

        .status-aprobado, .status-publicado {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .status-obsoleto {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .status-default {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        /* Acknowledge Button Card */
        .ack-card {
            background: linear-gradient(135deg, #e0e7ff 0%, #eff6ff 100%);
            border: 1px solid #c7d2fe;
            position: relative;
            overflow: hidden;
        }

        .ack-card::before {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(79, 70, 229, 0.05);
            border-radius: 50%;
            top: -40px;
            right: -40px;
        }

        .btn-ack {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            transition: var(--transition-smooth);
        }

        .btn-ack:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            color: white;
        }

        .btn-ack:active {
            transform: translateY(0);
        }

        /* Interactive Version History */
        .history-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .history-item-link {
            text-decoration: none;
            color: inherit;
            display: block;
            margin-bottom: 12px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            background-color: var(--bg-card);
            transition: var(--transition-smooth);
        }

        .history-item-link:last-child {
            margin-bottom: 0;
        }

        .history-item-link:hover {
            border-color: var(--primary);
            background-color: var(--bg-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        }

        /* Active version highlight styling */
        .history-item-active {
            border-color: var(--primary);
            background-color: var(--primary-light) !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.08);
            pointer-events: none; /* Already viewing it */
        }

        .active-pill {
            background-color: var(--primary);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .obsolete-pill {
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #cbd5e1;
        }

        .latest-pill {
            background-color: #ecfdf5;
            color: #059669;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #a7f3d0;
        }

        .version-num-lbl {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .history-item-active .version-num-lbl {
            color: var(--primary);
        }

        /* Loading indicator for PDF iframe */
        .iframe-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            text-align: center;
            color: var(--text-muted);
            transition: var(--transition-smooth);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated-entry {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</head>

<body>

    <!-- Header / Navbar -->
    <header class="app-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="brand-logo">
                <i class="fa-solid fa-shield-halved"></i> QualityDoc
            </a>
            <?php if (isset($_SESSION['user'])): 
                $nombre = $_SESSION['user']['nombre'] ?? 'Usuario';
                $words = explode(' ', $nombre);
                $initials = '';
                if (count($words) >= 2) {
                    $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
                } else {
                    $initials = mb_substr($nombre, 0, 2);
                }
                $initials = mb_strtoupper($initials);
            ?>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-flex flex-column text-end">
                        <span class="fw-semibold text-dark small" style="line-height: 1.2;"><?= htmlspecialchars($nombre) ?></span>
                        <span class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($_SESSION['user']['rol'] ?? 'Sin Rol') ?> • <?= htmlspecialchars($_SESSION['user']['departamento'] ?? 'No Asignado') ?></span>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem; font-family: 'Outfit', sans-serif;" title="<?= htmlspecialchars($_SESSION['user']['empresa'] ?? 'Sin Empresa') ?>">
                        <?= htmlspecialchars($initials) ?>
                    </div>
                    <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm border-0 d-flex align-items-center justify-content-center p-2 rounded-circle" style="width: 38px; height: 38px; transition: var(--transition-smooth);" title="Cerrar Sesión">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light text-dark border p-2 px-3 rounded-pill">
                        <i class="fa-solid fa-file-circle-check me-1 text-primary"></i> Control Documental
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container viewer-container animated-entry">
        <!-- Back Button -->
        <a href="index.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Volver al listado
        </a>

        <div class="row g-4">
            <!-- Left Side: Document Viewer (Iframe) -->
            <div class="col-lg-8">
                <div class="doc-viewer-card">
                    <div class="doc-viewer-header">
                        <div>
                            <span class="badge bg-light text-dark border me-2" style="font-family: monospace;">
                                <?= htmlspecialchars($document['document_code']) ?>
                            </span>
                            <span class="fw-semibold text-slate-700">Versión <?= htmlspecialchars($document['version_number']) ?></span>
                        </div>
                        <?php 
                            $status = mb_strtolower($document['status_name'] ?? '');
                            $statusClass = 'status-default';
                            if (str_contains($status, 'aprobado')) {
                                $statusClass = 'status-aprobado';
                            } elseif (str_contains($status, 'publicado')) {
                                $statusClass = 'status-publicado';
                            } elseif (str_contains($status, 'obsoleto')) {
                                $statusClass = 'status-obsoleto';
                            }
                        ?>
                        <span class="custom-badge <?= $statusClass ?>">
                            <i class="fa-solid fa-circle-dot me-1" style="font-size: 0.5rem"></i>
                            <?= htmlspecialchars($document['status_name'] ?? 'Desconocido') ?>
                        </span>
                    </div>
                    
                    <div class="doc-viewer-body">
                        <!-- Spinner loader background -->
                        <div class="iframe-loader" id="iframeLoader">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <div>Cargando visor de documento...</div>
                        </div>
                        
                        <!-- Document Iframe -->
                        <iframe class="doc-iframe" id="docIframe" src="index.php?action=serve_file&id=<?= htmlspecialchars($document['id']) ?>"></iframe>
                    </div>
                </div>
            </div>

            <!-- Right Side: Metadata, Acknowledge & History -->
            <div class="col-lg-4">
                
                <!-- Acknowledge Card -->
                <div class="sidebar-card ack-card">
                    <h5 class="mb-2">Confirmación de Lectura</h5>
                    <p class="text-muted small mb-3">
                        Al presionar este botón, se registrará de forma oficial que has leído, comprendido y aceptado el contenido de esta versión específica del documento.
                    </p>

                    <form method="POST" action="index.php?action=acknowledge">
                        <input type="hidden" name="document_id" value="<?= htmlspecialchars($document['id']) ?>">
                        <button type="submit" class="btn-ack">
                            <i class="fa-solid fa-square-check"></i> He leído y comprendido
                        </button>
                    </form>
                </div>

                <!-- Document Details Card -->
                <div class="sidebar-card">
                    <h5 class="mb-3 border-bottom pb-2">Información del Documento</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block">Título:</label>
                        <span class="fw-semibold text-slate-800"><?= htmlspecialchars($document['title']) ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block">Código del Sistema:</label>
                        <code class="text-dark bg-light px-2 py-1 rounded small border" style="font-weight: 600; letter-spacing: 0.5px;">
                            <?= htmlspecialchars($document['document_code']) ?>
                        </code>
                    </div>

                    <?php if (!empty($document['company_name'])): ?>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Empresa:</label>
                            <span class="text-slate-700"><?= htmlspecialchars($document['company_name']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="text-muted small d-block">Descripción / Objeto:</label>
                        <p class="text-slate-600 small mb-0 bg-light p-2.5 rounded border-start border-primary border-3" style="background-color: #fafafa; padding: 10px 12px; margin-top: 4px;">
                            <?= htmlspecialchars($document['description'] ?? 'Sin descripción disponible.') ?>
                        </p>
                    </div>
                </div>

                <!-- Interactive History Card -->
                <div class="sidebar-card">
                    <h5 class="mb-3 border-bottom pb-2">Historial de Versiones</h5>
                    <p class="text-muted small mb-3">Haz clic en cualquier versión para abrirla en el visor.</p>
                    
                    <div class="history-list">
                        <?php if (isset($history) && count($history) > 0): ?>
                            <?php foreach ($history as $ver): 
                                // Determinar si esta iteración es la versión que estamos visualizando actualmente
                                $isCurrent = ($ver['id'] === $document['id']);
                            ?>
                                <a href="index.php?action=view&id=<?= htmlspecialchars($ver['id']) ?>" 
                                   class="history-item-link d-flex justify-content-between align-items-center <?= $isCurrent ? 'history-item-active' : '' ?>">
                                    <div>
                                        <div class="version-num-lbl">
                                            Versión <?= htmlspecialchars($ver['version_number']) ?>
                                        </div>
                                        <small class="text-muted text-truncate d-inline-block mt-0.5" style="max-width: 180px;">
                                            <?= htmlspecialchars($ver['title']) ?>
                                        </small>
                                    </div>
                                    
                                    <div>
                                        <?php if ($isCurrent): ?>
                                            <span class="active-pill">Viendo</span>
                                        <?php elseif ($ver['is_latest']): ?>
                                            <span class="latest-pill">Vigente</span>
                                        <?php else: ?>
                                            <span class="obsolete-pill">Obsoleta</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-3 small">
                                No se encontraron otras versiones para este documento.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 border-top bg-white text-center">
        <div class="container text-muted small">
            &copy; 2026 QualityDoc. Todos los derechos reservados. | Registro automatizado de visualización y cumplimiento.
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para remover spinner de carga (con fail-safe para PDFs) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('docIframe');
            const loader = document.getElementById('iframeLoader');
            
            if (iframe && loader) {
                // Evento de carga estándar del iframe
                iframe.addEventListener('load', function() {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 300);
                });
                
                // Fail-safe: Desaparecer el spinner a los 1.2 segundos si el visor de PDF bloquea el evento 'load'
                setTimeout(function() {
                    if (loader.style.display !== 'none') {
                        loader.style.opacity = '0';
                        setTimeout(() => loader.style.display = 'none', 300);
                    }
                }, 1200);
            }
        });
    </script>
</body>

</html>