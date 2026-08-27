initAdminCrudPage({
    tableSelector: "#tablaGeneros",
    fields: [
        { id: "Desc", dataset: "descripcion", mensaje: "La descripción no puede estar vacía." }
    ]
});

function eliminarGenero(id, descripcion) {
    abrirModalEliminar(id, descripcion);
}
