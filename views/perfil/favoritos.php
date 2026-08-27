<?php
$titulo = 'Mis Favoritos - CineBox';
ob_start();
?>
<div class="container py-5 text-light">
  <h2 class="text-warning mb-4">
    <i class="bi bi-heart-fill me-2"></i>Mi Lista
  </h2>
  <?php if (empty($items)): ?>
    <div class="card bg-dark border-warning text-light shadow">
      <div class="card-body text-center">
          <h5 class="mb-3">No tienes películas en tu lista</h5>
              <p>Visita nuestro catálogo para empezar a disfrutar contenido.</p>
          <a href="<?= BASE_PATH ?>/" class="btn btn-warning">
              <i class="bi bi-film"></i> Ir al catálogo
          </a>
      </div>
  </div>
  <?php else: ?>
<div class="row g-4">
  <?php foreach ($items as $p):
    $img = $p['IMAGEN'] ?: IMG_DEFAULT;
  ?>
    <div class="col-md-3">
      <div class="card bg-dark border-warning text-light shadow h-100">
        <img src="<?= htmlspecialchars($img) ?>"
          class="card-img-top"
          style="height:320px;object-fit:cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title text-warning">
            <?= htmlspecialchars($p['TITULO']) ?>
          </h6>
          <p class="small text-muted mb-1">
            <?= htmlspecialchars($p['GENERO']) ?>
          </p>
          <p class="small text-muted mb-2">
            <?= htmlspecialchars($p['ESTUDIO']) ?>
          </p>
          <p class="text-warning fw-bold mb-3">
            ₡<?= number_format($p['PRECIO'],2) ?>
          </p>
          <div class="mt-auto d-grid gap-2">
            <a href="<?= BASE_PATH ?>/pelicula?id=<?= $p['ID_PELICULA'] ?>" class="btn btn-outline-info btn-sm">
            <i class="bi bi-info-circle me-1"></i>
              Detalle
            </a>
            <?php if ($p['ES_ALQUILADA'] == 1): ?>
              <a href="<?= BASE_PATH ?>/ver/<?= $p['ID_TRANSACCION'] ?>" class="btn btn-warning btn-sm">
              <i class="bi bi-play-fill"></i>Ver
              </a>
            <?php else: ?>
              <button class="btn <?= $p['EN_CARRITO'] ? 'btn-warning' : 'btn-outline-warning' ?> btn-sm"
                    onclick="toggleCarritoFavoritos(<?= $p['ID_PELICULA'] ?>, this)">
                <i class="bi <?= $p['EN_CARRITO'] ? 'bi-cart-fill' : 'bi-cart' ?>"></i>
                <?= $p['EN_CARRITO'] ? 'En carrito' : 'Agregar al carrito' ?>
            </button>
            <button class="btn btn-warning btn-sm" onclick="alquilarPelicula(<?= $p['ID_PELICULA'] ?>)">
            <i class="bi bi-ticket-perforated-fill me-1"></i>
              Alquilar
            </button>
            <?php endif; ?>
            <button class="btn btn-outline-danger btn-sm" onclick="toggleFavoritoLista(<?= $p['ID_PELICULA'] ?>, this)">
            <i class="bi bi-x-lg me-1"></i>
              Quitar de favoritos 
            </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<!-- ===============================
     MODAL CARRITO MENSAJE
================================ -->
<div class="modal fade" id="modalCarritoMsg" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-warning">
      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning">
            Carrito
        </h5>
        <button class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="fs-5 mb-3"
             id="carritoMensajeTexto">
        </div>
      </div>
      <div class="modal-footer border-warning justify-content-center">
        <a href="<?= BASE_PATH ?>/perfil/carrito"
           id="btnIrCarrito"
           class="btn btn-warning">
           <i class="bi bi-cart-fill me-2"></i>Ir al carrito
        </a>
        <button class="btn btn-outline-warning"
                data-bs-dismiss="modal">
            Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</div>
<!-- ===============================
     MODAL FAVORITOS
================================ -->
<div class="modal fade" id="modalFav" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-warning">
      <div class="modal-body text-center">
        <span id="favMensaje"></span>
      </div>
    </div>
  </div>
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
<!-- ==============================
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
<script src="<?= BASE_PATH ?>/js/favoritos.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_perfil.php';
?>