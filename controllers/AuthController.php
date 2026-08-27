<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';

/* la acción viene determinada por la RUTA, no por ?action */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$basePath = trim(BASE_PATH, '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = trim(substr($uri, strlen($basePath)), '/');
}

/* LOGIN */
if ($uri === 'auth/login') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . BASE_PATH . "/login");
        exit;
    }

    $correo = trim($_POST['correo'] ?? '');
    $pass   = $_POST['contrasena'] ?? '';

    if ($correo === '' || $pass === '') {
        header("Location: " . BASE_PATH . "/login?msg=error_campos");
        exit;
    }

    try {
        $user = Usuario::obtenerPorCorreo($correo);

        if (!$user) {
            header("Location: " . BASE_PATH . "/login?msg=correoInvalido");
            exit;
        }

        if (!password_verify($pass, $user['CONTRASENA'])) {
            header("Location: " . BASE_PATH . "/login?msg=contrasena");
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['id']     = $user['ID_USUARIO'];
        $_SESSION['correo'] = $user['CORREO'];
        $_SESSION['rol']    = $user['ROL'];
        $_SESSION['usuario_nombre'] = trim(
            $user['NOMBRE'] . ' ' .
            ($user['APELLIDO_PATERNO'] ?? '') . ' ' .
            ($user['APELLIDO_MATERNO'] ?? '')
        );

        header("Location: " . BASE_PATH . "/");
        exit;

    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: " . BASE_PATH . "/login?msg=errorDB");
        exit;
    }
}

/* LOGOUT */
if ($uri === 'auth/logout') {

    session_unset();
    session_destroy();

    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Pragma: no-cache");

    header("Location: " . BASE_PATH . "/login");
    exit;
}