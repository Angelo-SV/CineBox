document.addEventListener('DOMContentLoaded', () => {
    const btnToggle = document.getElementById('btnMostrarReview');
    const container = document.getElementById('reviewFormContainer');
    const form = document.getElementById('formReview');
    const btnEnviar = document.getElementById('btnEnviarReview');
    const loader = document.getElementById('reviewLoader');
    const btnText = document.getElementById('reviewBtnText');
    const inputCal = document.getElementById('inputCalificacion');
    const inputCom = document.getElementById('inputComentario');
    const errCal = document.getElementById('errorCalificacion');
    const errCom = document.getElementById('errorComentario');
    // ===== Toggle form =====
    if (btnToggle && container) {
        btnToggle.addEventListener('click', () => {
            container.style.display =
                container.style.display === 'none' ?
                'block' :
                'none';
        });
    }
    if (!form) return;
    // ===== Helpers =====
    function showError(el, msg) {
        el.textContent = msg;
        el.style.display = 'block';
    }

    function clearError(el) {
        el.textContent = '';
        el.style.display = 'none';
    }

    function resetBtn() {
        btnEnviar.disabled = false;
        loader.style.display = 'none';
        btnText.textContent = 'Enviar review';
    }
    // ===== Submit =====
    form.addEventListener('submit', e => {
        e.preventDefault();
        clearError(errCal);
        clearError(errCom);
        const cal = parseInt(inputCal.value);
        const comentario = inputCom.value.trim();
        let valido = true;
        // ---- Validar calificación ----
        if (isNaN(cal)) {
            showError(errCal, 'Debe indicar una calificación');
            valido = false;
        } else if (cal < 1 || cal > 10) {
            showError(errCal, 'La calificación debe estar entre 1 y 10');
            valido = false;
        }
        // ---- Validar comentario ----
        if (comentario.length === 0) {
            showError(errCom, 'Debe escribir un comentario');
            valido = false;
        } else if (comentario.length < 5) {
            showError(errCom, 'El comentario es demasiado corto');
            valido = false;
        }
        if (!valido) return;
        // ===== Loader =====
        btnEnviar.disabled = true;
        loader.style.display = 'inline-block';
        btnText.textContent = 'Enviando...';
        const data = new FormData(form);
        fetch(window.BASE_PATH + '/review-guardar', {
                method: 'POST',
                body: data
            })
            .then(r => r.json())
            .then(d => {
                if (d.ok) {
                    location.reload();
                } else {
                    showError(errCom, d.msg || 'Error guardando review');
                    resetBtn();
                }
            })
            .catch(() => {
                showError(errCom, 'Error de conexión');
                resetBtn();
            });
    });

});
/* ===============================
   FORMATOS
   =============================== */
function formatoCRC(valor) {
    return new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        minimumFractionDigits: 2
    }).format(valor);
}
// ===== STAR RATING =====
const stars = document.querySelectorAll('#starRating .star');
let selectedRating = 0;
stars.forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.dataset.value);
        document.getElementById('inputCalificacion').value = selectedRating;
        stars.forEach(s => {
            s.textContent =
                parseInt(s.dataset.value) <= selectedRating ?
                '★' :
                '☆';
        });
    });
});
// ===== FAVORITOS DETALLE (con loader) =====
function toggleFavoritoDetalle(idPelicula, boton) {
    const textoOriginal = boton.textContent;
    const clasesOriginales = boton.className;
    boton.disabled = true;
    boton.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
    `;
    fetch(`${window.BASE_PATH}/favoritos-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) {
                boton.className = clasesOriginales;
                boton.textContent = textoOriginal;
                return;
            }
            if (data.favorito) {
                boton.classList.remove('btn-outline-warning');
                boton.classList.add('btn-warning');
                boton.innerHTML = `
                    <i class="bi bi-check"></i>
                    Mi Lista
                `;
            } else {
                boton.classList.remove('btn-warning');
                boton.classList.add('btn-outline-warning');
                boton.innerHTML = `
                    <i class="bi bi-plus"></i>
                    Mi Lista
                `;
            }
            const msg = data.favorito ?
                "❤️ Añadido a tu lista" :
                "❌ Eliminado de tu lista";
            document.getElementById("favMensaje").innerText = msg;
            const modal = new bootstrap.Modal(
                document.getElementById('modalFav')
            );
            modal.show();
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.className = clasesOriginales;
            boton.textContent = textoOriginal;
            alert("Error actualizando favoritos");
        });
}

function toggleCarritoDetalle(idPelicula, boton) {
    const original = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
    `;
    fetch(`${window.BASE_PATH}/carrito-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) {
                boton.innerHTML = original;
                mostrarModalCarrito("Error procesando carrito", false);
                return;
            }
            if (data.en_carrito) {
                boton.classList.remove('btn-outline-warning');
                boton.classList.add('btn-warning');
                boton.innerHTML = `
                    <i class="bi bi-cart-fill"></i>
                    En carrito
                `;
                mostrarModalCarrito(
                    "Película añadida al carrito",
                    true
                );
            } else {

                boton.classList.remove('btn-warning');
                boton.classList.add('btn-outline-warning');
                boton.innerHTML = `
                    <i class="bi bi-cart"></i>
                    Agregar
                `;
                mostrarModalCarrito(
                    "Película eliminada del carrito",
                    false
                );
            }
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.innerHTML = original;
            mostrarModalCarrito("Error de conexión", false);
        });
}

