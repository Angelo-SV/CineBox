/* ===============================
   MOSTRAR MODAL ÉXITO
================================ */
function mostrarModalExito() {
    const modalEl = document.getElementById('modalAlquilerExito');
    const modal = new bootstrap.Modal(modalEl);
    modalEl.addEventListener('hidden.bs.modal', function() {
        location.reload();
    }, { once: true });

    modal.show();
}

/* ===============================
   MOSTRAR MODAL ERROR
================================ */
function mostrarModalError(msg) {
    document.getElementById('errorAlquilerTexto').innerText = msg;
    const modalEl = document.getElementById('modalAlquilerError');
    const modal = new bootstrap.Modal(modalEl);
    modalEl.addEventListener('hidden.bs.modal', function() {
        location.reload();
    }, { once: true });
    modal.show();
}
/* ===============================
   CLICK BOTÓN PRINCIPAL
================================ */
function alquilarTodo() {
    const metodo = document.getElementById("metodoPago").value;
    const btn = document.getElementById("btnAlquilarTodo");
    if (metodo == 1) {
        const modal = new bootstrap.Modal(
            document.getElementById('modalTarjeta')
        );
        modal.show();
        return;
    }
    // PAYPAL
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Procesando...`;
    setTimeout(() => {
        procesarAlquiler(metodo, null);
    }, 150);
}
/* ===============================
   CONFIRMAR TARJETA
================================ */
function confirmarPagoTarjeta() {
    const btn = document.getElementById("btnConfirmarTarjeta");
    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Procesando...
    `;
    // pequeño delay para que el spinner sí se pinte
    setTimeout(() => {
        procesarAlquiler(1, 'tarjeta');
    }, 150);
}
/* ===============================
   PROCESAR ALQUILER (CORREGIDO)
================================ */
function procesarAlquiler(metodo, origen) {
    const btnPrincipal = document.getElementById("btnAlquilarTodo");
    const btnTarjeta = document.getElementById("btnConfirmarTarjeta");
    fetch("/Videoteca_ElResplandor/carrito-alquilar", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ metodo_pago: metodo })
        })
        .then(r => r.json())
        .then(data => {
            // 🔹 RESTAURAR BOTONES SIEMPRE
            if (btnPrincipal) {
                btnPrincipal.disabled = false;
                btnPrincipal.innerHTML = "🎬 Alquilar todo";
            }
            if (btnTarjeta) {
                btnTarjeta.disabled = false;
                btnTarjeta.innerHTML = "Confirmar pago";
            }
            // 🔹 CERRAR MODAL TARJETA SI APLICA
            if (origen === 'tarjeta') {
                const modalTarjetaEl = document.getElementById('modalTarjeta');
                const instancia = bootstrap.Modal.getInstance(modalTarjetaEl) ||
                    new bootstrap.Modal(modalTarjetaEl);
                instancia.hide();
            }
            // 🔹 MOSTRAR RESULTADO
            if (data.ok) {
                mostrarModalExito();
            } else {
                mostrarModalError(data.msg || "Error en el alquiler.");
            }
        })
        .catch(() => {
            if (btnPrincipal) {
                btnPrincipal.disabled = false;
                btnPrincipal.innerHTML = "🎬 Alquilar todo";
            }
            if (btnTarjeta) {
                btnTarjeta.disabled = false;
                btnTarjeta.innerHTML = "Confirmar pago";
            }
            if (origen === 'tarjeta') {
                const modalTarjetaEl = document.getElementById('modalTarjeta');
                const instancia = bootstrap.Modal.getInstance(modalTarjetaEl) ||
                    new bootstrap.Modal(modalTarjetaEl);
                instancia.hide();
            }
            mostrarModalError("Error de conexión con el servidor.");
        });
}
/* ===============================
   ELIMINAR ITEM
================================ */
function eliminarItem(idPelicula) {
    fetch("/Videoteca_ElResplandor/carrito-eliminar", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pelicula: idPelicula })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                location.reload();
            } else {
                alert("No se pudo eliminar el item");
            }
        });
}