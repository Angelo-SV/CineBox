<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="navbar-custom shadow-sm border-bottom border-warning">
  <div class="container py-2">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start"> 
      <!-- Logo -->
      <a href="/Videoteca_ElResplandor/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo3.png?alt=media&token=31ad1239-a16d-46cc-a1a2-ffd773a17a6a" 
        width="240" height="60" alt="CineBox Logo">
      </a>
      <!-- Links -->
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ms-3">
        <li><a href="/Videoteca_ElResplandor/nosotros" class="nav-link px-2 text-light">Nosotros</a></li>
      </ul>
      <!-- Área de sesión -->
      <div class="text-end">
        <ul class="nav mb-2 mb-md-0">
          <?php if (!isset($_SESSION['correo'])): ?>
            <li><a href="/Videoteca_ElResplandor/login" class="btn btn-outline-light me-2">Iniciar Sesión</a></li>
            <li><a href="/Videoteca_ElResplandor/registro" class="btn btn-warning">Registrarse</a></li>
          <?php else: ?>
            <li class="nav-item position-relative">
                <a href="/Videoteca_ElResplandor/perfil/carrito" class="nav-link text-light position-relative">
                    <i class="bi bi-cart-fill fs-5"></i>
                    <!-- BURBUJA -->
                    <span id="cart-count"
                          class="position-absolute top-2 start-95 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 0.6rem;">
                          0
                    </span>
                </a>
            </li>
            <?php if (isset($_SESSION['rol'])): ?>
              <li>
                  <a href="/Videoteca_ElResplandor/perfil"
                    class="btn btn-outline-light me-2">
                    <i class="bi bi-person-circle"></i>
                    Mi Perfil
                  </a>
              </li>
              <?php if ($_SESSION['rol'] == ROL_ADMIN): ?>
              <li>
                  <a href="/Videoteca_ElResplandor/admin"
                    class="btn btn-outline-light me-2">
                    <i class="bi bi-shield-lock"></i>
                    Admin
                  </a>
              </li>
              <?php endif; ?>
              <li>
                  <a href="/Videoteca_ElResplandor/auth/logout"
                    class="btn btn-warning">
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                  </a>
              </li>
              <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</header>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const cartCount = document.getElementById("cart-count");
    if (!cartCount) return;
    function actualizarCantidad() {
        fetch("/Videoteca_ElResplandor/carrito/cantidad")
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                cartCount.textContent = data.cantidad;

                if (data.cantidad == 0) {
                    cartCount.style.display = "none";
                } else {
                    cartCount.style.display = "inline-block";
                }
            }
        });
    }
    actualizarCantidad();
});
</script>