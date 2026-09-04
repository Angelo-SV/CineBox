<?php
$titulo = 'Iniciar Sesión - CineBox';
ob_start();

if (isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit;
}
?>
<!-- Contenido principal -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-lg border-0">
        <div class="card-header text-center bg-dark text-warning">
          <h4 class="mb-0">Accede a tu Perfil</h4>
        </div>
        <?php if (isset($_GET['msg'])): ?>
              <div class="px-4 pt-3 bg-dark">
                  <?php if ($_GET['msg'] === 'registro_ok'): ?>
                      <div class="alert alert-success alert-dismissible fade show">
                          ➕ Usuario agregado correctamente.
                          <button class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                  <?php elseif ($_GET['msg'] === 'contrasena'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        Error al iniciar sesión, la contraseña ingresada es incorrecta.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php elseif ($_GET['msg'] === 'correoInvalido'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        Error al iniciar sesión, el correo ingresado aún no se encuentra registrado.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php elseif ($_GET['msg'] === 'errorDB'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        Error al iniciar sesión, ocurrió un problema al conectarse con la base de datos. Inténtalo de nuevo más tarde.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php elseif ($_GET['msg'] === 'error_campos'): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                          ⚠️ Debes completar los campos obligatorios.
                          <button class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                  <?php elseif ($_GET['msg'] === 'error_bd'): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                          ❌ Error al procesar la solicitud.<br>
                          <small><?= htmlspecialchars($_GET['detalle'] ?? '') ?></small>
                          <button class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                  <?php elseif ($_GET['msg'] === 'no_auth'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        ❌ Para acceder al módulo de administración debes iniciar sesión con un usuario administrador.<br>
                        <small><?= htmlspecialchars($_GET['detalle'] ?? '') ?></small>
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php endif; ?>
              </div>
          <?php endif; ?>
        <div class="card-body bg-dark text-light">
          <form id="loginForm" method="POST" action="<?= BASE_PATH ?>/auth/login" novalidate>
          <input type="hidden" name="action" value="login">
            <div class="mb-3">
              <label for="correo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
              <input type="email" class="form-control" name="correo" id="correo" placeholder="ejemplo@correo.com">
              <div class="invalid-feedback">Por favor ingrese un correo válido.</div>
            </div>
            <div class="mb-3">
              <label for="contrasena" class="form-label">Contraseña <span class="text-danger">*</span></label>
              <input type="password" class="form-control" name="contrasena" id="contrasena">
              <div class="invalid-feedback">Debe ingresar su contraseña.</div>
            </div>
            <button type="submit" class="btn btn-warning w-100 justify-content-center align-items-center">Iniciar Sesión</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Script de validación -->
<script src="js/login.js"></script>
<script src="js/password-toggle.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>