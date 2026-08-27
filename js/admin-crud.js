/* ===============================
   PANEL ADMIN — utilidades compartidas de las páginas de catálogo
   (actores, directores, estudios, géneros, proveedores, usuarios,
   películas): inicialización de DataTables, modal de confirmar
   eliminación, y un inicializador genérico de página CRUD para las
   páginas de catálogo simple (un formulario, un modal de edición).
   =============================== */

function initDataTableSimple(selector) {
    return $(selector).DataTable({
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
}

function abrirModalEliminar(id, texto) {
    document.getElementById("deleteId").value = id;
    document.getElementById("textoEliminar").innerText = texto;
    new bootstrap.Modal(document.getElementById("modalEliminar")).show();
}

/**
 * Inicializa una página de catálogo simple (actores, directores,
 * estudios, géneros, proveedores): valida los formularios de agregar
 * y editar, llena el modal de edición desde los data-* del botón, e
 * inicializa la tabla.
 *
 * @param {Object} opts
 * @param {string} opts.tableSelector   Selector de la tabla, ej. "#tablaActores"
 * @param {Array}  opts.fields          [{ id, dataset, mensaje, validate? }]
 *   - id: sufijo del input (addNombre/editNombre -> id: "Nombre")
 *   - dataset: nombre de la propiedad en el dataset del botón .btnEditar
 *   - mensaje: texto de error a mostrar si la validación falla
 *   - validate: función opcional (valor) => bool. Por defecto exige no-vacío.
 */
function initAdminCrudPage({ tableSelector, fields }) {
    document.addEventListener("DOMContentLoaded", () => {
        function validarFormulario(form, prefijo) {
            form.addEventListener("submit", e => {
                let valido = true;
                fields.forEach(f => {
                    const input = document.getElementById(prefijo + f.id);
                    input.classList.remove("is-invalid");
                    const valor = input.value.trim();
                    const ok = f.validate ? f.validate(valor) : valor !== "";
                    if (!ok) {
                        input.classList.add("is-invalid");
                        input.nextElementSibling.textContent = f.mensaje;
                        valido = false;
                    }
                });
                if (!valido) e.preventDefault();
            });
        }

        const formAgregar = document.getElementById("formAgregar");
        if (formAgregar) validarFormulario(formAgregar, "add");

        const formEditar = document.getElementById("formEditar");
        if (formEditar) validarFormulario(formEditar, "edit");

        document.querySelectorAll(".btnEditar").forEach(btn =>
            btn.addEventListener("click", function () {
                document.getElementById("editId").value = this.dataset.id;
                fields.forEach(f => {
                    document.getElementById("edit" + f.id).value =
                        this.dataset[f.dataset];
                });
            })
        );

        initDataTableSimple(tableSelector);
    });
}
