<?php
require_once 'models/Document.php';

class DocumentController
{
    private $model;

    public function __construct()
    {
        $this->model = new Document();
    }

    // Acción por defecto: Mostrar la lista de archivos
    public function index()
    {
        $documents = $this->model->getAllLatest();
        require_once 'views/list.php'; // Mandamos los datos a la vista
    }

    // Acción para ver un archivo y su historial
    public function view()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $id = $_GET['id'];
        $document = $this->model->getById($id);

        if ($document) {
            // 1. Registramos en la auditoría que se abrió el archivo
            $this->model->logView($id);

            // 2. Sacamos el historial de versiones usando el document_code
            $history = $this->model->getHistory($document['document_code']);

            // 3. Cargamos la vista del visualizador
            require_once 'views/view.php';
        } else {
            echo "Documento no encontrado.";
        }
    }

    // Acción para el botón "Marcar como leído"
    public function acknowledge()
    {
        if (isset($_POST['document_id'])) {
            $id = $_POST['document_id'];
            $this->model->markAsRead($id);

            // Redirigir de vuelta al visualizador
            header("Location: index.php?action=view&id=" . $id);
        }
    }

    // Acción para subir o sincronizar un documento desde la API de C#
    public function upload()
    {
        // Asegurarnos de que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido. Utilice POST.']);
            exit;
        }

        // Obtener el cuerpo de la petición (JSON)
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido o vacío.']);
            exit;
        }

        // Normalizar claves (PascalCase o camelCase) a PascalCase
        $normalizedData = [];
        $expectedFields = [
            'Id', 'DocumentCode', 'Title', 'Description', 'FilePath', 
            'VersionNumber', 'IsLatest', 'StatusName', 'CompanyId', 
            'CompanyName', 'AuthorId', 'CreatedAt'
        ];
        foreach ($expectedFields as $field) {
            $camelField = lcfirst($field);
            if (isset($data[$field])) {
                $normalizedData[$field] = $data[$field];
            } elseif (isset($data[$camelField])) {
                $normalizedData[$field] = $data[$camelField];
            } else {
                $normalizedData[$field] = null;
            }
        }

        // Validar campos requeridos
        $requiredFields = ['Id', 'Title', 'VersionNumber', 'IsLatest'];
        foreach ($requiredFields as $field) {
            if ($normalizedData[$field] === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['error' => "El campo '$field' es obligatorio."]);
                exit;
            }
        }

        // Llamar al modelo para guardar/sincronizar el documento
        $result = $this->model->saveOrUpdate($normalizedData);

        header('Content-Type: application/json');
        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Documento sincronizado exitosamente en PostgreSQL.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error interno al guardar en la base de datos.']);
        }
        exit;
    }
}
?>