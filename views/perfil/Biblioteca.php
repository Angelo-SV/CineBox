<?php ob_start(); ?>
<h2 class="text-warning mb-4">
    <i class="bi bi-collection-play"></i> Mi Biblioteca
</h2>
<?php if (empty($peliculas)): ?>
<div class="card bg-dark border-warning text-light shadow">
    <div class="card-body text-center">
        <h5 class="mb-3">No tienes películas en tu biblioteca</h5>
            <p>Visita nuestro catálogo para empezar a disfrutar contenido.</p>
        <a href="<?= BASE_PATH ?>/" class="btn btn-warning">
            <i class="bi bi-film"></i> Ir al catálogo
        </a>
    </div>
</div>
<?php else: ?>
<div class="row g-4">
            <?php foreach ($peliculas as $p): ?>
            <div class="col-md-3">
                <div class="card bg-dark border-warning text-light shadow h-100">
                    <img src="<?= htmlspecialchars($p['IMAGEN']) ?>" 
                        class="card-img-top"
                        style="height:320px;object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-warning">
                        <?= htmlspecialchars($p['TITULO']) ?>
                    </h6>
                    <?php
                    $h = intval($p['HORAS_RESTANTES']);
                    $dias = floor($h / 24);
                    $horas = $h % 24;
                    $color = ($h <= 24) ? "text-danger" : "text-warning";
                    ?>
                    <p class="small text-muted mb-1">
                        Expira: <?= date('d/m/Y', strtotime($p['FECHA_EXPIRACION'])) ?>
                    </p>
                    <p class="small <?= $color ?> mb-2">
                    ⏳ Disponible por:
                    <strong><?= $dias ?>d <?= $horas ?>h</strong>
                    </p>
                <div class="mt-auto d-grid gap-2">
                    <a href="<?= BASE_PATH ?>/pelicula?id=<?= $p['ID_PELICULA'] ?>"
                    class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-info-circle"></i> Detalle
                    </a>
                    <a href="<?= BASE_PATH ?>/ver/<?= $p['ID_TRANSACCION'] ?>"
                    class="btn btn-warning btn-sm">
                    <i class="bi bi-play-fill"></i> Ver
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_perfil.php';
?>