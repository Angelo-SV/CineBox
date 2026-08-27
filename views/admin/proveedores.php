<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>
<div class="d-flex justify-content-between mb-4">
    <h2 class="text-warning">Gestión de Proveedores</h2>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregar">
        <i class="bi bi-plus-circle"></i> Nuevo Proveedor
    </button>
</div>
<div class="card bg-dark text-light shadow">
    <div class="card-body">
    <?php if (isset($_GET['msg'])): ?>
        <div class="container">
            <?php if ($_GET['msg'] === 'insertado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    ➕ Proveedor agregado correctamente.
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'actualizado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    ✔️ Proveedor actualizado correctamente.
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'eliminado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    🗑️ Proveedor eliminado correctamente.
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
    <?php endif; ?>
        <table id="tablaProveedores" class="table table-striped table-dark table-hover w-100">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $row): ?>
                    <tr>
                        <td class="col-id"><?= $row['ID_PROVEEDOR'] ?></td>
                        <td><?= htmlspecialchars($row['NOMBRE']) ?></td>
                        <td><?= htmlspecialchars($row['CONTACTO']) ?></td>
                        <td><?= htmlspecialchars($row['CORREO']) ?></td>
                        <td><?= htmlspecialchars($row['TELEFONO']) ?></td>
                        <td><?= htmlspecialchars($row['DIRECCION']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btnEditar"
                                data-id="<?= $row['ID_PROVEEDOR'] ?>"
                                data-nombre="<?= htmlspecialchars($row['NOMBRE']) ?>"
                                data-contacto="<?= htmlspecialchars($row['CONTACTO']) ?>"
                                data-correo="<?= htmlspecialchars($row['CORREO']) ?>"
                                data-telefono="<?= htmlspecialchars($row['TELEFONO']) ?>"
                                data-direccion="<?= htmlspecialchars($row['DIRECCION']) ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="btn btn-sm btn-danger"
                                onclick="eliminarProveedor(
                                    <?= $row['ID_PROVEEDOR'] ?>,
                                    '<?= htmlspecialchars($row['NOMBRE']) ?>'
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
        <form id="formAgregar" method="POST" action="<?= BASE_PATH ?>/proveedores/crear" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="insert">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Agregar Proveedor</h5>
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
                        <label>Contacto</label>
                        <input type="text" name="contacto" id="addContacto" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Correo</label>
                        <input type="email" name="correo" id="addCorreo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" id="addTelefono" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label>Dirección</label>
                        <textarea name="direccion" id="addDireccion" class="form-control"></textarea>
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
        <form id="formEditar" method="POST" action="<?= BASE_PATH ?>/proveedores/actualizar" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Editar Proveedor</h5>
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
                        <label>Contacto</label>
                        <input type="text" id="editContacto" name="contacto" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Correo</label>
                        <input type="text" id="editCorreo" name="correo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono</label>
                        <input type="text" id="editTelefono" name="telefono" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label>Dirección</label>
                        <textarea id="editDireccion" name="direccion" class="form-control"></textarea>
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
        <form method="POST" action="<?= BASE_PATH ?>/proveedores/eliminar" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="deleteId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar Proveedor</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Deseas eliminar el proveedor:
                <strong class="text-warning" id="textoEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
        </form>
    </div>
</div>
<script src="js/proveedores.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_admin.php';
?>