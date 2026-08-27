initAdminCrudPage({
    tableSelector: "#tablaDirectores",
    fields: [
        { id: "Nombre", dataset: "descripcion", mensaje: "El nombre no puede estar vacío." }
    ]
});

function eliminarDirector(id, nombre) {
    abrirModalEliminar(id, nombre);
}
