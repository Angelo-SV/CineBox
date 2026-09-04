<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Transaccion.php';
require_once __DIR__ . '/../models/Perfil.php';
require_once __DIR__ . '/../middleware/admin.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$basePath = trim(BASE_PATH, '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = trim(substr($uri, strlen($basePath)), '/');
}

/* ===============================
   LISTADO
   =============================== */
   if ($uri === 'usuarios') {
    requireAdmin();
    // seguridad mínima
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
        header("Location: " . BASE_PATH . "/");
        exit;
    }

    $usuarios = Usuario::listar(); // 👈 viene del modelo
    $alquileresActivosPorUsuario = Transaccion::contarActivosPorUsuario();
    require __DIR__ . '/../views/admin/usuarios.php';
    exit;
}

/* ===============================
   ALQUILERES ACTIVOS DE UN USUARIO (AJAX, para el modal)
   =============================== */
if ($uri === 'usuarios/alquileres') {
    requireAdmin();
    header('Content-Type: application/json');

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['ok' => false]);
        exit;
    }

    try {
        $peliculas = Perfil::obtenerBiblioteca($id);
        echo json_encode(['ok' => true, 'peliculas' => $peliculas]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['ok' => false]);
    }
    exit;
}

/* ===============================
   INSERTAR USUARIO
   =============================== */
   if ($uri === 'usuarios/crear') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . BASE_PATH . "/");
        exit;
    }

    $origen = $_POST['origen'] ?? 'admin';

    if ($origen === 'admin') {
        requireAdmin();
    }

    try {
        $nombre          = trim($_POST['nombre'] ?? '');
        $apellidoPaterno = trim($_POST['apellidoPaterno'] ?? '');
        $apellidoMaterno = trim($_POST['apellidoMaterno'] ?? '');
        $correo          = trim($_POST['correo'] ?? '');
        $telefono        = trim($_POST['telefono'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmar       = $_POST['confirmarContrasena'] ?? '';

        validarDatosUsuario([
            'nombre'           => $nombre,
            'apellidoPaterno'  => $apellidoPaterno,
            'apellidoMaterno'  => $apellidoMaterno,
            'correo'           => $correo,
            'telefono'         => $telefono,
            'password'         => $password,
            'confirmar'        => $confirmar
        ], $origen);

        $data = [
            'nombre'          => $nombre,
            'apellidoPaterno' => $apellidoPaterno,
            'apellidoMaterno' => $apellidoMaterno,
            'correo'          => $correo,
            'telefono'        => $telefono,
            'rol'             => ($origen === 'admin' && isset($_POST['admin'])) ? 1 : 2,
            'password'        => password_hash($password, PASSWORD_DEFAULT)
        ];

        Usuario::insertar($data);

        /* 🔀 Redirect OK */
        if ($origen === 'registro') {
            header("Location: " . BASE_PATH . "/login?msg=registro_ok");
        } else {
            header("Location: " . BASE_PATH . "/usuarios?msg=insertado");
        }
        exit;

    } catch (Exception $e) {

        /* 🔀 Redirect ERROR */
        if ($origen === 'registro') {
            $msg = $e->getMessage();
            header("Location: " . BASE_PATH . "/registro?msg={$msg}");
        } else {
            header("Location: " . BASE_PATH . "/usuarios?msg=error_campos");
        }
        exit;
    }
}

/* ===============================
   ACTUALIZAR
   =============================== */
if ($uri === 'usuarios/actualizar') {
    requireAdmin();
    try {
        $id = intval($_POST['id'] ?? 0);

        if ($id === $_SESSION['id'] && !isset($_POST['admin'])) {
            header("Location: " . BASE_PATH . "/usuarios?msg=error_rol");
            exit;
        }

        $password = $_POST['password'] !== ''
            ? password_hash($_POST['password'], PASSWORD_DEFAULT)
            : $_POST['password_actual'];

        validarDatosUsuario([
            'nombre'           => $_POST['nombre'] ?? '',
            'apellidoPaterno'  => $_POST['apellidoPaterno'] ?? '',
            'apellidoMaterno'  => $_POST['apellidoMaterno'] ?? '',
            'correo'           => $_POST['correo'] ?? '',
            'telefono'         => $_POST['telefono'] ?? '',
            'password'         => $_POST['password'] ?? ''
        ], 'admin', true);
        
        Usuario::actualizar([
            'id'               => $id,
            'nombre'           => trim($_POST['nombre']),
            'apellidoPaterno'  => trim($_POST['apellidoPaterno']),
            'apellidoMaterno'  => trim($_POST['apellidoMaterno']),
            'correo'           => trim($_POST['correo']),
            'telefono'         => trim($_POST['telefono']),
            'rol'              => isset($_POST['admin']) ? 1 : 2,
            'password'         => $password
        ]);

        header("Location: " . BASE_PATH . "/usuarios?msg=actualizado");
        exit;

    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: " . BASE_PATH . "/usuarios?msg=error_bd");
        exit;
    }
}

/* ===============================
   ELIMINAR
   =============================== */
if ($uri === 'usuarios/eliminar') {
    requireAdmin();
    $id = intval($_POST['id'] ?? 0);

    if ($id === $_SESSION['id']) {
        header("Location: " . BASE_PATH . "/usuarios?msg=error_autodelete");
        exit;
    }

    try {
        Usuario::eliminar($id);
        header("Location: " . BASE_PATH . "/usuarios?msg=eliminado");
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: " . BASE_PATH . "/usuarios?msg=error_bd");
        exit;
    }
}

function validarDatosUsuario(array $data, string $origen = 'admin', bool $esUpdate = false): void
{
    if (
        trim($data['nombre']) === '' ||
        trim($data['apellidoPaterno']) === '' ||
        trim($data['apellidoMaterno']) === '' ||
        trim($data['correo']) === '' ||
        trim($data['telefono']) === ''
    ) {
        throw new Exception('error_campos');
    }

    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('error_correo');
    }

    if (!preg_match('/^[0-9]{8,15}$/', $data['telefono'])) {
        throw new Exception('error_telefono');
    }

    // Validaciones solo si NO es update o si se cambia password
    if (!$esUpdate || $data['password'] !== '') {

        if (strlen($data['password']) < 8) {
            throw new Exception('error_password');
        }

        if ($origen === 'registro' && $data['password'] !== ($data['confirmar'] ?? '')) {
            throw new Exception('error_password');
        }
    }
}