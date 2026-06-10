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
            // 1. Registramos en la auditoría que se abrió el archivo con los datos del usuario logueado
            $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 1;
            $userName = isset($_SESSION['user']['nombre']) ? $_SESSION['user']['nombre'] : 'Usuario Test';
            $companyName = isset($_SESSION['user']['empresa']) ? $_SESSION['user']['empresa'] : 'Sin Empresa';
            $area = isset($_SESSION['user']['departamento']) ? $_SESSION['user']['departamento'] : 'Sistemas';

            $this->model->logView($id, $userId, $userName, $companyName, $area);

            // 2. Sacamos el historial de versiones usando el document_code
            $history = $this->model->getHistory($document['document_code']);

            // 3. Comprobar si el usuario ya dio acuse de lectura
            $hasAcknowledged = $this->model->hasUserAcknowledged($id, $userId);

            // 4. Cargamos la vista del visualizador
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
            
            $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 1;
            $userName = isset($_SESSION['user']['nombre']) ? $_SESSION['user']['nombre'] : 'Usuario Test';
            $companyName = isset($_SESSION['user']['empresa']) ? $_SESSION['user']['empresa'] : 'Sin Empresa';
            $area = isset($_SESSION['user']['departamento']) ? $_SESSION['user']['departamento'] : 'Sistemas';

            $this->model->markAsRead($id, $userId, $userName, $companyName, $area);

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

    // Servir un archivo desde la URL remota basada en API_LOGIN_URI
    public function serveFile()
    {
        if (!isset($_GET['id'])) {
            header("HTTP/1.1 400 Bad Request");
            echo "ID de documento requerido.";
            exit;
        }

        $id = $_GET['id'];
        $document = $this->model->getById($id);

        if ($document && !empty($document['file_path'])) {
            $filePath = $document['file_path'];
            
            // Construir la URL remota usando API_LOGIN_URI
            $apiLoginUri = getenv('API_LOGIN_URI') ?: 'http://host.docker.internal:5000';
            $apiLoginUri = rtrim($apiLoginUri, '/');
            $cleanPath = '/' . ltrim($filePath, '/');
            $remoteUrl = $apiLoginUri . $cleanPath;

            // Obtener el archivo remoto
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'timeout' => 15
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $fileData = @file_get_contents($remoteUrl, false, $context);

            if ($fileData !== false) {
                // Analizar headers para propagar tipo de contenido y longitud
                $mimeType = 'application/pdf'; // fallback
                $contentLength = strlen($fileData);
                
                if (isset($http_response_header)) {
                    foreach ($http_response_header as $header) {
                        if (stripos($header, 'Content-Type:') === 0) {
                            $mimeType = trim(substr($header, 13));
                        }
                        if (stripos($header, 'Content-Length:') === 0) {
                            $contentLength = (int)trim(substr($header, 15));
                        }
                    }
                }

                header("Content-Type: " . $mimeType);
                header("Content-Length: " . $contentLength);
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                echo $fileData;
                exit;
            }
        }
        header("HTTP/1.1 404 Not Found");
        echo "Archivo no encontrado o no disponible en el servidor remoto.";
        exit;
    }

    // Acción de auditoría / bitácora para administradores
    public function audit()
    {
        // Solo accesible para rol de 'Admin'
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'Admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "Acceso denegado. Se requiere el rol de Admin.";
            exit;
        }

        $viewLogs = $this->model->getViewAuditLogs();
        $ackLogs = $this->model->getReadAcknowledgmentLogs();

        require_once 'views/audit.php';
    }
}
?>