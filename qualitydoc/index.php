<?php
session_start();

require_once 'controllers/DocumentController.php';
require_once 'controllers/AuthController.php';

$documentController = new DocumentController();
$authController = new AuthController();

// Un enrutador súper sencillo
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Excluir acciones públicas (upload de la API de C# y la pantalla de login/autenticación)
$publicActions = ['login', 'upload'];

if (!isset($_SESSION['user']) && !in_array($action, $publicActions)) {
    header("Location: index.php?action=login");
    exit;
}

// Redirigir al inicio si ya está logueado e intenta ir a la pantalla de login
if (isset($_SESSION['user']) && $action === 'login') {
    header("Location: index.php");
    exit;
}

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'view':
        $documentController->view();
        break;
    case 'serve':
        $documentController->serve();
        break;
    case 'acknowledge':
        $documentController->acknowledge();
        break;
    case 'upload':
        $documentController->upload();
        break;
    case 'serve_file':
        $documentController->serveFile();
        break;
    case 'index':
    default:
        $documentController->index();
        break;
}
?>