<?php
$titulo = 'Detalle Película';
ob_start();
?>
<div class="container my-5">
    <div class="row g-4">
        <!-- POSTER -->
        <div class="col-md-4">
            <div class="card bg-dark border-warning shadow">
                <img src="<?= $pelicula['IMAGEN'] ?: '/img/no-image.jpg' ?>"
                     class="img-fluid rounded">
            </div>
        </div>
        <!-- INFO -->
        <div class="col-md-8">
        <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="text-warning mb-0">
                <?= htmlspecialchars($pelicula['TITULO']) ?>
            </h2>
            <span class="badge border border-warning text-warning fs-6 px-3 py-2">
                ⭐ <?= number_format($promedio,1) ?>/10
            </span>
        </div>
            <p class="text-light">
                <?= nl2br(htmlspecialchars($pelicula['SINOPSIS'])) ?>
            </p>
            <div class="row text-light mt-4">
                <div class="col-md-6">
                    <strong>Género:</strong><br>
                    <?= htmlspecialchars($pelicula['GENERO']) ?>
                </div>
                <div class="col-md-6">
                    <strong>Estudio:</strong><br>
                    <?= htmlspecialchars($pelicula['ESTUDIO']) ?>
                </div>
                <div class="col-md-6 mt-3">
                    <strong>Director:</strong><br>
                    <?= $pelicula['DIRECTOR']
                        ? htmlspecialchars($pelicula['DIRECTOR'])
                        : '—' ?>
                </div>
                <div class="col-md-6 mt-3">
                    <strong>Duración:</strong><br>
                    <?= intval($pelicula['DURACION']) ?> min
                </div>
                <div class="col-md-6 mt-3">
                    <strong>Estreno:</strong><br>
                    <?= date('d/m/Y', strtotime($pelicula['FECHA_ESTRENO'])) ?>
                </div>
                <div class="col-md-6 mt-3">
                    <strong>Precio:</strong><br>
                    <span class="text-warning fw-bold">
                        ₡<?= number_format($pelicula['PRECIO'], 2) ?>
                    </span>
                </div>
                <?php if (!empty($pelicula['HORAS_RESTANTES'])): 
                    $h = intval($pelicula['HORAS_RESTANTES']);
                    $dias = floor($h / 24);
                    $horas = $h % 24;
                ?>
                <div class="alert border border-warning text-warning mt-4">
                    ⏳ Disponible por:
                    <strong><?= $dias ?> días <?= $horas ?> horas</strong><br>
                    Puedes verla hasta su expiración.
                </div>
                <?php endif; ?>
            </div>
            <!-- ===== CAST ARRIBA ===== -->
            <div class="mt-4">
                <hr class="border-warning">
                <h5 class="text-warning mb-3">🎭 Reparto</h5>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php if (!empty($cast)): ?>
                        <?php foreach ($cast as $actor): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-dark border-warning h-100 shadow-sm">
                                <div class="card-body text-light p-2">
                                    <strong>
                                        <?= htmlspecialchars($actor['NOMBRE']) ?>
                                    </strong>
                                    <?php if ($actor['ES_PROTAGONISTA'] == 1): ?>
                                        <span class="badge bg-warning text-dark ms-1">
                                            Protagonista
                                        </span>
                                    <?php endif; ?>
                                    <div class="small text-light mt-2">
                                        <?= htmlspecialchars($actor['ROL']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted">
                            No hay reparto registrado.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
   <!-- BOTONES -->
   <?php
    $favorito = !empty($pelicula['ES_FAVORITO']) && $pelicula['ES_FAVORITO'] == 1;
    $alquilada = !empty($pelicula['ES_ALQUILADA']) && $pelicula['ES_ALQUILADA'] == 1;
    $enCarrito = !empty($pelicula['EN_CARRITO']) && $pelicula['EN_CARRITO'] == 1;
    ?>
    <div class="mt-4 d-flex justify-content-end flex-wrap gap-2">
        <button onclick="volverPagina()"
        class="btn btn-outline-warning">
            ← Volver
        </button>
        <?php if (!empty($_SESSION['id'])): ?>
            <?php if ($alquilada && !empty($pelicula['ID_TRANSACCION'])): ?>
                <a href="/Videoteca_ElResplandor/ver/<?= $pelicula['ID_TRANSACCION'] ?>"
                class="btn btn-outline-warning">
                <i class="bi bi-play-fill"></i>Ver película
                </a>
            <?php else: ?>
                <!-- ALQUILAR -->
                <button class="btn btn-warning"
                        onclick="abrirModalAlquiler(<?= $pelicula['ID_PELICULA'] ?>)">
                        <i class="bi bi-ticket-perforated-fill"></i> Alquilar
                </button>
                <!-- CARRITO -->
                <button id="btnCarritoDetalle"
                        onclick="toggleCarritoDetalle(<?= $pelicula['ID_PELICULA'] ?>, this)"
                        class="btn <?= $enCarrito ? 'btn-warning' : 'btn-outline-warning' ?>">
                    <i class="bi <?= $enCarrito ? 'bi-cart-fill' : 'bi-cart' ?>"></i>
                    <?= $enCarrito ? 'En carrito' : 'Agregar' ?>
                </button>
            <?php endif; ?>
            <!-- MI LISTA (SIEMPRE DISPONIBLE CON SESIÓN) -->
            <button
                onclick="toggleFavoritoDetalle(<?= $pelicula['ID_PELICULA'] ?>, this)"
                class="btn <?= $favorito ? 'btn-warning' : 'btn-outline-warning' ?>">
                <i class="bi <?= $favorito ? 'bi-check' : 'bi-plus' ?>"></i>
                Mi Lista
            </button>
        <?php endif; ?>
    </div>
    <!-- ================= REVIEWS ================= -->
<?php
$yaComento = false;
if (!empty($_SESSION['id'])) {
    $yaComento = Review::usuarioYaComento(
        $_SESSION['id'],
        $pelicula['ID_PELICULA']
    );
}
?>
<div class="mt-5">
    <hr class="border-warning">
    <h4 class="text-warning mb-3">⭐ Reviews</h4>
    <!-- BOTÓN MOSTRAR FORM -->
    <?php if (!empty($_SESSION['id']) && !$yaComento): ?>
        <button id="btnMostrarReview"
                class="btn btn-outline-warning mb-3">
            <i class="bi bi-pencil-fill"></i>Añadir review
        </button>
    <?php elseif (!empty($_SESSION['id']) && $yaComento): ?>
        <div class="alert alert-warning">
            Ya enviaste un review para esta película
        </div>
    <?php endif; ?>
    <!-- FORMULARIO REVIEW -->
    <?php if (!empty($_SESSION['id']) && !$yaComento): ?>
    <div id="reviewFormContainer" style="display:none;">
        <div class="card bg-dark border-warning mb-4">
            <div class="card-body">
                <form id="formReview">
                    <input type="hidden"
                           name="pelicula"
                           value="<?= $pelicula['ID_PELICULA'] ?>">
                    <!-- STARS -->
                    <label class="text-warning">Calificación</label>
                    <div id="starRating" class="mb-2">
                        <?php for($i=1;$i<=10;$i++): ?>
                            <span class="star"
                                  data-value="<?= $i ?>">☆</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden"
                           name="calificacion"
                           id="inputCalificacion">
                    <div id="errorCalificacion"
                         class="text-danger small mb-2"
                         style="display:none;"></div>
                    <!-- COMENTARIO -->
                    <label class="text-warning">
                        Comentario
                    </label>
                    <textarea name="comentario"
                              id="inputComentario"
                              class="form-control bg-dark text-light border-warning"
                              rows="3"
                              maxlength="2000"></textarea>
                    <div id="errorComentario"
                         class="text-danger small mt-1"
                         style="display:none;"></div>
                    <!-- BOTÓN -->
                    <button id="btnEnviarReview"
                            class="btn btn-warning mt-3">
                        <span id="reviewBtnText">
                            Enviar review
                        </span>
                        <span id="reviewLoader"
                              class="spinner-border spinner-border-sm ms-2"
                              style="display:none;">
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- LISTA REVIEWS -->
    <?php if (empty($reviews)): ?>
        <div class="text-light">
            No hay reviews todavía.
        </div>
    <?php else: ?>
        <?php foreach ($reviews as $r): ?>
            <div class="card bg-dark border-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong class="text-warning">
                            <?= htmlspecialchars($r['USUARIO']) ?>
                        </strong>
                        <span class="badge border border-warning text-warning fs-8 px-3 py-2">
                            ⭐ <?= $r['CALIFICACION'] ?>/10
                        </span>
                    </div>
                    <div class="text-light mt-2">
                        <?= nl2br(htmlspecialchars($r['COMENTARIO'])) ?>
                    </div>
                    <div class="text-muted small mt-2">
                        <?= date('d/m/Y', strtotime($r['FECHA'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>
<!-- Modal -->
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
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <!-- loader -->
        <div id="alquilerLoader" class="text-center py-5">
            <div class="spinner-border text-warning"></div>
            <p class="mt-3">Cargando información...</p>
        </div>
        <!-- contenido -->
        <div id="alquilerContenido" class="d-none">
          <!-- ALERTAS DINÁMICAS -->
          <div id="alqAlertBox" class="mb-3"></div>
            <div class="row">
                <div class="col-md-4 text-center">
                <img id="alqImagen" class="img-fluid rounded shadow" style="max-height:220px; object-fit:cover;">
                </div>
                <div class="col-md-8">
                    <h4 id="alqTitulo" class="text-warning"></h4>
                    <p><strong>Género:</strong> <span id="alqGenero"></span></p>
                    <p><strong>Director:</strong> <span id="alqDirector"></span></p>
                    <p><strong>Precio:</strong> <span id="alqPrecio"></span></p>
                </div>
            </div>
            <hr class="border-warning">
            <!-- método pago -->
            <label class="form-label">Método de pago</label>
            <select id="alqMetodo" class="form-select bg-dark text-light border-warning">
                <option value="1">Tarjeta</option>
                <option value="2">PayPal</option>
            </select>
            <!-- tarjeta -->
            <div id="alqTarjetaBox" class="mt-3">
                <label class="form-label">Número de Tarjeta</label>
                <input class="form-control mb-2 bg-dark text-light border-warning"
                      placeholder="Número tarjeta">
                <label class="form-label">Fecha de Vencimiento</label>
                <input class="form-control mb-2 bg-dark text-light border-warning"
                      placeholder="Vencimiento MM/AA">
                <label class="form-label">CVV</label>
                <input class="form-control bg-dark text-light border-warning"
                      placeholder="CVV">
            </div>
        </div>
    </div>
    <div class="modal-footer border-warning">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
        </button>
        <button class="btn btn-warning" id="btnConfirmarAlquiler">
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
        <a href="/Videoteca_ElResplandor/perfil/carrito" 
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
<!-- ================= JS REVIEW ================= -->
<script src="js/reviews.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>