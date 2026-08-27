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