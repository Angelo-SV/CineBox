<?php
$titulo = 'Panel Administrador - CineBox';
ob_start();
?>

<h2 class="text-warning mb-4">
    <i class="bi bi-speedometer2"></i> Panel Administrativo
</h2>

<div class="row g-4">
    <!-- 🎬 CONTENIDO -->
    <div class="col-lg-4">
        <div class="card bg-dark border-warning text-light shadow h-100">
            <div class="card-body">
                <h5 class="text-warning mb-3">🎬 Contenido</h5>

                <p>Películas: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_PELICULAS'] ?>
                    </span>
                </p>

                <p>Géneros: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_GENEROS'] ?>
                    </span>
                </p>

                <p>Estudios: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_ESTUDIOS'] ?>
                    </span>
                </p>

                <p>Proveedores: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_PROVEEDORES'] ?>
                    </span>
                </p>

            </div>
        </div>
    </div>

    <!-- 🎭 TALENTO -->
    <div class="col-lg-4">
        <div class="card bg-dark border-warning text-light shadow h-100">
            <div class="card-body">
                <h5 class="text-warning mb-3">🎭 Talento</h5>

                <p>Actores: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_ACTORES'] ?>
                    </span>
                </p>

                <p>Directores: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_DIRECTORES'] ?>
                    </span>
                </p>

            </div>
        </div>
    </div>

    <!-- 👥 USUARIOS -->
    <div class="col-lg-4">
        <div class="card bg-dark border-warning text-light shadow h-100">
            <div class="card-body">
                <h5 class="text-warning mb-3">👥 Usuarios</h5>

                <p>Total usuarios: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_USUARIOS'] ?>
                    </span>
                </p>

            </div>
        </div>
    </div>

    <!-- 🎟️ TRANSACCIONES -->
    <div class="col-lg-6">
        <div class="card bg-dark border-warning text-light shadow h-100">
            <div class="card-body">
                <h5 class="text-warning mb-3">🎟️ Alquileres</h5>

                <p>Activos: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['ALQUILERES_ACTIVOS'] ?>
                    </span>
                </p>

                <p>Historial total: 
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_HISTORIAL'] ?>
                    </span>
                </p>

            </div>
        </div>
    </div>

    <!-- 💰 INGRESOS -->
    <div class="col-lg-6">
        <div class="card bg-dark border-warning text-light shadow h-100">
            <div class="card-body">
                <h5 class="text-warning mb-3">💰 Ingresos</h5>

                <h3 class="text-warning">
                    ₡<?= number_format($resumen['INGRESOS_TOTALES'],2) ?>
                </h3>

                <p class="text-muted">
                    Total generado por alquileres
                </p>

            </div>
        </div>
    </div>

</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_admin.php';
?>