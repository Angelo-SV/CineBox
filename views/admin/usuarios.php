<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>
<div class="d-flex justify-content-between mb-4">
    <h2 class="text-warning">Gestión de Usuarios</h2>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregar">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </button>
</div>
<div class="card bg-dark text-light shadow">
    <div class="card-body">
    <?php if (isset($_GET['msg'])): ?>
        <div class="container">
            <?php if ($_GET['msg'] === 'insertado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    ➕ Usuario agregado correctamente.
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'actualizado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    ✔️ Usuario actualizado correctamente.
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'eliminado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    🗑️ Usuario eliminado correctamente.
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
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
        <table id="tablaUsuarios" class="table table-striped table-dark table-hover w-100">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $row): ?>
            <tr>
                <td><?= $row['ID_USUARIO'] ?></td>
                <td><?= htmlspecialchars($row['NOMBRE']) ?></td>
                <td><?= htmlspecialchars($row['APELLIDO_PATERNO']) ?></td>
                <td><?= htmlspecialchars($row['CORREO']) ?></td>
                <td><?= htmlspecialchars($row['TELEFONO']) ?></td>
                <td>
                    <?= $row['ROL'] == 1
                        ? '<span class="badge bg-warning text-dark">Admin</span>'
                        : '<span class="badge bg-secondary">Cliente</span>' ?>
                </td>
                <td><?= (new DateTime($row['FECHA_REGISTRO']))->format('d/m/Y') ?></td>
                <td>
                <button class="btn btn-warning btn-sm btnEditar"
                    data-id="<?= $row['ID_USUARIO'] ?>"
                    data-nombre="<?= htmlspecialchars($row['NOMBRE']) ?>"
                    data-correo="<?= htmlspecialchars($row['CORREO']) ?>"
                    data-apellido-paterno="<?= htmlspecialchars($row['APELLIDO_PATERNO']) ?>"
                    data-apellido-materno="<?= htmlspecialchars($row['APELLIDO_MATERNO']) ?>"
                    data-telefono="<?= htmlspecialchars($row['TELEFONO']) ?>"
                    data-admin="<?= $row['ROL'] ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-danger"
                        onclick="eliminarUsuario(
                            <?= $row['ID_USUARIO'] ?>,
                            '<?= htmlspecialchars($row['NOMBRE']) ?>',
                            '<?= htmlspecialchars($row['APELLIDO_PATERNO']) ?>'
                        )">
                    <i class="bi bi-trash"></i>
                </button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog modal-lg">
        <form id="formAgregar" method="POST" action="<?= BASE_PATH ?>/usuarios/crear"
              class="modal-content bg-dark text-light">
              <input type="hidden" name="origen" value="admin">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Agregar Usuario</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" id="addNombre" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Correo *</label>
                        <input type="email" name="correo" id="addCorreo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Primer Apellido *</label>
                        <input type="text" name="apellidoPaterno" id="addApellido_Paterno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Segundo Apellido *</label>
                        <input type="text" name="apellidoMaterno" id="addApellido_Materno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono *</label>
                        <input type="text" name="telefono" id="addTelefono" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Contraseña *</label>
                        <input type="password" name="password" id="addPassword" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Confirmar Contraseña *</label>
                        <input type="password" name="addConfirmarContrasena" id="addConfirmPassword" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- ADMIN -->
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Usuario *</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="addAdmin" name="admin" value="1">
                            <label class="form-check-label" for="addAdmin">
                                Administrador
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog modal-lg">
        <form id="formEditar" method="POST" action="<?= BASE_PATH ?>/usuarios/actualizar"
              class="modal-content bg-dark text-light">
            <input type="hidden" id="editId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Editar Usuario</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Nombre *</label>
                        <input type="text" id="editNombre" name="nombre" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Correo *</label>
                        <input type="email" id="editCorreo" name="correo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Primer Apellido *</label>
                        <input type="text" name="apellidoPaterno" id="editApellido_Paterno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Segundo Apellido *</label>
                        <input type="text" name="apellidoMaterno" id="editApellido_Materno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono *</label>
                        <input type="text" name="telefono" id="editTelefono" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Contraseña *</label>
                        <input type="password" name="password" id="editPassword" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Confirmar Contraseña *</label>
                        <input type="password" name="editConfimarContrasena" id="editConfirmPassword" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- ADMIN -->
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Usuario *</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="editAdmin" name="admin" value="1">
                            <label class="form-check-label" for="editAdmin">
                                Administrador
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" type="submit">Actualizar</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminar">
    <div class="modal-dialog">
        <form method="POST" action="<?= BASE_PATH ?>/usuarios/eliminar" class="modal-content bg-dark text-light">
            <input type="hidden" id="deleteId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar Usuario</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Deseas eliminar el usuario:
                <strong class="text-warning" id="textoEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
        </form>
    </div>
</div>
<script src="js/shared.js"></script>
<script src="js/admin-crud.js"></script>
<script src="js/usuarios.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_admin.php';
?>