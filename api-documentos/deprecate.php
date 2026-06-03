<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Falta el ID del documento"]);
    exit;
}

try {
    // Quitamos la etiqueta de versión más reciente
    $sql = "UPDATE documents SET is_latest = false WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $data['id']]);

    echo json_encode(["status" => "success", "message" => "Documento deprecado."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>