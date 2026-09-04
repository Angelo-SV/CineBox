<?php
$titulo = "Reproductor - CineBox";
/* VIDEO DEMO*/
$videoURL = VIDEO_DEFAULT;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23212529'/%3E%3Ccircle cx='32' cy='32' r='24' fill='%23ffc107'/%3E%3Cpath d='M26 20L46 32L26 44Z' fill='%23212529'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/reproductor.css">
</head>
<body>
    <!-- TOP BAR -->
    <div class="player-topbar">
        <div class="player-title">
            <?= htmlspecialchars($pelicula['TITULO'] ?? 'Película') ?>
        </div>
        <button onclick="volverPagina()" class="btn btn-back">
            ← Volver
        </button>
    </div>
    <!-- VIDEO PLAYER -->
    <div class="video-container">
        <video controls autoplay>
            <source src="<?= $videoURL ?>" type="video/mp4">
            Tu navegador no soporta video.
        </video>
    </div>
</body>
</html>
<script>
    function volverPagina(){
        if(document.referrer !== ""){
            history.back();
        }else{
            window.location.href="<?= BASE_PATH ?>/";
        }
    }
</script>