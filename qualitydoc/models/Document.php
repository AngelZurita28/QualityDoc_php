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

    // Obtener el ID numérico de la empresa a partir de su nombre (sincronizada en documents)
    public function getCompanyIdByName($company_name)
    {
        if (empty($company_name)) {
            return null;
        }
        $query = "SELECT company_id FROM documents WHERE company_name = :company_name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':company_name', $company_name);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? (int)$row['company_id'] : null;
    }

    // Auditoría: Registrar que el usuario abrió el archivo
    public function logView($document_id, $user_id, $user_name, $company_name, $area)
    {
        $company_id = $this->getCompanyIdByName($company_name);
        if ($company_id === null) {
            $doc = $this->getById($document_id);
            if ($doc && isset($doc['company_id'])) {
                $company_id = (int)$doc['company_id'];
            }
        }

        $query = "INSERT INTO document_views_audit (document_id, user_id, user_name, company_id, area) 
                  VALUES (:document_id, :user_id, :user_name, :company_id, :area)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':document_id', $document_id);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area);
        $stmt->execute();
    }

    // Acuse de lectura: El usuario hace clic en "Marcar como leído"
    public function markAsRead($document_id, $user_id, $user_name, $company_name, $area)
    {
        try {
            $company_id = $this->getCompanyIdByName($company_name);
            if ($company_id === null) {
                $doc = $this->getById($document_id);
                if ($doc && isset($doc['company_id'])) {
                    $company_id = (int)$doc['company_id'];
                }
            }

            // ON CONFLICT DO NOTHING evita que truene si el usuario le da clic dos veces al mismo archivo
            $query = "INSERT INTO document_read_acknowledgments (document_id, user_id, user_name, company_id, area) 
                      VALUES (:document_id, :user_id, :user_name, :company_id, :area) 
                      ON CONFLICT (document_id, user_id) DO NOTHING";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':document_id', $document_id);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->bindParam(':area', $area);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Comprobar si el usuario ya confirmó la lectura de este documento
    public function hasUserAcknowledged($document_id, $user_id)
    {
        $query = "SELECT COUNT(*) FROM document_read_acknowledgments 
                  WHERE document_id = :document_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':document_id', $document_id);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
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

            $isLatest = 1;
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

            // Marcar los anteriores con el mismo document_code como is_latest = FALSE y status_name = 'Obsoleto'
            if (!empty($documentCode)) {
                $queryUpdateOld = "UPDATE documents SET is_latest = FALSE, status_name = 'Obsoleto' 
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

    // Obtener la bitácora de visualizaciones completa
    public function getViewAuditLogs()
    {
        $query = "SELECT a.id, a.user_id, a.user_name, a.company_id, a.area, a.viewed_at,
                         d.document_code, d.title, d.version_number
                  FROM document_views_audit a
                  JOIN documents d ON a.document_id = d.id
                  ORDER BY a.viewed_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener el listado de acuses de lectura registrados
    public function getReadAcknowledgmentLogs()
    {
        $query = "SELECT r.id, r.user_id, r.user_name, r.company_id, r.area, r.acknowledged_at,
                         d.document_code, d.title, d.version_number
                  FROM document_read_acknowledgments r
                  JOIN documents d ON r.document_id = d.id
                  ORDER BY r.acknowledged_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>