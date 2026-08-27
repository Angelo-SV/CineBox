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
    <link rel="icon" href="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo2.png?alt=media&token=25bcc6e4-890b-4557-95e0-07444f5d81f4" 
    type="image/png">
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