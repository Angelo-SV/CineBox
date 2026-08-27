initAdminCrudPage({
    tableSelector: "#tablaProveedores",
    fields: [
        { id: "Nombre", dataset: "nombre", mensaje: "El nombre es obligatorio." },
        { id: "Contacto", dataset: "contacto", mensaje: "El contacto es obligatorio." },
        { id: "Correo", dataset: "correo", mensaje: "Correo electrónico no válido.", validate: validarCorreo },
        { id: "Telefono", dataset: "telefono", mensaje: "Teléfono no válido.", validate: validarTelefono },
        { id: "Direccion", dataset: "direccion", mensaje: "La dirección es obligatoria." }
    ]
});

function eliminarProveedor(id, nombre) {
    abrirModalEliminar(id, nombre);
}
