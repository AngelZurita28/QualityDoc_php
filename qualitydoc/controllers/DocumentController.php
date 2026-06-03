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
}
?>