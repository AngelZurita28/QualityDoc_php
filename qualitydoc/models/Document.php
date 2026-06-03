<?php
require_once 'config/db.php';

class Document
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener solo las versiones más recientes para la lista principal
    public function getAllLatest()
    {
        $query = "SELECT * FROM documents WHERE is_latest = TRUE ORDER BY title ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un documento específico por su ID
    public function getById($id)
    {
        $query = "SELECT * FROM documents WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener el historial completo de un documento usando su código
    public function getHistory($document_code)
    {
        $query = "SELECT id, version_number, is_latest, title 
                  FROM documents 
                  WHERE document_code = :document_code 
                  ORDER BY version_number DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':document_code', $document_code);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Auditoría: Registrar que el usuario 1 abrió el archivo
    public function logView($document_id)
    {
        // Tu compañero dijo: "prueba con un usuario por defecto que se guarde de que usuario 1 visualizo"
        $query = "INSERT INTO document_views_audit (document_id, user_id, company_id, area) 
                  VALUES (:document_id, 1, 1, 'Sistemas')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':document_id', $document_id);
        $stmt->execute();
    }

    // Acuse de lectura: El usuario hace clic en "Marcar como leído"
    public function markAsRead($document_id)
    {
        try {
            // ON CONFLICT DO NOTHING evita que truene si el usuario le da clic dos veces al mismo archivo
            $query = "INSERT INTO document_read_acknowledgments (document_id, user_id, company_id, area) 
                      VALUES (:document_id, 1, 1, 'Sistemas') 
                      ON CONFLICT (document_id, user_id) DO NOTHING";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':document_id', $document_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>