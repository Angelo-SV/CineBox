<?php
$titulo = 'Historial de alquileres';
ob_start();
?>
<div class="container py-5 text-light">
    <h2 class="text-warning mb-4">
        <i class="bi bi-clock-history"></i> Historial de alquileres
    </h2>
    <?php if (empty($items)): ?>
        <div class="alert alert-warning bg-dark border-warning text-light">
            Aún no has alquilado películas.
        </div>
        <a href="<?= BASE_PATH ?>/catalogo" class="btn btn-warning">
            <i class="bi bi-film"></i> Ir al catálogo
        </a>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead class="text-warning text-center">
                <tr>
                    <th>Poster</th>
                    <th>Título</th>
                    <th>Último alquiler</th>
                    <th>Veces alquilada</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($items as $p): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($p['IMAGEN']) ?>" style="width:60px;height:90px;object-fit:cover;border-radius:6px;">
                    </td>
                    <td>
                        <?= htmlspecialchars($p['TITULO']) ?>
                    </td>
                    <td>
                        <?= date('d/m/Y', strtotime($p['ULTIMA_FECHA'])) ?>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark"> <?= $p['VECES_ALQUILADA'] ?> veces
                    </span>
                    </td>
                    <td>
                        ₡<?= number_format($p['PRECIO'],2) ?>
                    </td>
                    <td>
                        <?php if ($p['ES_ALQUILADA'] == 1): ?>
                            <a href="<?= BASE_PATH ?>/ver/<?= $p['ID_TRANSACCION_ACTIVA'] ?>"
                            class="btn btn-warning btn-sm">
                            <i class="bi bi-play-fill me-1"></i>Ver ahora
                            </a>
                            <?php else: ?>

                            <button class="btn btn-outline-warning btn-sm"
                                onclick="alquilarPelicula(<?= $p['ID_PELICULA'] ?>)">
                                <i class="bi bi-ticket-perforated me-2"></i>Volver a alquilar
                            </button>
                        <?php endif; ?>
                        </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<!-- ===============================
     MODAL ALQUILER
================================ -->
<div class="modal fade" id="modalAlquiler" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-warning">

      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning">
            Confirmar alquiler
        </h5>
        <button class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div id="alquilerLoader" class="text-center py-5">
            <div class="spinner-border text-warning"></div>
            <p class="mt-3">Cargando información...</p>
        </div>

        <div id="alquilerContenido" class="d-none">

            <div class="row">
                <div class="col-md-4 text-center">
                    <img id="alqImagen"
                         class="img-fluid rounded shadow"
                         style="max-height:220px; object-fit:cover;">
                </div>

                <div class="col-md-8">

                    <h4 id="alqTitulo"
                        class="text-warning"></h4>

                    <p><strong>Género:</strong>
                       <span id="alqGenero"></span>
                    </p>

                    <p><strong>Director:</strong>
                       <span id="alqDirector"></span>
                    </p>

                    <p><strong>Precio:</strong>
                       <span id="alqPrecio"></span>
                    </p>

                </div>
            </div>

            <hr class="border-warning">

            <label class="form-label">
                Método de pago
            </label>

            <select id="alqMetodo"
                    class="form-select bg-dark text-light border-warning">

                <option value="1">Tarjeta</option>
                <option value="2">PayPal</option>

            </select>

            <div id="alqTarjetaBox" class="mt-3">

                <label class="form-label">
                    Número de Tarjeta
                </label>

                <input class="form-control mb-2 bg-dark text-light border-warning"
                       placeholder="Número tarjeta">

                <label class="form-label">
                    Fecha de Vencimiento
                </label>

                <input class="form-control mb-2 bg-dark text-light border-warning"
                       placeholder="Vencimiento MM/AA">

                <label class="form-label">
                    CVV
                </label>

                <input class="form-control bg-dark text-light border-warning"
                       placeholder="CVV">

            </div>

        </div>

      </div>

      <div class="modal-footer border-warning">

        <button class="btn btn-secondary"
                data-bs-dismiss="modal">
            Cancelar
        </button>

        <button class="btn btn-warning"
                id="btnConfirmarAlquiler">
            Confirmar alquiler
        </button>

      </div>

    </div>
  </div>
</div>

<!-- ===============================
     MODAL EXITO
================================ -->
<div class="modal fade" id="modalAlquilerExito" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-warning">

      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning">
            Alquiler exitoso
        </h5>

        <button class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <div class="fs-1 mb-3">🎬</div>

        <p>
          Ya puedes disfrutar de esta película
          por los próximos <strong>7 días</strong>.
        </p>

      </div>

      <div class="modal-footer border-warning justify-content-center">

        <a href="<?= BASE_PATH ?>/perfil/biblioteca"
           class="btn btn-warning">
           Ir a mi biblioteca
        </a>

        <button class="btn btn-outline-light"
                data-bs-dismiss="modal">
            Cerrar
        </button>

      </div>

    </div>
  </div>
</div>

<!-- ===============================
     MODAL ERROR
================================ -->
<div class="modal fade" id="modalAlquilerError" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-danger">

      <div class="modal-header border-danger">
        <h5 class="modal-title text-danger">
            Error en el alquiler
        </h5>

        <button class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <div class="fs-1 mb-3">⚠️</div>

        <p id="errorAlquilerTexto">
            Ocurrió un error
        </p>

      </div>

      <div class="modal-footer border-danger justify-content-center">

        <button class="btn btn-outline-light"
                data-bs-dismiss="modal">
            Cerrar
        </button>

      </div>

    </div>
  </div>
</div>
<script src="<?= BASE_PATH ?>/js/alquiler-simple.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_perfil.php';
?>