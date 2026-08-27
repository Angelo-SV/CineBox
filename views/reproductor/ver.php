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
    <style>
        body{
            margin:0;
            background:#000;
            color:white;
            overflow:hidden;
        }
        /* barra superior */
        .player-topbar{
            position:absolute;
            top:0;
            width:100%;
            padding:15px 25px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            z-index:5;
            background:linear-gradient(to bottom, rgba(0,0,0,0.8), transparent);
        }
        .player-title{
            font-size:18px;
            font-weight:bold;
        }
        /* botón volver */
        .btn-back{
            background:rgba(0,0,0,0.6);
            border:1px solid #ffc107;
            color:#ffc107;
        }
        /* contenedor video */
        .video-container{
            width:100vw;
            height:100vh;
        }
        /* video */
        video{
            width:100%;
            height:100%;
            object-fit:cover;
        }
    </style>
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