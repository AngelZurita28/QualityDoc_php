<?php

class AuthController
{
    private $apiUri;

    public function __construct()
    {
        $this->apiUri = getenv('API_LOGIN_URI') ?: 'http://localhost:5000';
    }

    public function login()
    {
        // Si ya está logueado, redirigir al index
        if (isset($_SESSION['user'])) {
            header("Location: index.php");
            exit;
        }

        $error = null;
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
            $password = isset($_POST['Password']) ? $_POST['Password'] : '';

            if (empty($email) || empty($password)) {
                $error = "El correo electrónico y la contraseña son obligatorios.";
            } else {
                // Preparar petición
                $url = rtrim($this->apiUri, '/') . '/api/auth/login';
                $data = json_encode([
                    'Email' => $email,
                    'Password' => $password
                ]);

                $response = null;
                $httpCode = 0;

                // Enviar petición usando curl con fallback a file_get_contents
                if (function_exists('curl_init')) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data)
                    ]);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                    
                    // Desactivar verificación SSL para desarrollo local
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    
                    if (curl_errno($ch)) {
                        $error = "Error de conexión: " . curl_error($ch);
                    }
                    curl_close($ch);
                } else {
                    $options = [
                        'http' => [
                            'method'  => 'POST',
                            'header'  => "Content-Type: application/json\r\n" .
                                         "Content-Length: " . strlen($data) . "\r\n",
                            'content' => $data,
                            'ignore_errors' => true,
                            'timeout' => 8
                        ],
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false
                        ]
                    ];
                    $context  = stream_context_create($options);
                    $response = @file_get_contents($url, false, $context);
                    
                    $httpCode = 500;
                    if (isset($http_response_header) && is_array($http_response_header)) {
                        foreach ($http_response_header as $header) {
                            if (preg_match('/^HTTP\/\d+\.\d+\s+(\d+)/', $header, $matches)) {
                                $httpCode = (int)$matches[1];
                                break;
                            }
                        }
                    }
                    if ($response === false) {
                        $error = "No se pudo conectar con el servidor de autenticación.";
                    }
                }

                if ($error === null) {
                    $result = json_decode($response, true);

                    if ($httpCode === 200 && is_array($result)) {
                        // Éxito. Guardar en la sesión de PHP
                        $_SESSION['user'] = [
                            'id' => $result['id'] ?? null,
                            'nombre' => $result['nombre'] ?? 'Usuario',
                            'usuario' => $result['usuario'] ?? $email,
                            'empresa' => $result['empresa'] ?? 'Sin Empresa',
                            'rol' => $result['rol'] ?? 'Sin Rol',
                            'departamento' => $result['departamento'] ?? 'No Asignado'
                        ];

                        header("Location: index.php");
                        exit;
                    } elseif ($httpCode === 400) {
                        $error = isset($result['message']) ? $result['message'] : "Email y contraseña son requeridos.";
                    } elseif ($httpCode === 401) {
                        $error = isset($result['message']) ? $result['message'] : "Credenciales incorrectas o usuario inactivo.";
                    } else {
                        $error = "Error de comunicación con el servidor de autenticación (" . $httpCode . ").";
                    }
                }
            }
        }

        require_once 'views/login.php';
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
