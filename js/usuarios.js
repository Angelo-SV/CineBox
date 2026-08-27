document.addEventListener("DOMContentLoaded", () => {

    /* validarCorreo/validarTelefono vienen de shared.js */
    const limpiar = inputs => {
        inputs.forEach(i => {
            i.classList.remove("is-invalid");
            if (i.nextElementSibling) {
                i.nextElementSibling.textContent = "";
            }
        });
    };

    /* ===============================
       VALIDAR FORM AGREGAR
       =============================== */
    const formAgregar = document.getElementById("formAgregar");
    if (formAgregar) {
        formAgregar.addEventListener("submit", e => {
            let valido = true;
            const nombre = document.getElementById("addNombre");
            const correo = document.getElementById("addCorreo");
            const apellidoP = document.getElementById("addApellido_Paterno");
            const apellidoM = document.getElementById("addApellido_Materno");
            const telefono = document.getElementById("addTelefono");
            const password = document.getElementById("addPassword");
            const confirm = document.getElementById("addConfirmPassword");
            limpiar([
                nombre, correo, apellidoP, apellidoM,
                telefono, password, confirm
            ]);
            if (!nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                nombre.nextElementSibling.textContent = "El nombre es obligatorio.";
                valido = false;
            }
            if (!apellidoP.value.trim()) {
                apellidoP.classList.add("is-invalid");
                apellidoP.nextElementSibling.textContent = "El primer apellido es obligatorio.";
                valido = false;
            }
            if (!apellidoM.value.trim()) {
                apellidoM.classList.add("is-invalid");
                apellidoM.nextElementSibling.textContent = "El segundo apellido es obligatorio.";
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
            if (!password.value || password.value.length < 8) {
                password.classList.add("is-invalid");
                password.nextElementSibling.textContent =
                    "La contraseña debe tener al menos 8 caracteres.";
                valido = false;
            }
            if (confirm.value !== password.value) {
                confirm.classList.add("is-invalid");
                confirm.nextElementSibling.textContent =
                    "Las contraseñas no coinciden.";
                valido = false;
            }
            if (!valido) e.preventDefault();
        });
    }

    /* ===============================
       VALIDAR FORM EDITAR
       =============================== */
    const formEditar = document.getElementById("formEditar");
    if (formEditar) {
        formEditar.addEventListener("submit", e => {
            let valido = true;
            const nombre = document.getElementById("editNombre");
            const correo = document.getElementById("editCorreo");
            const apellidoP = document.getElementById("editApellido_Paterno");
            const apellidoM = document.getElementById("editApellido_Materno");
            const telefono = document.getElementById("editTelefono");
            const password = document.getElementById("editPassword");
            const confirm = formEditar.querySelector("#editConfirmPassword");
            limpiar([
                nombre, correo, apellidoP, apellidoM,
                telefono, password, confirm
            ]);
            if (!nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                nombre.nextElementSibling.textContent = "El nombre es obligatorio.";
                valido = false;
            }
            if (!apellidoP.value.trim()) {
                apellidoP.classList.add("is-invalid");
                apellidoP.nextElementSibling.textContent = "El primer apellido es obligatorio.";
                valido = false;
            }
            if (!apellidoM.value.trim()) {
                apellidoM.classList.add("is-invalid");
                apellidoM.nextElementSibling.textContent = "El segundo apellido es obligatorio.";
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
            /* Contraseña opcional */
            if (password.value) {
                if (password.value.length < 8) {
                    password.classList.add("is-invalid");
                    password.nextElementSibling.textContent =
                        "La contraseña debe tener al menos 8 caracteres.";
                    valido = false;
                }
                if (confirm.value !== password.value) {
                    confirm.classList.add("is-invalid");
                    confirm.nextElementSibling.textContent =
                        "Las contraseñas no coinciden.";
                    valido = false;
                }
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
            document.getElementById("editCorreo").value = this.dataset.correo;
            document.getElementById("editApellido_Paterno").value = this.dataset.apellidoPaterno;
            document.getElementById("editApellido_Materno").value = this.dataset.apellidoMaterno;
            document.getElementById("editTelefono").value = this.dataset.telefono;
            const rol = parseInt(this.dataset.admin, 10);
            document.getElementById("editAdmin").checked = (rol === 1);
        })
    );

    /* ===============================
       DATATABLE
       =============================== */
    initDataTableSimple("#tablaUsuarios");
});

/* ===============================
   MODAL ELIMINAR USUARIO
   =============================== */
function eliminarUsuario(id, nombre, apellido) {
    abrirModalEliminar(id, `${nombre} ${apellido}`);
}

const correoInput = document.getElementById("addCorreo");
if (correoInput) {
    correoInput.addEventListener("blur", () => {
        const correo = correoInput.value.trim();
        if (!correo) return;
        fetch(window.BASE_PATH + "/validaCorreo.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "correo=" + encodeURIComponent(correo)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === "exists") {
                    correoInput.classList.add("is-invalid");
                    correoInput.nextElementSibling.textContent =
                        "Este correo ya está registrado.";
                }
            })
            .catch(() => {
                console.error("Error validando correo");
            });
    });
}