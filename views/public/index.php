<?php
$titulo = 'Inicio - CineBox';
ob_start();
?>
<!-- HERO CAROUSEL -->
<div id="carouselExampleCaptions" class="carousel slide mb-5 shadow-lg" data-bs-ride="carousel">
  <div class="carousel-inner">
  <div class="carousel-item active">
  <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Carteles%20Peliculas%2Fspiderman-banner-730x310.jpg?alt=media&token=915edd14-8384-45f6-976b-f307306da0b4"
       class="d-block w-100"
       style="height:520px; object-fit:cover;">
  <div class="carousel-caption text-start">
    <div class="caption-box">
      <h2 class="text-warning fw-bold">Estrenos Imperdibles</h2>
      <p>Descubre lo más nuevo en CineBox y vive la experiencia del cine en casa</p>
    </div>
  </div>
</div>
<div class="carousel-item">
  <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Carteles%20Peliculas%2FOppenheimer-banner.jpg?alt=media&token=7b77906c-a547-49ed-99fb-26aa11763d97"
       class="d-block w-100"
       style="height:520px; object-fit:cover;">
  <div class="carousel-caption">
    <div class="caption-box">
      <h2 class="text-warning fw-bold">Grandes Producciones</h2>
      <p>Disfruta de los éxitos más taquilleros y películas aclamadas</p>
    </div>
  </div>
</div>
<div class="carousel-item">
  <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Carteles%20Peliculas%2FTopGunMaverick_Banner.jpg?alt=media&token=2e1d8e59-610f-47a2-8842-9af7790892fe"
       class="d-block w-100"
       style="height:520px; object-fit:cover;">
  <div class="carousel-caption">
    <div class="caption-box text-end">
      <h2 class="text-warning fw-bold">Todos los Géneros</h2>
      <p>Acción, comedia, drama y más… todo en un solo lugar</p>
    </div>
  </div>
</div>
  </div>
  <button class="carousel-control-prev"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
<!-- SECCIÓN PRINCIPAL -->
<div class="container mb-5">
  <!-- HEADER SECCIÓN -->
  <div class="text-center mb-4">
    <h2 class="text-warning fw-bold">Películas Destacadas</h2>
    <p class="text-light">
      Explora nuestro catálogo y encuentra tu próxima película favorita
    </p>
  </div>
  <!-- ===============================
     BLOQUE FILTROS UNIFICADO
================================ -->
<div class="card bg-dark border-warning shadow mb-4">
  <div class="card-body">
    <div class="filtros-container">
      <!-- BUSCADOR -->
      <div class="filtro-item filtro-busqueda">
        <span class="icono">🔎</span>
        <input type="text"
               id="buscadorTitulo"
               placeholder="Buscar película...">
      </div>
      <!-- GENERO -->
      <div class="filtro-item">
        <select id="filtroGenero" onchange="aplicarFiltros()">
          <option value="0">🎭 Género</option>
        </select>
      </div>
      <!-- ESTUDIO -->
      <div class="filtro-item">
        <select id="filtroEstudio" onchange="aplicarFiltros()">
          <option value="0">🏢 Estudio</option>
        </select>
      </div>
      <!-- SWITCHES -->
      <?php if (!empty($_SESSION['id'])): ?>
      <div class="filtro-item filtros-switches">
        <label class="switch-chip">
          <input type="checkbox" id="switchMiLista" onchange="aplicarFiltros()">
          <span><i class="bi bi-heart me-1"></i>Mi Lista</span>
        </label>
        <label class="switch-chip">
          <input type="checkbox" id="switchAlquiladas" onchange="aplicarFiltros()">
          <span><i class="bi bi-ticket-perforated me-1"></i>Alquiladas</span>
        </label>
        <label class="switch-chip">
          <input type="checkbox" id="switchCarrito" onchange="aplicarFiltros()">
          <span><i class="bi bi-cart me-1"></i>Carrito</span>
        </label>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- GRID PELÍCULAS -->
<div class="row row-cols-1 row-cols-md-3 g-4"
      id="contenedorPeliculas">
</div>
  <!-- PAGINACIÓN -->
  <nav class="mt-5">
    <ul class="pagination justify-content-center"
        id="paginacion"></ul>
  </nav>
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
        <a href="<?= BASE_PATH ?>/biblioteca"
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
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/peliculas-public.js"></script>
<script>
window.usuarioLogueado = <?= isset($_SESSION['id']) ? 'true' : 'false' ?>;
</script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>