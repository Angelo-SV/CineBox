<?php
ob_start();
?>
<h2 class="text-warning mb-4">
    <i class="bi bi-person-circle"></i> Mi Perfil
</h2>
<div class="row g-4">
    <!-- INFORMACIÓN BÁSICA -->
    <div class="col-lg-6">
        <div class="card bg-dark border-warning text-light shadow">
            <div class="card-body">
                <h5 class="text-warning mb-3">Información Personal</h5>
                <p>
                <strong>Nombre:</strong>
                    <?= htmlspecialchars(
                        trim(
                            ($resumen['NOMBRE'] ?? '') . ' ' .
                            ($resumen['APELLIDO_PATERNO'] ?? '') . ' ' .
                            ($resumen['APELLIDO_MATERNO'] ?? '')
                        )
                    ) ?>
                </p>
                <p>
                    <strong>Email:</strong>
                    <?= htmlspecialchars($resumen['CORREO'] ?? '') ?>
                </p>
                <p>
                    <strong>Teléfono:</strong>
                    <?= htmlspecialchars($resumen['TELEFONO'] ?? '') ?>
                </p>
                <p>
                    <strong>Fecha de registro:</strong>
                    <?= !empty($resumen['FECHA_REGISTRO']) 
                        ? date('d/m/Y', strtotime($resumen['FECHA_REGISTRO'])) 
                        : '' ?>
                </p>
            </div>
        </div>
    </div>
    <!-- ESTADÍSTICAS -->
    <div class="col-lg-6">
        <div class="card bg-dark border-warning text-light shadow">
            <div class="card-body">
                <h5 class="text-warning mb-3">Resumen</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span><i class="bi bi-camera-reels text-warning me-2"></i>Alquileres activos</span>
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['ALQUILERES_ACTIVOS'] ?? 0 ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>
                        <i class="bi bi-heart text-warning me-2"></i>Mi lista</span>
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_FAVORITOS'] ?? 0 ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><i class="bi bi-clock-history text-warning me-2"></i>Historial total</span>
                    <span class="badge bg-warning text-dark">
                        <?= $resumen['TOTAL_HISTORIAL'] ?? 0 ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_perfil.php';
?>