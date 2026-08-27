initAdminCrudPage({
    tableSelector: "#tablaEstudios",
    fields: [
        { id: "Nombre", dataset: "descripcion", mensaje: "El nombre no puede estar vacío." }
    ]
});

function eliminarEstudio(id, nombre) {
    abrirModalEliminar(id, nombre);
}
