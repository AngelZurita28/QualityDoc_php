<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Visualizando:
        <?= htmlspecialchars($document['title']) ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4 mb-5">
        <a href="index.php" class="btn btn-outline-secondary mb-3">← Volver al listado</a>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <?= htmlspecialchars($document['title']) ?> (V-
                            <?= htmlspecialchars($document['version_number']) ?>)
                        </h5>
                        <span class="badge bg-secondary">
                            <?= htmlspecialchars($document['status_name']) ?>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <iframe src="<?= htmlspecialchars($document['file_path']) ?>" width="100%" height="700px"
                            style="border: none;"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">Confirmación de Lectura</h5>
                        <p class="card-text text-muted small">Al presionar este botón, se registrará en la auditoría que
                            has leído y comprendido este documento.</p>

                        <form method="POST" action="index.php?action=acknowledge">
                            <input type="hidden" name="document_id" value="<?= $document['id'] ?>">
                            <button type="submit" class="btn btn-primary w-100">✔ He leído y comprendido</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6>Detalles</h6>
                        <hr>
                        <p class="mb-1"><strong>Código:</strong>
                            <?= htmlspecialchars($document['document_code']) ?>
                        </p>
                        <p class="mb-1"><strong>Empresa:</strong>
                            <?= htmlspecialchars($document['company_name'] ?? 'N/A') ?>
                        </p>
                        <p class="mb-1"><strong>Descripción:</strong>
                            <?= htmlspecialchars($document['description']) ?>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Historial de Versiones</h6>
                        <hr>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($history as $ver): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <small class="d-block fw-bold">Versión
                                            <?= $ver['version_number'] ?>
                                        </small>
                                        <small class="text-muted text-truncate"
                                            style="max-width: 150px; display:inline-block;">
                                            <?= htmlspecialchars($ver['title']) ?>
                                        </small>
                                    </div>
                                    <?php if ($ver['is_latest']): ?>
                                        <span class="badge bg-success rounded-pill">Actual</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill">Obsoleta</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>