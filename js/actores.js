document.addEventListener("DOMContentLoaded", () => {
    // VALIDACIÓN FORM AGREGAR
    const formAgregar = document.getElementById("formAgregar");
    if (formAgregar) {
        formAgregar.addEventListener("submit", e => {
            const input = document.getElementById("addNombre");
            let valido = true;
            input.classList.remove("is-invalid");
            if (!input.value.trim()) {
                input.classList.add("is-invalid");
                input.nextElementSibling.textContent = "El nombre no puede estar vacío.";
                valido = false;
            }
            if (!valido) e.preventDefault();
        });
    }

    // VALIDACIÓN FORM EDITAR
    const formEditar = document.getElementById("formEditar");
    if (formEditar) {
        formEditar.addEventListener("submit", e => {
            const input = document.getElementById("editNombre");
            let valido = true;
            input.classList.remove("is-invalid");
            if (!input.value.trim()) {
                input.classList.add("is-invalid");
                input.nextElementSibling.textContent = "El nombre no puede estar vacío.";
                valido = false;
            }
            if (!valido) e.preventDefault();
        });
    }

    // LLENAR MODAL EDITAR
    document.querySelectorAll(".btnEditar").forEach(btn =>
        btn.addEventListener("click", function() {
            document.getElementById("editId").value = this.dataset.id;
            document.getElementById("editNombre").value = this.dataset.descripcion;
        })
    );

    // Inicializar DataTables
    const tabla = $("#tablaActores").DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        columnDefs: [{
                targets: 0, // columna ID
                visible: false, // ocultarla
                searchable: false // evitar que aparezca en búsqueda
            },
            {
                targets: 2, // columna opciones
                orderable: false // evitar ordenar por botones
            }
        ]
    });
});

// MODAL ELIMINAR
function eliminarActor(id, nombre) {
    document.getElementById("deleteId").value = id;
    document.getElementById("textoEliminar").innerText = nombre;
    const modal = new bootstrap.Modal(document.getElementById("modalEliminar"));
    modal.show();
}