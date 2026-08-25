document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formRegistro");

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        let valido = true;

        // Limpiar mensajes previos
        form.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");
        form.querySelectorAll(".form-control").forEach(el => el.classList.remove("is-invalid"));

        const nombre = form.nombre.value.trim();
        const apellido1 = form.apellidoPaterno.value.trim();
        const apellido2 = form.apellidoMaterno.value.trim();
        const correo = form.correo.value.trim();
        const telefono = form.telefono.value.trim();
        const contrasena = form.password.value.trim();
        const confirmarContrasena = form.confirmarContrasena.value.trim();

        // Patrón para contraseña segura: mínimo 8 caracteres, una mayúscula, una minúscula y un número
        const patronContrasena = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        // Validaciones
        if (nombre === "") mostrarError("nombre", "Debe ingresar su nombre.");
        if (apellido1 === "") mostrarError("apellidoPaterno", "Debe ingresar su primer apellido.");
        if (apellido2 === "") mostrarError("apellidoMaterno", "Debe ingresar su segundo apellido.");
        if (correo === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo))
            mostrarError("correo", "Debe ingresar un correo válido.");
        if (telefono === "") mostrarError("telefono", "Debe ingresar su número de teléfono.");

        // Validación de contraseña
        if (contrasena === "") {
            mostrarError("password", "Debe ingresar una contraseña.");
            valido = false;
        } else if (!patronContrasena.test(contrasena)) {
            mostrarError("password", "La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.");
            valido = false;
        }

        // Validación de confirmación
        if (confirmarContrasena === "") {
            mostrarError("confirmarContrasena", "Debe confirmar su contraseña.");
            valido = false;
        } else if (contrasena !== confirmarContrasena) {
            mostrarError("confirmarContrasena", "Las contraseñas no coinciden.");
            valido = false;
        }

        // Validación de confirmación
        if (confirmarContrasena === "") {
            mostrarError("confirmarContrasena", "Debe confirmar su contraseña.");
            valido = false;
        } else if (contrasena !== confirmarContrasena) {
            mostrarError("confirmarContrasena", "Las contraseñas no coinciden.");
            valido = false;
        }

        // Si hay errores, no enviamos el formulario
        if (form.querySelector(".is-invalid")) return;

        // Si todo está correcto, enviamos el formulario
        form.submit();
    });

    function mostrarError(campo, mensaje) {
        const input = form.querySelector(`[name="${campo}"]`);
        const feedback = input.nextElementSibling;
        input.classList.add("is-invalid");
        feedback.textContent = mensaje;
    }

    // --- Validación AJAX del correo (blur) ---
    const correoInput = form.querySelector('input[name="correo"]');
    correoInput.addEventListener("blur", async() => {
        const correo = correoInput.value.trim();
        if (correo === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) return;

        const formData = new FormData();
        formData.append("correo", correo);

        try {
            const response = await fetch("validaCorreo.php", { method: "POST", body: formData });
            const data = await response.json();
            const feedback = correoInput.nextElementSibling;

            if (data.status === "exists") {
                correoInput.classList.add("is-invalid");
                feedback.textContent = data.message;
            } else {
                correoInput.classList.remove("is-invalid");
                feedback.textContent = "";
            }
        } catch (err) {
            console.error("Error verificando el correo:", err);
        }
    });
});