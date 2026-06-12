<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QualityDoc - Repositorio de Documentos</title>
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

        /* Search & Filter bar card */
        .search-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            margin-bottom: 30px;
            transition: var(--transition-smooth);
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .search-control {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 12px 16px 12px 48px;
            font-size: 0.95rem;
            color: var(--text-main);
            background-color: var(--bg-primary);
            transition: var(--transition-smooth);
        }

        .search-control:focus {
            background-color: var(--bg-card);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
            outline: none;
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
            padding: 20px 24px;
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
            transform: scale(1.002);
        }

        /* Custom Badges */
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
        }

        .status-aprobado, .status-publicado {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .status-pendiente {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
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

        .version-tag {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            display: inline-block;
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

        /* Actions styling */
        .btn-action {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 18px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.2);
            transition: var(--transition-smooth);
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-action:active {
            transform: translateY(0);
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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated-entry {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Search Switch and Loader Styling */
        .search-mode-switch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: var(--transition-smooth);
        }
        
        .search-mode-switch:hover {
            color: var(--text-main);
        }

        .search-mode-switch input {
            display: none;
        }

        .switch-slider {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            background-color: #cbd5e1;
            border-radius: 20px;
            transition: var(--transition-smooth);
        }

        .switch-slider::before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: var(--transition-smooth);
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        .search-mode-switch input:checked + .switch-slider {
            background: var(--primary-gradient);
        }

        .search-mode-switch input:checked + .switch-slider::before {
            transform: translateX(16px);
        }

        .search-tag-pill {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 4px 10px;
            background-color: #f1f5f9;
            color: var(--text-muted);
            border-radius: 30px;
            border: 1px solid var(--border-color);
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .search-tag-pill i {
            font-size: 0.65rem;
            color: var(--primary);
        }

        .match-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .matched-fields-container {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .matched-field-pill {
            background-color: #f8fafc;
            border: 1px dashed var(--border-color);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        /* Pulse loader bar */
        .search-loader-bar {
            height: 3px;
            width: 0%;
            background: var(--primary-gradient);
            border-radius: 3px;
            margin-top: 8px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-loader-bar.loading {
            width: 100%;
            animation: pulse-loader 1.5s infinite ease-in-out;
        }

        @keyframes pulse-loader {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        .api-badge {
            font-size: 0.7rem;
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .api-badge.deep {
            background-color: #faf5ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }

        .api-badge.offline {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
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
                    <?php if (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'Admin'): ?>
                        <a href="index.php?action=audit" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="font-weight: 600; font-size: 0.85rem; transition: var(--transition-smooth);">
                            <i class="fa-solid fa-clock-rotate-left"></i> Bitácora
                        </a>
                    <?php endif; ?>
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
                        <i class="fa-solid fa-building me-1 text-primary"></i> Gestión de Calidad
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container dashboard-container animated-entry">
        <div class="row mb-4">
            <div class="col-lg-8">
                <h2 class="mb-1 text-slate-800">Repositorio de Documentos</h2>
                <p class="text-muted">Consulta, visualiza y gestiona las versiones vigentes del sistema de calidad.</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-card">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="search-input-wrapper">
                        <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
                        <input type="text" id="documentSearch" class="form-control search-control" placeholder="Buscar documentos por código, título o descripción...">
                    </div>
                    <div class="search-loader-bar" id="searchLoaderBar"></div>
                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <label class="search-mode-switch">
                                <input type="checkbox" id="searchModeToggle">
                                <span class="switch-slider"></span>
                                <span>Búsqueda profunda (Deep Search)</span>
                            </label>
                            <span id="searchStatusBadge" style="display: none;"></span>
                        </div>
                        <div id="searchTagsTitle" class="text-muted small" style="display: none; font-weight: 500;">
                            Palabras clave analizadas:
                        </div>
                    </div>
                    <div id="searchTagsContainer" class="mt-2 d-flex flex-wrap gap-1" style="display: none;">
                        <!-- las etiquetas dinámicas de la API irán aquí -->
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="text-muted small" id="docCounter">
                        Mostrando <?= isset($documents) ? count($documents) : 0 ?> documentos vigentes
                    </span>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="table-card">
            <div class="table-container">
                <table class="table custom-table" id="documentsTable">
                    <thead>
                        <tr>
                            <th style="width: 15%">Código</th>
                            <th style="width: 45%">Título del Documento</th>
                            <th style="width: 12%">Versión</th>
                            <th style="width: 13%">Estado</th>
                            <th style="width: 15%" class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($documents) && count($documents) > 0): ?>
                            <?php foreach ($documents as $doc): 
                                // Determinar la clase CSS de la insignia según el estado
                                $status = mb_strtolower($doc['status_name'] ?? '');
                                $statusClass = 'status-default';
                                if (str_contains($status, 'aprobado')) {
                                    $statusClass = 'status-aprobado';
                                } elseif (str_contains($status, 'publicado')) {
                                    $statusClass = 'status-publicado';
                                } elseif (str_contains($status, 'pendiente')) {
                                    $statusClass = 'status-pendiente';
                                } elseif (str_contains($status, 'obsoleto')) {
                                    $statusClass = 'status-obsoleto';
                                }
                            ?>
                                <tr class="doc-row">
                                    <td>
                                        <span class="document-code"><?= htmlspecialchars($doc['document_code']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-slate-800 text-title-search"><?= htmlspecialchars($doc['title']) ?></div>
                                        <div class="text-muted small mt-1 text-desc-search"><?= htmlspecialchars($doc['description'] ?? 'Sin descripción.') ?></div>
                                    </td>
                                    <td>
                                        <span class="version-tag">V-<?= htmlspecialchars($doc['version_number']) ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <i class="fa-solid fa-circle-dot" style="font-size: 0.5rem"></i>
                                            <?= htmlspecialchars($doc['status_name'] ?? 'Desconocido') ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="index.php?action=view&id=<?= $doc['id'] ?>" class="btn-action">
                                            <span>Visualizar</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="noDocsRow">
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fa-regular fa-folder-open empty-state-icon"></i>
                                        <h5>No hay documentos disponibles</h5>
                                        <p class="text-muted">No se encontraron documentos vigentes o sincronizados en la base de datos.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <!-- Fila vacía dinámica para búsquedas sin resultados -->
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-magnifying-glass empty-state-icon"></i>
                                    <h5>Sin coincidencias</h5>
                                    <p class="text-muted">No encontramos documentos que coincidan con tu criterio de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 border-top bg-white text-center">
        <div class="container text-muted small">
            &copy; 2026 QualityDoc. Todos los derechos reservados. | Diseñado con fines de excelencia y trazabilidad.
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Búsqueda Inteligente mediante API Node/MongoDB -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('documentSearch');
            const modeToggle = document.getElementById('searchModeToggle');
            const loaderBar = document.getElementById('searchLoaderBar');
            const tagsContainer = document.getElementById('searchTagsContainer');
            const tagsTitle = document.getElementById('searchTagsTitle');
            const statusBadge = document.getElementById('searchStatusBadge');
            const counter = document.getElementById('docCounter');
            const tableBody = document.querySelector('#documentsTable tbody');
            const searchIcon = document.getElementById('searchIcon');
            
            // Guardar las filas originales cargadas por PHP desde PostgreSQL
            const originalRows = Array.from(tableBody.querySelectorAll('.doc-row'));
            const originalNoDocsRow = document.getElementById('noDocsRow');
            const originalCounterText = counter ? counter.textContent.trim() : '';
            
            const noDocsRowHTML = `
                <tr id="noDocsRow">
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fa-regular fa-folder-open empty-state-icon"></i>
                            <h5>No hay documentos disponibles</h5>
                            <p class="text-muted">No se encontraron documentos vigentes o sincronizados en la base de datos.</p>
                        </div>
                    </td>
                </tr>
            `;

            const noResultsRowHTML = `
                <tr id="noResultsRow">
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fa-solid fa-magnifying-glass empty-state-icon"></i>
                            <h5>Sin coincidencias</h5>
                            <p class="text-muted">No encontramos documentos que coincidan con tu criterio de búsqueda en MongoDB.</p>
                        </div>
                    </td>
                </tr>
            `;

            let isApiAvailable = true; // Variable para saber si la API responde
            let debounceTimer;

            // Función Debounce para limitar peticiones consecutivas
            function debounce(func, delay) {
                return function(...args) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            // Modo de filtrado local en caso de desconexión del backend Node
            function performLocalFilter(filterText) {
                let visibleCount = 0;
                const filter = filterText.toLowerCase().trim();
                
                originalRows.forEach(row => {
                    const code = row.querySelector('.document-code').textContent.toLowerCase();
                    const title = row.querySelector('.text-title-search').textContent.toLowerCase();
                    const desc = row.querySelector('.text-desc-search').textContent.toLowerCase();
                    
                    if (code.includes(filter) || title.includes(filter) || desc.includes(filter)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Limpiar etiquetas de la API dado que es búsqueda local
                tagsContainer.style.display = 'none';
                tagsTitle.style.display = 'none';
                
                // Mostrar/ocultar fila de sin resultados
                const localNoResults = document.getElementById('noResultsRow');
                if (visibleCount === 0 && originalRows.length > 0) {
                    if (localNoResults) {
                        localNoResults.style.display = '';
                    } else {
                        tableBody.insertAdjacentHTML('beforeend', noResultsRowHTML);
                    }
                } else if (localNoResults) {
                    localNoResults.style.display = 'none';
                }

                if (counter) {
                    counter.textContent = `Mostrando ${visibleCount} de ${originalRows.length} documentos vigentes (Filtro Local)`;
                }
            }

            // Restaurar estado inicial de la tabla
            function restoreInitialTable() {
                tableBody.innerHTML = '';
                
                if (originalRows.length > 0) {
                    originalRows.forEach(row => {
                        row.style.display = '';
                        tableBody.appendChild(row);
                    });
                } else if (originalNoDocsRow) {
                    tableBody.appendChild(originalNoDocsRow);
                } else {
                    tableBody.insertAdjacentHTML('beforeend', noDocsRowHTML);
                }

                tagsContainer.style.display = 'none';
                tagsTitle.style.display = 'none';
                statusBadge.style.display = 'none';
                loaderBar.classList.remove('loading');
                if (counter) counter.textContent = originalCounterText;
            }

            // Renderizar los resultados asíncronos en la tabla
            function renderResults(data, searchTags, mode) {
                tableBody.innerHTML = '';
                
                if (data.length === 0) {
                    tableBody.insertAdjacentHTML('beforeend', noResultsRowHTML);
                    if (counter) counter.textContent = `0 coincidencias (MongoDB)`;
                    return;
                }

                data.forEach(doc => {
                    // Mapear el estado a clases de badge existentes
                    const statusStr = (doc.statusId || doc.lifecycleStatus || '').toLowerCase();
                    let statusClass = 'status-default';
                    let statusLabel = doc.statusId || doc.lifecycleStatus || 'Desconocido';
                    
                    if (statusStr.includes('aprobado') || statusStr === 'active' || statusStr === 'activo') {
                        statusClass = 'status-aprobado';
                        statusLabel = 'Aprobado';
                    } else if (statusStr.includes('publicado')) {
                        statusClass = 'status-publicado';
                        statusLabel = 'Publicado';
                    } else if (statusStr.includes('pendiente')) {
                        statusClass = 'status-pendiente';
                        statusLabel = 'Pendiente';
                    } else if (statusStr.includes('obsoleto') || statusStr === 'obsolete') {
                        statusClass = 'status-obsoleto';
                        statusLabel = 'Obsoleto';
                    }

                    // Badge de relevancia (matchCount)
                    const matchCount = doc._matchCount || 0;
                    const relevanceBadge = matchCount > 0 
                        ? `<span class="match-badge ms-2" title="Relevancia en etiquetas de búsqueda"><i class="fa-solid fa-star"></i> ${matchCount}</span>` 
                        : '';

                    // Mostrar campos coincidentes si es Búsqueda Profunda
                    let matchedFieldsHTML = '';
                    if (mode === 'deep' && doc._matchedFields && doc._matchedFields.length > 0) {
                        const fieldsList = doc._matchedFields.map(f => `<span class="matched-field-pill">${f}</span>`).join('');
                        matchedFieldsHTML = `
                            <div class="matched-fields-container">
                                <span class="small text-muted me-1">Coincidencia en:</span>
                                ${fieldsList}
                            </div>
                        `;
                    }

                    const rowHTML = `
                        <tr class="doc-row animated-entry">
                            <td>
                                <span class="document-code">${escapeHTML(doc.documentCode || 'S/C')}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="fw-semibold text-slate-800 text-title-search">${escapeHTML(doc.title)}</div>
                                    ${relevanceBadge}
                                </div>
                                <div class="text-muted small mt-1 text-desc-search">${escapeHTML(doc.description || 'Sin descripción.')}</div>
                                ${matchedFieldsHTML}
                            </td>
                            <td>
                                <span class="version-tag">V-${escapeHTML(doc.versionNumber || '1')}</span>
                            </td>
                            <td>
                                <span class="status-badge ${statusClass}">
                                    <i class="fa-solid fa-circle-dot" style="font-size: 0.5rem"></i>
                                    ${escapeHTML(statusLabel)}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="index.php?action=view&id=${escapeHTML(doc.id)}" class="btn-action">
                                    <span>Visualizar</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', rowHTML);
                });

                if (counter) {
                    counter.textContent = `Mostrando ${data.length} de ${originalRows.length} documentos vigentes`;
                }
            }

            // Renderizar etiquetas devueltas por la API
            function renderSearchTags(tags) {
                if (!tags || tags.length === 0) {
                    tagsContainer.style.display = 'none';
                    tagsTitle.style.display = 'none';
                    return;
                }
                
                tagsContainer.innerHTML = tags.map(tag => `
                    <span class="search-tag-pill">
                        <i class="fa-solid fa-tag"></i> ${escapeHTML(tag)}
                    </span>
                `).join('');
                
                tagsContainer.style.display = '';
                tagsTitle.style.display = '';
            }

            function escapeHTML(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Consulta AJAX a la API
            async function performSearch(query, isDeep) {
                if (!query.trim()) {
                    restoreInitialTable();
                    return;
                }

                loaderBar.classList.add('loading');
                if (searchIcon) {
                    searchIcon.className = 'fa-solid fa-spinner fa-spin';
                }

                const searchMode = isDeep ? 'deepsearch' : 'search';
                const apiUrl = `http://localhost:3000/api/documents/${searchMode}?q=${encodeURIComponent(query)}`;

                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 4000); // 4s timeout
                    
                    const response = await fetch(apiUrl, { signal: controller.signal });
                    clearTimeout(timeoutId);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        isApiAvailable = true; // Sigue activa
                        
                        // Actualizar insignia de estado de API
                        statusBadge.className = isDeep ? 'api-badge deep' : 'api-badge';
                        statusBadge.innerHTML = isDeep 
                            ? `<i class="fa-solid fa-brain"></i> MongoDB Deep` 
                            : `<i class="fa-solid fa-bolt"></i> MongoDB Tag`;
                        statusBadge.style.display = '';

                        renderResults(result.data || [], result.searchTags || [], isDeep ? 'deep' : 'standard');
                        renderSearchTags(result.searchTags || []);
                    } else {
                        throw new Error(result.message || 'Error en respuesta');
                    }
                } catch (error) {
                    console.warn('API de búsqueda inaccesible, activando fallback local:', error.message);
                    isApiAvailable = false;
                    
                    statusBadge.className = 'api-badge offline';
                    statusBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Modo Offline`;
                    statusBadge.style.display = '';
                    
                    performLocalFilter(query);
                } finally {
                    loaderBar.classList.remove('loading');
                    if (searchIcon) {
                        searchIcon.className = 'fa-solid fa-magnifying-glass';
                    }
                }
            }

            // Event Listeners
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function() {
                    const q = this.value;
                    const isDeep = modeToggle ? modeToggle.checked : false;
                    
                    if (isApiAvailable) {
                        performSearch(q, isDeep);
                    } else {
                        if (!q.trim()) {
                            restoreInitialTable();
                        } else {
                            performLocalFilter(q);
                            statusBadge.className = 'api-badge offline';
                            statusBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Modo Offline`;
                            statusBadge.style.display = '';
                        }
                    }
                }, 300));
            }

            if (modeToggle) {
                modeToggle.addEventListener('change', function() {
                    const q = searchInput ? searchInput.value : '';
                    if (q.trim()) {
                        performSearch(q, this.checked);
                    }
                });
            }
        });
    </script>
</body>

</html>