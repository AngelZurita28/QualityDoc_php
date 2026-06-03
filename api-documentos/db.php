<?php
$host = 'localhost';
$db = 'qualitydoc';       // Tu nueva base de datos
$user = 'postgres';
$pass = '1234'; // Cambia esto por tu contraseña
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()]);
    exit;
}
?>