function actualizarBurbuja(cantidad) {
    const cartCount = document.getElementById("cart-count");
    if (!cartCount) return;
    cartCount.textContent = cantidad;
    cartCount.style.display = cantidad == 0 ? "none" : "inline-block";
}
/* ===============================
   MODAL MENSAJE CARRITO
=============================== */
function mostrarModalCarrito(mensaje, agregado) {
    const texto = document.getElementById('carritoMensajeTexto');
    texto.innerHTML = mensaje;
    const btnIr = document.getElementById('btnIrCarrito');
    if (agregado) {
        btnIr.style.display = 'inline-block';
    } else {
        btnIr.style.display = 'none';
    }
    const modal = new bootstrap.Modal(
        document.getElementById('modalCarritoMsg')
    );
    modal.show();
}
/* ===============================
   LIMPIAR MODAL TRANSACCIÓN
=============================== */
function limpiarModalAlquiler() {
    document.getElementById('alquilerLoader').classList.remove('d-none');
    document.getElementById('alquilerContenido').classList.add('d-none');
    document.getElementById('alqImagen').src = '';
    document.getElementById('alqTitulo').innerText = '';
    document.getElementById('alqGenero').innerText = '';
    document.getElementById('alqDirector').innerText = '';
    document.getElementById('alqPrecio').innerText = '';
    document.getElementById('alqMetodo').value = 1;
    document.getElementById('alqTarjetaBox').style.display = 'block';
    const btn = document.getElementById('btnConfirmarAlquiler');
    btn.disabled = false;
    btn.innerHTML = 'Confirmar alquiler';
    btn.classList.remove('d-none');
}

/* ===============================
   ABRIR MODAL ALQUILER
=============================== */
function abrirModalAlquiler(id) {
    peliculaAlquilerId = id;
    limpiarModalAlquiler();
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquiler')
    );
    modal.show();
    fetch(`${window.BASE_PATH}/pelicula-json?id=${id}`)
        .then(r => {
            if (!r.ok) throw new Error('HTTP error');
            return r.json();
        })
        .then(data => {
            if (!data.ok || !data.pelicula) {
                throw new Error('Respuesta inválida');
            }
            const p = data.pelicula;
            document.getElementById('alqImagen').src =
                p.IMAGEN || 'img/no-image.jpg';
            document.getElementById('alqTitulo').innerText = p.TITULO;
            document.getElementById('alqGenero').innerText = p.GENERO;
            document.getElementById('alqDirector').innerText = p.DIRECTOR;
            document.getElementById('alqPrecio').innerText = formatoCRC(p.PRECIO);
            document.getElementById('alquilerLoader').classList.add('d-none');
            document.getElementById('alquilerContenido').classList.remove('d-none');
        })
        .catch(err => {
            console.error(err);
            cerrarModalAlquiler();
            mostrarModalError(
                "No se pudo cargar la información de la película"
            );
        });
}
/* ===============================
   CAMBIO MÉTODO PAGO
=============================== */
document.addEventListener('change', e => {
    if (e.target.id === 'alqMetodo') {
        const box = document.getElementById('alqTarjetaBox');
        box.style.display = (e.target.value == 1) ? 'block' : 'none';
    }

});
/* ===============================
   CERRAR MODAL TRANSACCIÓN
=============================== */
function cerrarModalAlquiler() {
    const el = document.getElementById('modalAlquiler');
    const modal = bootstrap.Modal.getInstance(el);
    if (modal) modal.hide();
}

/* ===============================
   MOSTRAR MODAL ÉXITO
=============================== */
function mostrarModalExito() {
    limpiarModalAlquiler();
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquilerExito')
    );
    modal.show();
}
/* ===============================
   MOSTRAR MODAL ERROR
=============================== */
function mostrarModalError(msg) {
    limpiarModalAlquiler();
    document.getElementById('errorAlquilerTexto').innerText = msg;
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquilerError')
    );
    modal.show();
}
/* ===============================
   CONFIRMAR ALQUILER
=============================== */
document.getElementById('btnConfirmarAlquiler')
    .addEventListener('click', () => {
        const btn = document.getElementById('btnConfirmarAlquiler');
        btn.disabled = true;
        btn.innerHTML = 'Procesando...';
        const metodo = document.getElementById('alqMetodo').value;
        fetch(window.BASE_PATH + '/alquilar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    pelicula_id: peliculaAlquilerId,
                    metodo_pago: metodo
                })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = 'Confirmar alquiler';
                cerrarModalAlquiler();
                if (data.ok) {
                    mostrarModalExito();
                } else {
                    mostrarModalError(
                        data.msg || data.error || 'Ocurrió un error procesando el alquiler'
                    );
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = 'Confirmar alquiler';
                cerrarModalAlquiler();
                mostrarModalError('Error de conexión con el servidor');
            });
    });
/* ===============================
   REFRESCAR AL CERRAR MODAL ÉXITO
=============================== */
document
    .getElementById('modalAlquilerExito')
    .addEventListener('hidden.bs.modal', () => {
        location.reload();
    });

function volverPagina() {
    if (document.referrer !== "") {
        history.back();
    } else {
        window.location.href = window.BASE_PATH + "/";
    }
}