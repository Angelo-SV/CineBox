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
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23212529'/%3E%3Ccircle cx='32' cy='32' r='24' fill='%23ffc107'/%3E%3Cpath d='M26 20L46 32L26 44Z' fill='%23212529'/%3E%3C/svg%3E">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/perfil.css">
</head>
<body class="d-flex flex-column min-vh-100">
<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
    <span class="navbar-brand d-flex align-items-center mb-0">
            <a href="<?= BASE_PATH ?>/" class="d-flex align-items-center">
            <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo3.png?alt=media&token=31ad1239-a16d-46cc-a1a2-ffd773a17a6a"
        width="240" height="60" alt="CineBox Logo">
            </a>
                <span class="ms-2">Perfil de Usuario</span>
            </span>
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
<script>
/* Ver nota en layout_admin.php: fuerza recarga al volver desde bfcache
   tras un logout para que la sesión (ya destruida) se revalide. */
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
</body>
</html>