document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       FUNCIONES DE VALIDACIÓN
       =============================== */
    const validarURL = url =>
        /^(https?:\/\/)[^\s$.?#].[^\s]*$/i.test(url);

    const limpiar = inputs => {
        inputs.forEach(i => i.classList.remove("is-invalid"));
    };

    /* ===============================
       VALIDACIÓN FORM AGREGAR
       =============================== */
    const formAgregar = document.getElementById("formAgregar");
    if (formAgregar) {
        formAgregar.addEventListener("submit", e => {
            let valido = true;

            const titulo = document.getElementById("addTitulo");
            const sinopsis = document.getElementById("addSinopsis");
            const genero = document.getElementById("addGenero");
            const director = document.getElementById("addDirector");
            const estudio = document.getElementById("addEstudio");
            const fecha = document.getElementById("addFecha");
            const duracion = document.getElementById("addDuracion");
            const precio = document.getElementById("addPrecio");
            const proveedor = document.getElementById("addProveedor");
            const imagen = document.getElementById("addImagen");

            limpiar([titulo, sinopsis, genero, estudio, fecha, duracion, precio, proveedor, imagen]);

            if (!titulo.value.trim()) {
                titulo.classList.add("is-invalid");
                titulo.nextElementSibling.textContent = "El título es obligatorio.";
                valido = false;
            }

            if (!sinopsis.value.trim()) {
                sinopsis.classList.add("is-invalid");
                sinopsis.nextElementSibling.textContent = "La sinopsis es obligatoria.";
                valido = false;
            }

            if (!genero.value) {
                genero.classList.add("is-invalid");
                genero.nextElementSibling.textContent = "Se debe seleccionar un género.";
                valido = false;
            }

            if (!estudio.value) {
                estudio.classList.add("is-invalid");
                estudio.nextElementSibling.textContent = "Se debe seleccionar el estudio.";
                valido = false;
            }

            if (!director.value) {
                director.classList.add("is-invalid");
                director.nextElementSibling.textContent = "Se debe seleccionar el director.";
                valido = false;
            }

            if (!proveedor.value) {
                proveedor.classList.add("is-invalid");
                proveedor.nextElementSibling.textContent = "Se debe seleccionar un proveedor.";
                valido = false;
            }

            if (!fecha.value) {
                fecha.classList.add("is-invalid");
                fecha.nextElementSibling.textContent = "La fecha de estreno es obligatoria.";
                valido = false;
            } else {
                const fechaEstreno = new Date(fecha.value);
                const hoy = new Date();

                // Normalizar hoy a 00:00:00
                hoy.setHours(0, 0, 0, 0);

                if (fechaEstreno > hoy) {
                    fecha.classList.add("is-invalid");
                    fecha.nextElementSibling.textContent =
                        "La fecha de estreno no puede ser posterior a hoy.";
                    valido = false;
                }
            }

            if (!duracion.value || duracion.value <= 0) {
                duracion.classList.add("is-invalid");
                duracion.nextElementSibling.textContent = "Se debe especificar la duración en minutos.";
                valido = false;
            }

            if (!precio.value || precio.value <= 0) {
                precio.classList.add("is-invalid");
                precio.nextElementSibling.textContent = "Se debe especificar un precio en colones.";
                valido = false;
            }

            if (imagen.value && !validarURL(imagen.value)) {
                imagen.classList.add("is-invalid");
                imagen.nextElementSibling.textContent = "URL de imagen no válida.";
                valido = false;
            }

            if (!valido) e.preventDefault();
        });
    }

    const formEditar = document.getElementById("formEditar");
    if (formEditar) {
        formEditar.addEventListener("submit", e => {
            let valido = true;

            const titulo = document.getElementById("editTitulo");
            const sinopsis = document.getElementById("editSinopsis");
            const genero = document.getElementById("editGenero");
            const director = document.getElementById("editDirector");
            const estudio = document.getElementById("editEstudio");
            const proveedor = document.getElementById("editProveedor");
            const fecha = document.getElementById("editFecha");
            const duracion = document.getElementById("editDuracion");
            const precio = document.getElementById("editPrecio");
            const imagen = document.getElementById("editImagen");

            limpiar([titulo, sinopsis, genero, director, estudio, proveedor, fecha, duracion, precio, imagen]);

            if (!titulo.value.trim()) {
                titulo.classList.add("is-invalid");
                titulo.nextElementSibling.textContent = "El título es obligatorio.";
                valido = false;
            }

            if (!sinopsis.value.trim()) {
                sinopsis.classList.add("is-invalid");
                sinopsis.nextElementSibling.textContent = "La sinopsis es obligatoria.";
                valido = false;
            }

            if (!genero.value) {
                genero.classList.add("is-invalid");
                genero.nextElementSibling.textContent = "Se debe seleccionar un género.";
                valido = false;
            }

            if (!director.value) {
                director.classList.add("is-invalid");
                director.nextElementSibling.textContent = "Se debe seleccionar el director.";
                valido = false;
            }

            if (!estudio.value) {
                estudio.classList.add("is-invalid");
                estudio.nextElementSibling.textContent = "Se debe seleccionar el estudio.";
                valido = false;
            }

            if (!proveedor.value) {
                proveedor.classList.add("is-invalid");
                proveedor.nextElementSibling.textContent = "Se debe seleccionar un proveedor.";
                valido = false;
            }

            if (!fecha.value) {
                fecha.classList.add("is-invalid");
                fecha.nextElementSibling.textContent = "La fecha de estreno es obligatoria.";
                valido = false;
            } else {
                const fechaEstreno = new Date(fecha.value);
                const hoy = new Date();

                // Normalizar hoy a 00:00:00
                hoy.setHours(0, 0, 0, 0);

                if (fechaEstreno > hoy) {
                    fecha.classList.add("is-invalid");
                    fecha.nextElementSibling.textContent =
                        "La fecha de estreno no puede ser posterior a hoy.";
                    valido = false;
                }
            }

            if (!duracion.value || duracion.value <= 0) {
                duracion.classList.add("is-invalid");
                duracion.nextElementSibling.textContent = "Duración inválida.";
                valido = false;
            }

            if (!precio.value || precio.value <= 0) {
                precio.classList.add("is-invalid");
                precio.nextElementSibling.textContent = "Precio inválido.";
                valido = false;
            }

            if (imagen.value && !validarURL(imagen.value)) {
                imagen.classList.add("is-invalid");
                imagen.nextElementSibling.textContent = "URL de imagen no válida.";
                valido = false;
            }

            if (!valido) e.preventDefault();
        });
    }

    document.querySelectorAll(".btnEditar").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("editId").value = btn.dataset.id;
            document.getElementById("editTitulo").value = btn.dataset.titulo;
            document.getElementById("editSinopsis").value = btn.dataset.sinopsis;
            document.getElementById("editFecha").value = btn.dataset.fecha;
            document.getElementById("editGenero").value = btn.dataset.generoId || "";
            document.getElementById("editDirector").value = btn.dataset.directorId || "";
            document.getElementById("editEstudio").value = btn.dataset.estudioId || "";
            document.getElementById("editProveedor").value = btn.dataset.proveedorId || "";
            document.getElementById("editDuracion").value = btn.dataset.duracion;
            document.getElementById("editPrecio").value = btn.dataset.precio;
            document.getElementById("editImagen").value = btn.dataset.imagen;
        });
    });

    /* ===============================
       DATATABLE
       =============================== */
    $("#tablaPeliculas").DataTable({
        responsive: false, // 👈 IMPORTANTE
        autoWidth: false,
        pageLength: 10,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        columnDefs: [
            { targets: 0, visible: false, searchable: false }, // ID oculto
            { targets: -1, orderable: false, searchable: false, width: "150px" },
            { targets: 1, orderable: false, width: "80px" } // Poster
        ]
    });
});

