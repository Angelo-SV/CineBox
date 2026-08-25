document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const correo = document.getElementById("correo");
    const contrasena = document.getElementById("contrasena");

    form.addEventListener("submit", (e) => {
        let valido = true;
        // Limpiar clases previas
        [correo, contrasena].forEach(campo => campo.classList.remove("is-invalid"));
        // Validar correo
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!correo.value.trim()) {
            correo.classList.add("is-invalid");
            correo.nextElementSibling.textContent = "Debe ingresar su correo electrónico.";
            valido = false;
        } else if (!emailRegex.test(correo.value.trim())) {
            correo.classList.add("is-invalid");
            correo.nextElementSibling.textContent = "El formato del correo no es válido.";
            valido = false;
        }
        // Validar contraseña
        if (!contrasena.value.trim()) {
            contrasena.classList.add("is-invalid");
            contrasena.nextElementSibling.textContent = "Debe ingresar su contraseña.";
            valido = false;
        }
        // Evitar envío si hay errores
        if (!valido) {
            e.preventDefault();
        }
    });
});