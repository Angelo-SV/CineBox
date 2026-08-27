<?php
$titulo = $titulo ?? 'CineBox';
$contenido = $contenido ?? '<p>Bienvenido a CineBox</p>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script>window.BASE_PATH = "<?= BASE_PATH ?>";</script>
    <title>Administror - CineBox</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">
</head>
<body>
    <!-- NAVBAR SUPERIOR -->
    <header class="navbar navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a href="<?= BASE_PATH ?>/" class="navbar-brand d-flex align-items-center">
            <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo3.png?alt=media&token=31ad1239-a16d-46cc-a1a2-ffd773a17a6a" 
        width="240" height="60" alt="CineBox Logo">
                <span class="ms-2">Panel de Administración</span>
            </a>
            <div class="d-flex">
            <a href="<?= BASE_PATH ?>/perfil" class="btn btn-outline-light me-2">
                    <i class="bi bi-person-circle"></i>
                    Mi Perfil
                  </a>
                  <a href="<?= BASE_PATH ?>/auth/logout" class="btn btn-warning">
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                  </a>
            </div>
        </div>
    </header>
    <!-- SIDEBAR -->
    <aside class="sidebar shadow">
        <h5 class="text-center text-warning mb-4">Administración</h5>
        <a href="<?= BASE_PATH ?>/admin" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="<?= BASE_PATH ?>/peliculas"><i class="bi bi-film me-2"></i>Películas</a>
        <a href="<?= BASE_PATH ?>/generos"><i class="bi bi-tags me-2"></i>Géneros</a>
        <a href="<?= BASE_PATH ?>/actores"><i class="bi bi-people-fill me-2"></i>Actores</a>
        <a href="<?= BASE_PATH ?>/directores"><i class="bi bi-person-video3 me-2"></i>Directores</a>
        <a href="<?= BASE_PATH ?>/estudios"><i class="bi bi-building me-2"></i>Estudios</a>
        <a href="<?= BASE_PATH ?>/proveedores"><i class="bi bi-truck me-2"></i>Proveedores</a>
        <hr class="text-secondary">
        <a href="<?= BASE_PATH ?>/usuarios"><i class="bi bi-person-lines-fill me-2"></i>Usuarios</a>
    </aside>
    <!-- CONTENIDO PRINCIPAL -->
    <div class="d-flex flex-column min-vh-100">
        <!-- MAIN -->
        <main class="main-content flex-grow-1">
            <div class="container">
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
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
</body>
</html>