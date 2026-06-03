<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['document_code']) || !isset($data['version_number'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios (id, document_code, version_number)"]);
    exit;
}

try {
    $pdo->beginTransaction(); // Iniciamos la transacción de seguridad

    // 1. Marcar todas las versiones anteriores de este documento como "no recientes" (is_latest = false)
    $sql1 = "UPDATE documents SET is_latest = false WHERE document_code = :document_code";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([':document_code' => $data['document_code']]);

    // 2. Insertar la nueva versión (como es nuevo registro, is_latest será true por defecto)
    $sql2 = "INSERT INTO documents (
                id, document_code, title, description, file_path, version_number,
                status_name, company_id, company_name, author_id, sqlserver_created_at
            ) VALUES (
                :id, :document_code, :title, :description, :file_path, :version_number,
                :status_name, :company_id, :company_name, :author_id, :sqlserver_created_at
            )";

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        ':id' => $data['id'],
        ':document_code' => $data['document_code'],
        ':title' => $data['title'],
        ':description' => $data['description'] ?? null,
        ':file_path' => $data['file_path'] ?? null,
        ':version_number' => $data['version_number'], // Aquí debes mandar un 2, 3, etc.
        ':status_name' => $data['status_name'] ?? null,
        ':company_id' => $data['company_id'] ?? null,
        ':company_name' => $data['company_name'] ?? null,
        ':author_id' => $data['author_id'] ?? null,
        ':sqlserver_created_at' => $data['sqlserver_created_at'] ?? null
    ]);

    $pdo->commit(); // Confirmamos los cambios
    echo json_encode(["status" => "success", "message" => "Nueva versión actualizada correctamente."]);

} catch (PDOException $e) {
    $pdo->rollBack(); // Si algo falla, revertimos todo
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>