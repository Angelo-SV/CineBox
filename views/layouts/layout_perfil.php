<?php
require_once __DIR__ . '/../../config/constants.php';
$titulo = $titulo ?? 'Mi Perfil - CineBox';
$contenido = $contenido ?? '<p>Bienvenido</p>';
$ruta = $_SERVER['REQUEST_URI'];
$nombre = trim($_SESSION['usuario_nombre'] ?? '');
$partes = array_filter(explode(' ', $nombre)); // limpia espacios vacíos
$iniciales = '?';
if (count($partes) >= 2) {
    $iniciales = strtoupper(substr($partes[0], 0, 1) . substr($partes[1], 0, 1));
} elseif (count($partes) === 1) {
    $iniciales = strtoupper(substr($partes[0], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script>window.BASE_PATH = "<?= BASE_PATH ?>";</script>
    <title><?= $titulo ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        main {
            background-color: #3c3e40;
            color: #f8f9fa;
            padding-top: 2rem;
            padding-bottom: 2rem;
            flex: 1;
        }
        .navbar {
            height: 70px;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
            background-color: #1c1f23;
            padding-top: 30px;
            border-right: 1px solid #ffc107;
        }
        .sidebar a {
            padding: 12px 25px;
            display: block;
            color: #bbb;
            text-decoration: none;
            transition: 0.2s;
        }
        .sidebar a:hover {
            background-color: #ffc107;
            color: #000;
        }
        .sidebar a.active {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }
        .main-content {
            margin-left: 260px;
            padding: 100px 40px 40px 40px;
        }
        .avatar-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: #ffc107;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin: 0 auto 15px auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #ffc107;
            }
            .main-content {
                margin-left: 0;
                padding-top: 30px;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
    <a href="<?= BASE_PATH ?>/" class="navbar-brand d-flex align-items-center">
            <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo3.png?alt=media&token=31ad1239-a16d-46cc-a1a2-ffd773a17a6a" 
        width="240" height="60" alt="CineBox Logo">
                <span class="ms-2">Perfil de Usuario</span>
            </a>
        <div>
        <div class="d-flex gap-2">
            <?php if (
                isset($_SESSION['rol']) &&
                $_SESSION['rol'] == ROL_ADMIN): ?>
                <a href="<?= BASE_PATH ?>/admin"
                class="btn btn-outline-light">
                    <i class="bi bi-shield-lock"></i>
                    Admin
                </a>
            <?php endif; ?>
                <a href="<?= BASE_PATH ?>/auth/logout" class="btn btn-warning">
                <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</nav>
<!-- SIDEBAR -->
<aside class="sidebar text-center">
    <div class="avatar-circle">
        <?= $iniciales ?>
    </div>
    <h6 class="text-warning">
    <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>
    </h6>
    <small class="text-muted d-block mb-4">
        <?= htmlspecialchars($_SESSION['correo']) ?>
    </small>
    <a href="<?= BASE_PATH ?>/perfil" class="<?= $ruta === BASE_PATH . '/perfil' ? 'active' : '' ?>">
        <i class="bi bi-person-circle me-2"></i> Mi Perfil
    </a>
    <a href="<?= BASE_PATH ?>/perfil/biblioteca" class="<?= str_contains($ruta, 'biblioteca') ? 'active' : '' ?>">
        <i class="bi bi-collection-play me-2"></i> Mi Biblioteca
    </a>
    <a href="<?= BASE_PATH ?>/perfil/carrito" class="<?= str_contains($ruta, 'carrito') ? 'active' : '' ?>">
        <i class="bi bi-cart me-2"></i> Mi Carrito
    </a>
    <a href="<?= BASE_PATH ?>/perfil/favoritos" class="<?= str_contains($ruta, 'favoritos') ? 'active' : '' ?>">
        <i class="bi bi-heart me-2"></i> Mi Lista
    </a>
    <a href="<?= BASE_PATH ?>/perfil/historial"class="<?= str_contains($ruta, 'historial') ? 'active' : '' ?>">
        <i class="bi bi-clock-history me-2"></i> Historial
    </a>
</aside>
<!-- CONTENIDO -->
<main class="main-content flex-grow-1">
    <div class="container-fluid">
        <?= $contenido ?>
    </div>
</main>
<!-- FOOTER -->
<footer class="bg-dark text-light text-center py-3 border-top border-warning small">
            <a href="index.php" class="me-2 text-decoration-none">
                <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo2.png?alt=media&token=35eb8a1f-fc5f-487d-999d-4daa96160773" 
                    width="30" height="20">
            </a>
            <span>
                &copy; <?= date('Y'); ?> CineBox, S.A. Todos los derechos reservados.
            </span>
        </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>