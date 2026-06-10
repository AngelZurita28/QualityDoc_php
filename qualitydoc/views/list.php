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
    </style>
</head>

<body>

    <!-- Header / Navbar -->
    <header class="app-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="brand-logo">
                <i class="fa-solid fa-shield-halved"></i> QualityDoc
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark border p-2 px-3 rounded-pill">
                    <i class="fa-solid fa-building me-1 text-primary"></i> Gestión de Calidad
                </span>
            </div>
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
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="documentSearch" class="form-control search-control" placeholder="Buscar documentos por código, título o descripción...">
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
    
    <!-- Filtro de Búsqueda Dinámico en Cliente -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('documentSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.doc-row');
                    const noResultsRow = document.getElementById('noResultsRow');
                    const noDocsRow = document.getElementById('noDocsRow');
                    const counter = document.getElementById('docCounter');
                    
                    let visibleCount = 0;
                    
                    rows.forEach(row => {
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
                    
                    if (visibleCount === 0 && rows.length > 0) {
                        noResultsRow.style.display = '';
                    } else {
                        noResultsRow.style.display = 'none';
                    }
                    
                    if (counter) {
                        counter.textContent = `Mostrando ${visibleCount} de ${rows.length} documentos vigentes`;
                    }
                });
            }
        });
    </script>
</body>

</html>