/* ===============================
   MODAL ELIMINAR
   =============================== */
function eliminarPelicula(id, titulo) {
    abrirModalEliminar(id, titulo);
}

/* ===============================
   ABRIR MODAL Y CARGAR CAST
   =============================== */
document.querySelectorAll(".btnCast").forEach(btn => {
    btn.addEventListener("click", () => {
        const peliculaId = btn.dataset.id;
        const titulo = btn.dataset.titulo;

        document.getElementById("castPeliculaId").value = peliculaId;
        document.getElementById("castTitulo").innerText = titulo;

        limpiarErrorCast();
        document.getElementById("contenedorActores").innerHTML = "";
        mostrarLoader();

        const modalEl = document.getElementById("modalCast");
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        fetch(`${window.BASE_PATH}/cast/listarActores?id=${peliculaId}`)
            .then(r => r.json())
            .then(data => {
                ocultarLoader();
                cargarCast(data);
            })
            .catch(() => {
                ocultarLoader();
                mostrarErrorCast("❌ Error al cargar el elenco.");
            });
    });
});

/* ===============================
   ESTADO GLOBAL
   =============================== */
let indexActor = 0;

/* ===============================
   CARGAR CAST
   =============================== */
function cargarCast(cast) {
    const cont = document.getElementById("contenedorActores");
    cont.innerHTML = "";
    indexActor = 0;

    if (!cast || cast.length === 0) {
        agregarFila();
        return;
    }

    cast.forEach(actor => agregarFila(actor));
}

/* ===============================
   AGREGAR FILA ACTOR
   =============================== */
