initAdminCrudPage({
    tableSelector: "#tablaActores",
    fields: [
        { id: "Nombre", dataset: "descripcion", mensaje: "El nombre no puede estar vacío." }
    ]
});

function eliminarActor(id, nombre) {
    abrirModalEliminar(id, nombre);
}
