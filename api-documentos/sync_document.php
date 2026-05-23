<?php
// 1. Configurar las cabeceras para aceptar JSON
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Validar que la petición sea por método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Usa POST."]);
    exit;
}

// 3. Leer el JSON que enviará ASP.NET
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// 4. Validar que el JSON no esté vacío y traiga un ID
if (empty($data) || !isset($data['id']) || trim($data['id']) === '') {
    http_response_code(422); // Unprocessable Entity
    echo json_encode(["status" => "error", "message" => "Falta el campo obligatorio: id o el JSON es inválido."]);
    exit;
}

// 5. Datos de conexión a PostgreSQL
$host = 'localhost';
$db = 'sistema_documentos'; // El nombre que le pusimos en pgAdmin
$user = 'postgres'; // El usuario por defecto
$pass = '1234'; // <--- CAMBIA ESTO POR TU CONTRASEÑA DE POSTGRESQL
$port = '5432';

try {
    // Iniciar conexión con PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 6. Preparar el Upsert (Inserta, o si existe, actualiza)
    $sql = "INSERT INTO documents (
                id, title, description, version, company, author, status, 
                file_size, extension, mime_type, checksum, sha256, 
                file_created_at, file_updated_at, specific_metadata
            ) VALUES (
                :id, :title, :description, :version, :company, :author, :status, 
                :file_size, :extension, :mime_type, :checksum, :sha256, 
                :file_created_at, :file_updated_at, :specific_metadata
            ) 
            ON CONFLICT (id) DO UPDATE SET 
                title = EXCLUDED.title,
                description = EXCLUDED.description,
                version = EXCLUDED.version,
                company = EXCLUDED.company,
                author = EXCLUDED.author,
                status = EXCLUDED.status,
                file_size = EXCLUDED.file_size,
                extension = EXCLUDED.extension,
                mime_type = EXCLUDED.mime_type,
                checksum = EXCLUDED.checksum,
                sha256 = EXCLUDED.sha256,
                file_created_at = EXCLUDED.file_created_at,
                file_updated_at = EXCLUDED.file_updated_at,
                specific_metadata = EXCLUDED.specific_metadata,
                sync_date = CURRENT_TIMESTAMP";

    $stmt = $pdo->prepare($sql);

    // Formatear metadata extra a JSON para PostgreSQL
    $metadata = isset($data['specific_metadata']) ? json_encode($data['specific_metadata']) : json_encode([]);

    // 7. Ejecutar mapeando los datos recibidos
    $stmt->execute([
        ':id' => $data['id'],
        ':title' => $data['title'] ?? 'Sin título',
        ':description' => $data['description'] ?? null,
        ':version' => $data['version'] ?? '1.0',
        ':company' => $data['company'] ?? null,
        ':author' => $data['author'] ?? null,
        ':status' => $data['status'] ?? 'Pendiente',
        ':file_size' => $data['file_size'] ?? 0,
        ':extension' => $data['extension'] ?? null,
        ':mime_type' => $data['mime_type'] ?? null,
        ':checksum' => $data['checksum'] ?? null,
        ':sha256' => $data['sha256'] ?? null,
        ':file_created_at' => $data['file_created_at'] ?? null,
        ':file_updated_at' => $data['file_updated_at'] ?? null,
        ':specific_metadata' => $metadata
    ]);

    // 8. Responder éxito al sistema .NET
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Documento sincronizado correctamente.",
        "id" => $data['id']
    ]);

}
catch (PDOException $e) {
    // 9. Manejar errores de conexión o de inserción
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error de BD: " . $e->getMessage()
    ]);
}
?>