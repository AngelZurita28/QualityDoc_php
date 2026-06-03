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

    // Guardar o actualizar un documento proveniente de la sincronización
    public function saveOrUpdate($data)
    {
        try {
            $this->conn->beginTransaction();

            // Comprobar si ya existe el documento por su ID
            $queryCheck = "SELECT id FROM documents WHERE id = :id";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(':id', $data['Id']);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetch();

            $isLatest = filter_var($data['IsLatest'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $documentCode = isset($data['DocumentCode']) ? $data['DocumentCode'] : null;

            if ($exists) {
                // Actualizar
                $query = "UPDATE documents SET 
                            document_code = :document_code,
                            title = :title,
                            description = :description,
                            file_path = :file_path,
                            version_number = :version_number,
                            is_latest = :is_latest,
                            status_name = :status_name,
                            company_id = :company_id,
                            company_name = :company_name,
                            author_id = :author_id,
                            sqlserver_created_at = :sqlserver_created_at,
                            synced_at = CURRENT_TIMESTAMP
                          WHERE id = :id";
            } else {
                // Insertar
                $query = "INSERT INTO documents (
                            id, document_code, title, description, file_path, 
                            version_number, is_latest, status_name, company_id, 
                            company_name, author_id, sqlserver_created_at, synced_at
                          ) VALUES (
                            :id, :document_code, :title, :description, :file_path, 
                            :version_number, :is_latest, :status_name, :company_id, 
                            :company_name, :author_id, :sqlserver_created_at, CURRENT_TIMESTAMP
                          )";
            }

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindValue(':id', $data['Id']);
            $stmt->bindValue(':document_code', $documentCode);
            $stmt->bindValue(':title', $data['Title']);
            $stmt->bindValue(':description', isset($data['Description']) ? $data['Description'] : null);
            $stmt->bindValue(':file_path', isset($data['FilePath']) ? $data['FilePath'] : null);
            $stmt->bindValue(':version_number', $data['VersionNumber'], PDO::PARAM_INT);
            
            // bindValue for bool can vary in PDO pgsql driver, binding as PARAM_BOOL or raw int
            $stmt->bindValue(':is_latest', $isLatest, PDO::PARAM_BOOL);
            $stmt->bindValue(':status_name', isset($data['StatusName']) ? $data['StatusName'] : null);
            $stmt->bindValue(':company_id', isset($data['CompanyId']) ? $data['CompanyId'] : null, PDO::PARAM_INT);
            $stmt->bindValue(':company_name', isset($data['CompanyName']) ? $data['CompanyName'] : null);
            $stmt->bindValue(':author_id', isset($data['AuthorId']) ? $data['AuthorId'] : null, PDO::PARAM_INT);
            
            $createdAt = isset($data['CreatedAt']) ? $data['CreatedAt'] : null;
            $stmt->bindValue(':sqlserver_created_at', $createdAt);

            $stmt->execute();

            // Si este documento es la versión más reciente, marcar los anteriores con el mismo document_code como is_latest = FALSE
            if ($isLatest && !empty($documentCode)) {
                $queryUpdateOld = "UPDATE documents SET is_latest = FALSE 
                                   WHERE document_code = :document_code AND id <> :id";
                $stmtUpdateOld = $this->conn->prepare($queryUpdateOld);
                $stmtUpdateOld->bindParam(':document_code', $documentCode);
                $stmtUpdateOld->bindParam(':id', $data['Id']);
                $stmtUpdateOld->execute();
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error en saveOrUpdate: " . $e->getMessage());
            return false;
        }
    }
}
?>