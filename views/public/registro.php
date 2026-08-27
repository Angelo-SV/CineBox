<?php
$titulo = "Registro de Usuario - CineBox";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>
<!-- Contenido principal -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-warning text-center">
          <h4 class="mb-0">Regístrate para Acceder a Nuestros Servicios</h4>
        </div>
        <?php if (isset($_GET['msg'])): ?>
              <div class="px-4 pt-3 bg-dark">
                  <?php if ($_GET['msg'] === 'error_campos'): ?>
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
                  <?php elseif ($_GET['msg'] === 'error_password'): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            ❌ Las contraseñas no coinciden.
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                  <?php endif; ?>
              </div>
          <?php endif; ?>
        <div class="card-body bg-dark text-light">
          <form id="formRegistro" method="POST" action="<?= BASE_PATH ?>/usuarios/crear">
          <input type="hidden" name="action" value="insert">
          <input type="hidden" name="origen" value="registro">
            <div class="mb-3">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Primer Apellido <span class="text-danger">*</span></label>
              <input type="text" name="apellidoPaterno" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Segundo Apellido <span class="text-danger">*</span></label>
              <input type="text" name="apellidoMaterno" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
              <input type="text" name="correo" class="form-control" placeholder="ejemplo@correo.com">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
              <input type="password" name="confirmarContrasena" class="form-control">
              <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-warning w-100 btn-lg">¡Registrarme!</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
<!-- Archivo JS externo -->
<script src="js/registro.js"></script>