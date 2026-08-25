document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       FUNCIÓN VALIDAR CORREO
       =============================== */
    const validarCorreo = correo =>
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);

    /* ===============================
       FUNCIÓN VALIDAR TELÉFONO
       =============================== */
    const validarTelefono = tel =>
        /^[0-9+\-\s]{6,20}$/.test(tel);

    /* ===============================
       LIMPIAR VALIDACIONES
       =============================== */
    const limpiar = inputs => {
        inputs.forEach(i => {
            i.classList.remove("is-invalid");
        });
    };

    /* ===============================
       VALIDACIÓN FORM AGREGAR
       =============================== */
    const formAgregar = document.getElementById("formAgregar");
    if (formAgregar) {
        formAgregar.addEventListener("submit", e => {
            let valido = true;
            const nombre = document.getElementById("addNombre");
            const contacto = document.getElementById("addContacto");
            const correo = document.getElementById("addCorreo");
            const telefono = document.getElementById("addTelefono");
            const direccion = document.getElementById("addDireccion");
            limpiar([nombre, contacto, correo, telefono, direccion]);
            if (!nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                nombre.nextElementSibling.textContent = "El nombre es obligatorio.";
                valido = false;
            }
            if (!contacto.value.trim()) {
                contacto.classList.add("is-invalid");
                contacto.nextElementSibling.textContent = "El contacto es obligatorio.";
                valido = false;
            }
            if (!correo.value.trim() || !validarCorreo(correo.value)) {
                correo.classList.add("is-invalid");
                correo.nextElementSibling.textContent = "Correo electrónico no válido.";
                valido = false;
            }
            if (!telefono.value.trim() || !validarTelefono(telefono.value)) {
                telefono.classList.add("is-invalid");
                telefono.nextElementSibling.textContent = "Teléfono no válido.";
                valido = false;
            }
            if (!direccion.value.trim()) {
                direccion.classList.add("is-invalid");
                direccion.nextElementSibling.textContent = "La dirección es obligatoria.";
                valido = false;
            }
            if (!valido) e.preventDefault();
        });
    }

    /* ===============================
       VALIDACIÓN FORM EDITAR
       =============================== */
    const formEditar = document.getElementById("formEditar");
    if (formEditar) {
        formEditar.addEventListener("submit", e => {
            let valido = true;
            const nombre = document.getElementById("editNombre");
            const contacto = document.getElementById("editContacto");
            const correo = document.getElementById("editCorreo");
            const telefono = document.getElementById("editTelefono");
            const direccion = document.getElementById("editDireccion");
            limpiar([nombre, contacto, correo, telefono, direccion]);
            if (!nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                nombre.nextElementSibling.textContent = "El nombre es obligatorio.";
                valido = false;
            }
            if (!contacto.value.trim()) {
                contacto.classList.add("is-invalid");
                contacto.nextElementSibling.textContent = "El contacto es obligatorio.";
                valido = false;
            }
            if (!correo.value.trim() || !validarCorreo(correo.value)) {
                correo.classList.add("is-invalid");
                correo.nextElementSibling.textContent = "Correo electrónico no válido.";
                valido = false;
            }
            if (!telefono.value.trim() || !validarTelefono(telefono.value)) {
                telefono.classList.add("is-invalid");
                telefono.nextElementSibling.textContent = "Teléfono no válido.";
                valido = false;
            }
            if (!direccion.value.trim()) {
                direccion.classList.add("is-invalid");
                direccion.nextElementSibling.textContent = "La dirección es obligatoria.";
                valido = false;
            }
            if (!valido) e.preventDefault();
        });
    }

    /* ===============================
       LLENAR MODAL EDITAR
       =============================== */
    document.querySelectorAll(".btnEditar").forEach(btn =>
        btn.addEventListener("click", function() {
            document.getElementById("editId").value = this.dataset.id;
            document.getElementById("editNombre").value = this.dataset.nombre;
            document.getElementById("editContacto").value = this.dataset.contacto;
            document.getElementById("editCorreo").value = this.dataset.correo;
            document.getElementById("editTelefono").value = this.dataset.telefono;
            document.getElementById("editDireccion").value = this.dataset.direccion;
        })
    );

    /* ===============================
       DATATABLE
       =============================== */
    $("#tablaProveedores").DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        columnDefs: [
            { targets: 0, visible: false, searchable: false },
            { targets: -1, orderable: false }
        ]
    });
});

/* ===============================
   MODAL ELIMINAR
   =============================== */
function eliminarProveedor(id, nombre) {
    document.getElementById("deleteId").value = id;
    document.getElementById("textoEliminar").innerText = nombre;
    new bootstrap.Modal(document.getElementById("modalEliminar")).show();
}