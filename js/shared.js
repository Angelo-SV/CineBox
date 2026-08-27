/* ===============================
   UTILIDADES COMPARTIDAS
   Usadas por varias páginas: formato de moneda, burbuja del carrito,
   modal de mensaje del carrito y validaciones de formulario comunes.
   =============================== */

function formatoCRC(valor) {
    return new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        minimumFractionDigits: 2
    }).format(valor);
}

function actualizarBurbuja(cantidad) {
    const cartCount = document.getElementById("cart-count");
    if (!cartCount) return;
    cartCount.textContent = cantidad;
    cartCount.style.display = cantidad == 0 ? "none" : "inline-block";
}

function mostrarModalCarrito(mensaje, agregado) {
    const texto = document.getElementById('carritoMensajeTexto');
    texto.innerHTML = mensaje;
    const btnIr = document.getElementById('btnIrCarrito');
    btnIr.style.display = agregado ? 'inline-block' : 'none';
    const modal = new bootstrap.Modal(
        document.getElementById('modalCarritoMsg')
    );
    modal.show();
}

function validarCorreo(correo) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
}

function validarTelefono(tel) {
    return /^[0-9+\-\s]{6,20}$/.test(tel);
}
