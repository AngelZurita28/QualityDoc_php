<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QualityDoc - Sistema de Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4">Repositorio de Documentos</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Título</th>
                            <th>Versión</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($documents) && count($documents) > 0): ?>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($doc['document_code']) ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($doc['title']) ?>
                                        </strong><br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($doc['description']) ?>
                                        </small>
                                    </td>
                                    <td>V-
                                        <?= htmlspecialchars($doc['version_number']) ?>
                                    </td>
                                    <td><span class="badge bg-success">
                                            <?= htmlspecialchars($doc['status_name']) ?>
                                        </span></td>
                                    <td>
                                        <a href="index.php?action=view&id=<?= $doc['id'] ?>" class="btn btn-primary btn-sm">
                                            Abrir Documento
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay documentos disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>