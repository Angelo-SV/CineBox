<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 
?>
<div class="d-flex justify-content-between mb-4">
    <h2 class="text-warning">Gestión de Directores</h2>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregar">
        <i class="bi bi-plus-circle"></i> Nuevo Director
    </button>
</div>
<div class="card bg-dark text-light shadow">
    <div class="card-body">
    <?php if (isset($_GET['msg'])): ?>
<div class="container">
    <?php if ($_GET['msg'] === 'actualizado'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                ✔️ Director actualizado correctamente.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'eliminado'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                🗑️ Director eliminado correctamente.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'insertado'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                ➕ Director agregado exitosamente.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'error_campos'): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                ⚠️ Debes completar todos los campos obligatorios.
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
        <table id="tablaDirectores" id="tablaDirectores" class="table table-striped table-dark table-hover w-100">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th>Nombre</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($directores as $row): ?>
                <tr>
                    <td class="col-id"><?= $row['ID_DIRECTOR'] ?></td>
                    <td><?= $row['NOMBRE'] ?></td>
                    <td>
                    <button class="btn btn-warning btn-sm btnEditar"
                            data-id="<?= $row['ID_DIRECTOR'] ?>"
                            data-descripcion="<?= htmlspecialchars($row['NOMBRE']) ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditar">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger"
                            onclick="eliminarDirector(<?= $row['ID_DIRECTOR'] ?>, '<?= htmlspecialchars($row['NOMBRE']) ?>')">
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
    <div class="modal-dialog">
        <form id="formAgregar" method="POST" action="<?= BASE_PATH ?>/directores/crear" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="insert">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Agregar Director</h5>
                <button class="btn-close bg-light" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="addNombre" class="form-control">
                <div class="invalid-feedback">La descripción no puede estar vacía.</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-warning" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog">
        <form id="formEditar" method="POST" action="<?= BASE_PATH ?>/directores/actualizar" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Editar Director</h5>
                <button class="btn-close bg-light" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <label>Descripción:</label>
                <input type="text" id="editNombre" name="nombre" class="form-control">
                <div class="invalid-feedback">El nombre no puede estar vacio.</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-warning" type="submit">Actualizar</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminar">
    <div class="modal-dialog">
        <form method="POST" action="<?= BASE_PATH ?>/directores/eliminar" class="modal-content bg-dark text-light">
            <input type="hidden" name="action" value="delete">    
            <input type="hidden" id="deleteId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar Director</h5>
                <button class="btn-close bg-light" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                ¿Realmente deseas eliminar el Director:
                <strong class="text-warning" id="textoEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>

        </form>
    </div>
</div>
<script src="js/directores.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_admin.php';
?>