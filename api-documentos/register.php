<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
require 'db.php'; // Traemos la conexión

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['document_code'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Falta el ID o el Código del Documento"]);
    exit;
}

try {
    // is_latest es TRUE por defecto según tu SQL, y version_number es 1
    $sql = "INSERT INTO documents (
                id, document_code, title, description, file_path, 
                status_name, company_id, company_name, author_id, sqlserver_created_at
            ) VALUES (
                :id, :document_code, :title, :description, :file_path, 
                :status_name, :company_id, :company_name, :author_id, :sqlserver_created_at
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $data['id'],
        ':document_code' => $data['document_code'],
        ':title' => $data['title'],
        ':description' => $data['description'] ?? null,
        ':file_path' => $data['file_path'] ?? null,
        ':status_name' => $data['status_name'] ?? null,
        ':company_id' => $data['company_id'] ?? null,
        ':company_name' => $data['company_name'] ?? null,
        ':author_id' => $data['author_id'] ?? null,
        ':sqlserver_created_at' => $data['sqlserver_created_at'] ?? null
    ]);

    echo json_encode(["status" => "success", "message" => "Documento registrado correctamente."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>