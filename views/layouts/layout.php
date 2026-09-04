<?php
$titulo = $titulo ?? 'CineBox';
$contenido = $contenido ?? '<p>Bienvenido a CineBox</p>';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>window.BASE_PATH = "<?= BASE_PATH ?>";</script>
    <title><?= htmlspecialchars($titulo) ?></title>
    <!-- Favicon propio en SVG (antes usaba un logo alojado en Firebase Storage
         cuyo token de acceso expiró — devolvía 403 y el navegador mostraba el
         ícono genérico). Al ser inline no depende de ningún servicio externo. -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23212529'/%3E%3Ccircle cx='32' cy='32' r='24' fill='%23ffc107'/%3E%3Cpath d='M26 20L46 32L26 44Z' fill='%23212529'/%3E%3C/svg%3E">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/public.css">
  </head>
  <body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <?php include __DIR__ . '/../partials/navbar.php';?>
    <!-- CONTENIDO PRINCIPAL -->
    <main>
      <div class="container">
        <?= $contenido ?>
      </div>
    </main>
    <!-- FOOTER -->
    <?php include __DIR__ . '/../partials/footer.php';?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>