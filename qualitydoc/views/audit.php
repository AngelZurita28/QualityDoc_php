<?php
// Asegurarnos de que el usuario tiene acceso
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "Acceso denegado. Se requiere el rol de Admin.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QualityDoc - Bitácora de Auditoría</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for beautiful icons -->
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

        /* Dashboard Container */
        .dashboard-container {
            padding: 40px 0 60px 0;
        }

        /* Modern Tabs */
        .nav-tabs-custom {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 20px;
            border-radius: var(--radius-md) var(--radius-md) 0 0;
            position: relative;
            transition: var(--transition-smooth);
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary);
            background: transparent;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px;
        }

        /* Table Card Styling */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
            transition: var(--transition-smooth);
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table th {
            background-color: #fafafa;
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            transition: var(--transition-smooth);
        }

        .custom-table tbody tr {
            transition: var(--transition-smooth);
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            font-family: 'Outfit', sans-serif;
        }

        .document-code {
            font-family: monospace;
            background-color: #f1f5f9;
            color: #334155;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .version-tag {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            display: inline-block;
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

        /* Empty state styling */
        .empty-state {
            padding: 60px 24px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        /* Search input styling in card */
        .filter-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-input {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 10px 16px 10px 44px;
            font-size: 0.9rem;
            background-color: var(--bg-primary);
            transition: var(--transition-smooth);
            width: 100%;
        }

        .search-input:focus {
            background-color: var(--bg-card);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
            outline: none;
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
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container dashboard-container animated-entry">
        <!-- Back Button -->
        <a href="index.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Volver al listado
        </a>

        <div class="row mb-4">
            <div class="col-lg-12">
                <h2 class="mb-1 text-slate-800">Bitácora de Auditoría</h2>
                <p class="text-muted">Visualiza los accesos de visualización y registros de comprensión de documentos en tiempo real.</p>
            </div>
        </div>

        <!-- Custom Tabs Navigation -->
        <div class="nav nav-tabs-custom" id="auditTab" role="tablist">
            <button class="nav-link active" id="views-tab" data-bs-toggle="tab" data-bs-target="#viewsPanel" type="button" role="tab" aria-controls="viewsPanel" aria-selected="true">
                <i class="fa-regular fa-eye me-2"></i> Visualizaciones de Documentos
            </button>
            <button class="nav-link" id="acks-tab" data-bs-toggle="tab" data-bs-target="#acksPanel" type="button" role="tab" aria-controls="acksPanel" aria-selected="false">
                <i class="fa-regular fa-square-check me-2"></i> Confirmaciones de Lectura
            </button>
        </div>

        <div class="tab-content" id="auditTabContent">
            <!-- Panel 1: Views Audit Logs -->
            <div class="tab-pane fade show active" id="viewsPanel" role="tabpanel" aria-labelledby="views-tab">
                
                <!-- Search filter for Views -->
                <div class="filter-card">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="viewsSearch" class="search-input" placeholder="Filtrar accesos por usuario, departamento, código o título del documento...">
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="table custom-table" id="viewsTable">
                            <thead>
                                <tr>
                                    <th style="width: 25%">Usuario / Datos</th>
                                    <th style="width: 15%">Código</th>
                                    <th style="width: 35%">Documento Visualizado</th>
                                    <th style="width: 10%">Versión</th>
                                    <th style="width: 15%">Fecha de Acceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($viewLogs) && count($viewLogs) > 0): ?>
                                    <?php foreach ($viewLogs as $log): 
                                        $userInitials = mb_strtoupper(mb_substr($log['user_name'] ?? 'U', 0, 2));
                                    ?>
                                        <tr class="view-row">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar-small"><?= htmlspecialchars($userInitials) ?></div>
                                                    <div>
                                                        <div class="fw-semibold text-slate-800 view-search-user"><?= htmlspecialchars($log['user_name'] ?? 'Usuario ' . $log['user_id']) ?></div>
                                                        <small class="text-muted view-search-dept"><?= htmlspecialchars($log['area'] ?? 'Sistemas') ?> • ID: <?= $log['user_id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="document-code view-search-code"><?= htmlspecialchars($log['document_code']) ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-slate-800 view-search-title"><?= htmlspecialchars($log['title']) ?></div>
                                            </td>
                                            <td>
                                                <span class="version-tag">V-<?= htmlspecialchars($log['version_number']) ?></span>
                                            </td>
                                            <td>
                                                <span class="text-slate-600 small"><?= date('d/m/Y H:i:s', strtotime($log['viewed_at'])) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="fa-regular fa-eye-slash empty-state-icon"></i>
                                                <h5>Sin registros de acceso</h5>
                                                <p class="text-muted">Aún no se han registrado accesos o visualizaciones de documentos.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr id="noViewsResults" style="display: none;">
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-magnifying-glass empty-state-icon"></i>
                                            <h5>Sin coincidencias</h5>
                                            <p class="text-muted">No encontramos registros de acceso que coincidan con tu búsqueda.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Panel 2: Acknowledgment Logs -->
            <div class="tab-pane fade" id="acksPanel" role="tabpanel" aria-labelledby="acks-tab">
                
                <!-- Search filter for Acks -->
                <div class="filter-card">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="acksSearch" class="search-input" placeholder="Filtrar acuses por usuario, departamento, código o título del documento...">
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="table custom-table" id="acksTable">
                            <thead>
                                <tr>
                                    <th style="width: 25%">Usuario / Datos</th>
                                    <th style="width: 15%">Código</th>
                                    <th style="width: 35%">Documento Comprendido</th>
                                    <th style="width: 10%">Versión</th>
                                    <th style="width: 15%">Fecha de Confirmación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($ackLogs) && count($ackLogs) > 0): ?>
                                    <?php foreach ($ackLogs as $log): 
                                        $userInitials = mb_strtoupper(mb_substr($log['user_name'] ?? 'U', 0, 2));
                                    ?>
                                        <tr class="ack-row">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar-small" style="background-color: #d1fae5; color: #059669;"><?= htmlspecialchars($userInitials) ?></div>
                                                    <div>
                                                        <div class="fw-semibold text-slate-800 ack-search-user"><?= htmlspecialchars($log['user_name'] ?? 'Usuario ' . $log['user_id']) ?></div>
                                                        <small class="text-muted ack-search-dept"><?= htmlspecialchars($log['area'] ?? 'Sistemas') ?> • ID: <?= $log['user_id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="document-code ack-search-code"><?= htmlspecialchars($log['document_code']) ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-slate-800 ack-search-title"><?= htmlspecialchars($log['title']) ?></div>
                                            </td>
                                            <td>
                                                <span class="version-tag">V-<?= htmlspecialchars($log['version_number']) ?></span>
                                            </td>
                                            <td>
                                                <span class="text-slate-600 small"><?= date('d/m/Y H:i:s', strtotime($log['acknowledged_at'])) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="fa-regular fa-square-check empty-state-icon"></i>
                                                <h5>Sin confirmaciones de lectura</h5>
                                                <p class="text-muted">Aún ningún usuario ha marcado documentos como "leído y comprendido".</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr id="noAcksResults" style="display: none;">
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-magnifying-glass empty-state-icon"></i>
                                            <h5>Sin coincidencias</h5>
                                            <p class="text-muted">No encontramos confirmaciones de lectura que coincidan con tu búsqueda.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 border-top bg-white text-center">
        <div class="container text-muted small">
            &copy; 2026 QualityDoc. Todos los derechos reservados. | Bitácora de trazabilidad confidencial.
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client side filters for both lists -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter Views
            const viewsSearch = document.getElementById('viewsSearch');
            if (viewsSearch) {
                viewsSearch.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.view-row');
                    const noResults = document.getElementById('noViewsResults');
                    let count = 0;

                    rows.forEach(row => {
                        const user = row.querySelector('.view-search-user').textContent.toLowerCase();
                        const dept = row.querySelector('.view-search-dept').textContent.toLowerCase();
                        const code = row.querySelector('.view-search-code').textContent.toLowerCase();
                        const title = row.querySelector('.view-search-title').textContent.toLowerCase();

                        if (user.includes(filter) || dept.includes(filter) || code.includes(filter) || title.includes(filter)) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (count === 0 && rows.length > 0) {
                        noResults.style.display = '';
                    } else {
                        noResults.style.display = 'none';
                    }
                });
            }

            // Filter Acks
            const acksSearch = document.getElementById('acksSearch');
            if (acksSearch) {
                acksSearch.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.ack-row');
                    const noResults = document.getElementById('noAcksResults');
                    let count = 0;

                    rows.forEach(row => {
                        const user = row.querySelector('.ack-search-user').textContent.toLowerCase();
                        const dept = row.querySelector('.ack-search-dept').textContent.toLowerCase();
                        const code = row.querySelector('.ack-search-code').textContent.toLowerCase();
                        const title = row.querySelector('.ack-search-title').textContent.toLowerCase();

                        if (user.includes(filter) || dept.includes(filter) || code.includes(filter) || title.includes(filter)) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (count === 0 && rows.length > 0) {
                        noResults.style.display = '';
                    } else {
                        noResults.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>

</html>