function agregarFila(actor = null) {
    const cont = document.getElementById("contenedorActores");

    const div = document.createElement("div");
    div.className = "row g-2 actor-item mt-2";

    div.innerHTML = `
        <input type="hidden"
               name="actores[${indexActor}][original_id]"
               value="${actor ? actor.ID_ACTOR : 0}">

        <div class="col-md-4">
            <select name="actores[${indexActor}][id]"
                    class="form-select actor-select">
                <option value="">Seleccionar...</option>
                ${window.actoresHTML}
            </select>
        </div>

        <div class="col-md-4">
            <input type="text"
                   name="actores[${indexActor}][rol]"
                   class="form-control"
                   value="${actor ? actor.ROL : ''}">
        </div>

        <div class="col-md-2 d-flex align-items-center">
            <input type="checkbox"
                   name="protagonistas[]"
                   class="chk-protagonista"
                   value="${actor ? actor.ID_ACTOR : ''}"
                   ${actor && actor.ES_PROTAGONISTA == 1 ? 'checked' : ''}>
            <span class="ms-1">Protagonista</span>
        </div>

        <div class="col-md-2">
            <button type="button"
                    class="btn btn-outline-danger btn-sm btnEliminarActor">
                🗑
            </button>
        </div>
    `;

    cont.appendChild(div);

    if (actor) {
        div.querySelector("select").value = actor.ID_ACTOR;
    }

    const select = div.querySelector(".actor-select");
    const checkbox = div.querySelector(".chk-protagonista");

    select.addEventListener("change", () => {
        checkbox.value = select.value;
    });

    indexActor++;
}

/* ===============================
   AGREGAR NUEVO ACTOR
   =============================== */
document.getElementById("btnAgregarActor")
    .addEventListener("click", () => agregarFila());

/* ===============================
   ELIMINAR FILA (SOLO UI)
   =============================== */
document.addEventListener("click", e => {
    if (!e.target.classList.contains("btnEliminarActor")) return;

    const fila = e.target.closest(".actor-item");
    fila.remove();
});

/* ===============================
   SUBMIT FORM CAST
   =============================== */
document.getElementById("formCast").addEventListener("submit", e => {
    e.preventDefault();
    limpiarErrorCast();

    const filas = document.querySelectorAll(".actor-item");
    if (filas.length === 0) {
        mostrarErrorCast("Debe agregar al menos un actor.");
        return;
    }

    // Validar actores seleccionados
    const ids = [];
    for (const sel of document.querySelectorAll(".actor-select")) {
        if (!sel.value) {
            mostrarErrorCast("Debe seleccionar un actor en cada fila.");
            return;
        }
        if (ids.includes(sel.value)) {
            mostrarErrorCast("No puede repetir el mismo actor.");
            return;
        }
        ids.push(sel.value);
    }

    // Validar al menos un protagonista
    const protagonistas = document.querySelectorAll(
        'input[name="protagonistas[]"]:checked'
    );
    if (protagonistas.length === 0) {
        mostrarErrorCast("Debe seleccionar al menos un protagonista.");
        return;
    }

    // Enviar
    const form = e.target;
    const btn = document.getElementById("btnGuardarCast");
    const spinner = btn.querySelector(".spinner-border");
    const text = btn.querySelector(".txt");

    btn.disabled = true;
    spinner.classList.remove("d-none");
    text.textContent = "Guardando...";

    fetch(form.action, {
            method: "POST",
            body: new FormData(form)
        })
        .then(r => r.json())
        .then(resp => {
            const modalEl = document.getElementById("modalCast");
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

            modalEl.addEventListener("hidden.bs.modal", () => {
                mostrarAlerta(resp.tipo, resp.msg);
            }, { once: true });

            modal.hide();
        })
        .catch(() => {
            mostrarErrorCast("❌ Error al guardar el cast.");
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add("d-none");
            text.textContent = "Guardar Cast";
        });
});


/* ===============================
   ALERTAS & ERRORES
   =============================== */
function mostrarErrorCast(msg) {
    const div = document.getElementById("castError");
    div.textContent = msg;
    div.classList.remove("d-none");
    div.scrollIntoView({ behavior: "smooth" });
}

function limpiarErrorCast() {
    document.getElementById("castError").classList.add("d-none");
}

function mostrarAlerta(tipo, mensaje) {
    const contenedor = document.getElementById("alertContainer");
    contenedor.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show">
            ${mensaje}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    contenedor.scrollIntoView({ behavior: "smooth" });
}

/* ===============================
   LOADER
   =============================== */
function mostrarLoader() {
    document.getElementById("castLoader").classList.remove("d-none");
    document.getElementById("contenedorActores").classList.add("d-none");
}

function ocultarLoader() {
    document.getElementById("castLoader").classList.add("d-none");
    document.getElementById("contenedorActores").classList.remove("d-none");
}