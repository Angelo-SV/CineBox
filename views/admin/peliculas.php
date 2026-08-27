<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>
<div class="d-flex justify-content-between mb-4">
    <h2 class="text-warning">Gestión de Películas</h2>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregar">
        <i class="bi bi-plus-circle"></i> Nueva Película
    </button>
</div>
<div class="card bg-dark text-light shadow">
    <div class="card-body">
        <div id="alertContainer"></div>
        <?php if (isset($_GET['msg'])): ?>
            <div class="container">
                <?php if ($_GET['msg'] === 'insertado'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        🎬 Película agregada correctamente.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'actualizado'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        ✔️ Película actualizada correctamente.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'eliminado'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        🗑️ Película eliminada correctamente.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'error_campos'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        ⚠️ Completa todos los campos obligatorios.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'error_bd'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        ❌ Ha ocurrido un error al procesar la solicitud.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'cast_guardado'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        🎬 Actor(es) agregado(s) correctamente.
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
            <table id="tablaPeliculas" class="table table-striped table-dark table-hover w-100 align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Poster</th>
                <th>Título</th>
                <th>Género</th>
                <th>Estudio</th>
                <th>Duración</th>
                <th>Precio</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($peliculas as $row): 
            $img = $row['IMAGEN'] ?: IMG_DEFAULT;
        ?>
            <tr>
                <td><?= $row['ID_PELICULA'] ?></td>
                <!-- POSTER -->
                <td class="text-center">
                    <img src="<?= htmlspecialchars($img) ?>"
                        class="img-thumbnail"
                        style="width:60px; height:90px; object-fit:cover;">
                </td>
                <td><?= htmlspecialchars($row['TITULO']) ?></td>
                <td><?= htmlspecialchars($row['GENERO']) ?></td>
                <td><?= htmlspecialchars($row['ESTUDIO']) ?></td>
                <td><?= $row['DURACION'] ?> min</td>
                <td>₡<?= number_format($row['PRECIO'], 2) ?></td>
                <!-- OPCIONES -->
                <td class="text-center">
                    <button class="btn btn-warning btn-sm btnEditar"
                        data-id="<?= $row['ID_PELICULA'] ?>"
                        data-titulo="<?= htmlspecialchars($row['TITULO']) ?>"
                        data-sinopsis="<?= htmlspecialchars($row['SINOPSIS']) ?>"
                        data-genero-id="<?= $row['GENERO_ID'] ?>"
                        data-director-id="<?= $row['ID_DIRECTOR'] ?>"
                        data-estudio-id="<?= $row['ESTUDIO_ID'] ?>"
                        data-proveedor-id="<?= $row['PROVEEDOR_ID'] ?>"
                        data-fecha="<?= date('Y-m-d', strtotime($row['FECHA_ESTRENO'])) ?>"
                        data-duracion="<?= $row['DURACION'] ?>"
                        data-precio="<?= $row['PRECIO'] ?>"
                        data-imagen="<?= htmlspecialchars($row['IMAGEN'] ?? '') ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditar">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-danger btn-sm"
                        onclick="eliminarPelicula(<?= $row['ID_PELICULA'] ?>,'<?= htmlspecialchars($row['TITULO']) ?>')">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button type="button"
                        class="btn btn-info btn-sm btnCast"
                        data-id="<?= $row['ID_PELICULA'] ?>"
                        data-titulo="<?= htmlspecialchars($row['TITULO']) ?>">
                        <i class="bi bi-people-fill"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<!-- ================= MODAL AGREGAR ================= -->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog modal-xl">
        <form id="formAgregar" method="POST" action="<?= BASE_PATH ?>/peliculas/crear"
              class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Agregar Película</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- TITULO -->
                    <div class="col-md-6">
                        <label>Título *</label>
                        <input type="text" id="addTitulo" name="titulo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- FECHA ESTRENO -->
                    <div class="col-md-6">
                        <label>Fecha de Estreno *</label>
                        <input type="date" id="addFecha" name="fecha_estreno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- SINOPSIS -->
                    <div class="col-12">
                        <label>Sinopsis *</label>
                        <textarea id="addSinopsis" name="sinopsis" rows="4" class="form-control"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- GENERO -->
                    <div class="col-md-3">
                        <label>Género *</label>
                        <select id="addGenero" name="genero_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($generos as $g): ?>
                                <option value="<?= $g['ID_GENERO'] ?>">
                                    <?= htmlspecialchars($g['DESCRIPCION']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- DIRECTOR -->
                    <div class="col-md-3">
                        <label>Director</label>
                        <select id="addDirector" name="director_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($directores as $d): ?>
                                <option value="<?= $d['ID_DIRECTOR'] ?>">
                                    <?= htmlspecialchars($d['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- ESTUDIO -->
                    <div class="col-md-3">
                        <label>Estudio *</label>
                        <select id="addEstudio" name="estudio_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($estudios as $e): ?>
                                <option value="<?= $e['ID_ESTUDIO'] ?>">
                                    <?= htmlspecialchars($e['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- PROVEEDOR -->
                    <div class="col-md-3">
                        <label>Proveedor *</label>
                        <select id="addProveedor" name="proveedor_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?= $p['ID_PROVEEDOR'] ?>">
                                    <?= htmlspecialchars($p['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- DURACION -->
                    <div class="col-md-4">
                        <label>Duración (min) *</label>
                        <input type="number" id="addDuracion" name="duracion" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- PRECIO -->
                    <div class="col-md-4">
                        <label>Precio *</label>
                        <input type="number" step="0.01" id="addPrecio" name="precio" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- IMAGEN -->
                    <div class="col-md-4">
                        <label>Imagen / Poster (URL)</label>
                        <input type="text" id="addImagen" name="imagen" class="form-control">
                        <small class="text-muted">
                            Si se deja vacío se usará imagen genérica
                        </small>
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
<!-- ================= MODAL EDITAR ================= -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog modal-xl">
        <form id="formEditar" method="POST" action="<?= BASE_PATH ?>/peliculas/actualizar"
              class="modal-content bg-dark text-light">
            <input type="hidden" id="editId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Editar Película</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- TITULO -->
                    <div class="col-md-6">
                        <label>Título *</label>
                        <input type="text" id="editTitulo" name="titulo" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- FECHA ESTRENO -->
                    <div class="col-md-6">
                        <label>Fecha de Estreno *</label>
                        <input type="date" id="editFecha" name="fecha_estreno" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- SINOPSIS -->
                    <div class="col-12">
                        <label>Sinopsis *</label>
                        <textarea id="editSinopsis" name="sinopsis" rows="4" class="form-control"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- GENERO -->
                    <div class="col-md-3">
                        <label>Género *</label>
                        <select id="editGenero" name="genero_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($generos as $g): ?>
                                <option value="<?= $g['ID_GENERO'] ?>">
                                    <?= htmlspecialchars($g['DESCRIPCION']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- DIRECTOR -->
                    <div class="col-md-3">
                        <label>Director</label>
                        <select id="editDirector" name="director_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($directores as $d): ?>
                                <option value="<?= $d['ID_DIRECTOR'] ?>">
                                    <?= htmlspecialchars($d['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- ESTUDIO -->
                    <div class="col-md-3">
                        <label>Estudio *</label>
                        <select id="editEstudio" name="estudio_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($estudios as $e): ?>
                                <option value="<?= $e['ID_ESTUDIO'] ?>">
                                    <?= htmlspecialchars($e['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- PROVEEDOR -->
                    <div class="col-md-3">
                        <label>Proveedor *</label>
                        <select id="editProveedor" name="proveedor_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?= $p['ID_PROVEEDOR'] ?>">
                                    <?= htmlspecialchars($p['NOMBRE']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- DURACION -->
                    <div class="col-md-4">
                        <label>Duración (min) *</label>
                        <input type="number" id="editDuracion" name="duracion" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- PRECIO -->
                    <div class="col-md-4">
                        <label>Precio *</label>
                        <input type="number" step="0.01" id="editPrecio" name="precio" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- IMAGEN -->
                    <div class="col-md-4">
                        <label>Imagen / Poster (URL)</label>
                        <input type="text" id="editImagen" name="imagen" class="form-control">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">
                            Si se deja vacío se usará imagen genérica
                        </small>
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
<!-- ================= MODAL ELIMINAR ================= -->
<div class="modal fade" id="modalEliminar">
    <div class="modal-dialog">
        <form method="POST" action="<?= BASE_PATH ?>/peliculas/eliminar" class="modal-content bg-dark text-light">
            <input type="hidden" id="deleteId" name="id">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar Película</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Deseas eliminar la película:
                <strong class="text-warning" id="textoEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
        </form>
    </div>
</div>
<!-- ================= MODAL AÑADIR CAST ================= -->
<div class="modal fade" id="modalCast">
    <div class="modal-dialog modal-lg">
        <form id="formCast" method="POST" action="<?= BASE_PATH ?>/cast/guardar"
              class="modal-content bg-dark text-light">
            <!-- <input type="hidden" name="action" value="save_cast"> -->
            <input type="hidden" name="pelicula" id="castPeliculaId">
            <div class="modal-header">
                <h5 class="modal-title text-warning">
                    🎭 Cast de <span id="castTitulo"></span>
                </h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div id="castError" class="alert alert-danger d-none"></div>
            <div class="modal-body">
                <div id="castLoader" class="text-center my-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Cargando elenco...</p>
                </div>
                <!-- AQUÍ JS INSERTA LOS ACTORES -->
                <div id="contenedorActores"></div>
                <button type="button"
                        id="btnAgregarActor"
                        class="btn btn-outline-info btn-sm mt-3">
                    ➕ Agregar otro actor
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" type="submit" id="btnGuardarCast">
                    <span class="txt">Guardar Cast</span>
                    <span class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
window.actoresHTML = `
<?php foreach ($actores as $a): ?>
<option value="<?= $a['ID_ACTOR'] ?>">
    <?= htmlspecialchars($a['NOMBRE']) ?>
</option>
<?php endforeach; ?>
`;
</script>
<script src="js/admin-crud.js"></script>
<script src="js/peliculas.js"></script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout_admin.php';
?>