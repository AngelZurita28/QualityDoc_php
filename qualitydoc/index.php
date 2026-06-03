<?php
require_once 'controllers/DocumentController.php';

$controller = new DocumentController();

// Un enrutador súper sencillo
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'view':
        $controller->view();
        break;
    case 'acknowledge':
        $controller->acknowledge();
        break;
    case 'upload':
        $controller->upload();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
?>