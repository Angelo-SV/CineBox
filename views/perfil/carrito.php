<?php
$titulo = 'Mi Carrito - CineBox';
ob_start();
?>
<div class="container py-5 text-light">
    <h2 class="text-warning mb-4">
    <i class="bi bi-cart-fill me-2"></i>Mi Carrito
    </h2>
    <?php if (empty($items)): ?>
      <div class="card bg-dark border-warning text-light shadow">
        <div class="card-body text-center">
            <h5 class="mb-3">No tienes películas en tu carrito</h5>
                <p>Visita nuestro catálogo para empezar a disfrutar contenido.</p>
            <a href="/Videoteca_ElResplandor/" class="btn btn-warning">
                <i class="bi bi-film"></i> Ir al catálogo
            </a>
        </div>
    </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead class="text-warning text-center">
                    <tr>
                        <th>Poster</th>
                        <th>Título</th>
                        <th>Género</th>
                        <th>Estudio</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                <?php foreach ($items as $p):
                    $img = $p['IMAGEN'] ?: IMG_DEFAULT; 
                ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($img) ?>"
                            style="width:60px; height:90px; object-fit:cover; border-radius:6px;">
                    </td>
                    <td><?= htmlspecialchars($p['TITULO']) ?></td>
                    <td><?= htmlspecialchars($p['GENERO']) ?></td>
                    <td><?= htmlspecialchars($p['ESTUDIO']) ?></td>
                    <td>₡<?= number_format($p['PRECIO'],2) ?></td>
                    <td>
                        <a href="/Videoteca_ElResplandor/pelicula?id=<?= $p['ID_PELICULA'] ?>"
                        class="btn btn-sm btn-outline-info">
                        <i class="bi bi-info-circle"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger ms-2"
                                onclick="eliminarItem(<?= $p['ID_PELICULA'] ?>)">
                                <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <hr class="border-warning">
        <div class="row align-items-center mt-4">
            <!-- IZQUIERDA -->
            <div class="col-md-6 d-flex align-items-center gap-4">
                <h4 class="mb-0">
                    Total:
                    <span class="text-warning">
                        ₡<?= number_format($total,2) ?>
                    </span>
                </h4>
                <a href="/Videoteca_ElResplandor/"
                class="btn btn-outline-warning btn-sm">
                <i class="bi bi-camera-reels me-1"></i>
                Agregar más películas
                </a>
            </div>
            <!-- DERECHA -->
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <label class="form-label me-2 mb-0">Método de pago</label>
                <select id="metodoPago"
                        class="form-select bg-dark text-light border-warning w-auto d-inline-block">
                    <option value="1">Tarjeta</option>
                    <option value="2">PayPal</option>
                </select>
                <button class="btn btn-warning ms-3"
                        id="btnAlquilarTodo"
                        onclick="alquilarTodo()">
                        <i class="bi bi-ticket-perforated-fill me-1"></i>
                    Alquilar todo
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- ===============================
     MODAL TARJETA
================================ -->
<div class="modal fade" id="modalTarjeta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-warning">
      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning">Datos de la tarjeta</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Número de tarjeta</label>
          <input type="text" id="cardNumero"
                 class="form-control bg-dark text-light border-warning"
                 placeholder="0000 0000 0000 0000">
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre en la tarjeta</label>
          <input type="text" id="cardNombre"
                 class="form-control bg-dark text-light border-warning">
        </div>
        <div class="row">
          <div class="col">
            <label class="form-label">Expiración</label>
            <input type="text" id="cardExp"
                   class="form-control bg-dark text-light border-warning"
                   placeholder="MM/AA">
          </div>
          <div class="col">
            <label class="form-label">CVV</label>
            <input type="text" id="cardCvv"
                   class="form-control bg-dark text-light border-warning"
                   placeholder="123">
          </div>
        </div>
      </div>
      <div class="modal-footer border-warning justify-content-center">
        <button class="btn btn-warning"
                id="btnConfirmarTarjeta"
                onclick="confirmarPagoTarjeta()">
          Confirmar pago
        </button>
        <button class="btn btn-outline-light"
                data-bs-dismiss="modal">
          Cancelar
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
        <h5 class="modal-title text-warning">Alquiler exitoso</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="fs-1 mb-3">🎬</div>
        <p>
          Ya puedes disfrutar de esta película por los próximos
          <strong>7 días</strong>.
        </p>
        <p class="text-muted">
          Puedes consultar la fecha de expiración desde tu biblioteca.
        </p>
      </div>
      <div class="modal-footer border-warning justify-content-center">
        <a href="/Videoteca_ElResplandor/perfil/biblioteca"
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
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
<script src="/Videoteca_ElResplandor/js/carrito.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_perfil.php';
?>