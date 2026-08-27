let peliculaAlquilerId = null;
/* ===============================
   FAVORITOS
=============================== */
function toggleFavoritoLista(idPelicula, boton) {
    const card = boton.closest(".col-md-3");
    boton.disabled = true;
    boton.innerHTML =
        `<span class="spinner-border spinner-border-sm"></span>`;
    fetch(`${window.BASE_PATH}/favoritos-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) return;
            if (!data.favorito) {
                document.getElementById("favMensaje")
                    .innerText = "❌ Eliminado de tu lista";
                const modal = new bootstrap.Modal(
                    document.getElementById('modalFav')
                );
                modal.show();
                card.style.transition = "all .3s ease";
                card.style.opacity = "0";
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll(".col-md-3").length === 0)
                        location.reload();
                }, 300);
            }
        })
        .catch(err => {

            console.error(err);
            alert("Error actualizando favoritos");
        });
}
/* ===============================
   CARRITO
=============================== */
function toggleCarritoFavoritos(idPelicula, boton) {
    const original = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML =
        `<span class="spinner-border spinner-border-sm"></span>`;
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
                boton.classList.remove("btn-outline-warning");
                boton.classList.add("btn-warning");
                boton.innerHTML = `
                    <i class="bi bi-cart-fill"></i>
                    En carrito
                `;
                mostrarModalCarrito(
                    "Película añadida al carrito",
                    true
                );
            } else {
                boton.classList.remove("btn-warning");
                boton.classList.add("btn-outline-warning");
                boton.innerHTML = `
                    <i class="bi bi-cart"></i>
                    Agregar al Carrito
                `;
                mostrarModalCarrito(
                    "Película eliminada del carrito",
                    false
                );
            }
            if (data.cantidad !== undefined)
                actualizarBurbuja(data.cantidad);
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.innerHTML = original;
            mostrarModalCarrito("Error de conexión", false);
        });
}
/* ===============================
   MODAL CARRITO
=============================== */
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
/* ===============================
   BURBUJA CARRITO
=============================== */
function actualizarBurbuja(cantidad) {
    const cartCount = document.getElementById("cart-count");
    if (!cartCount) return;
    cartCount.textContent = cantidad;
    cartCount.style.display =
        cantidad == 0 ? "none" : "inline-block";
}
/* ===============================
   ABRIR MODAL ALQUILER
=============================== */
function alquilarPelicula(id) {
    peliculaAlquilerId = id;
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquiler')
    );
    modal.show();
    fetch(`${window.BASE_PATH}/pelicula-json?id=${id}`)
        .then(r => r.json())
        .then(data => {
            if (!data.ok) throw "error";
            const p = data.pelicula;
            document.getElementById('alqImagen').src =
                p.IMAGEN;
            document.getElementById('alqTitulo').innerText =
                p.TITULO;
            document.getElementById('alqGenero').innerText =
                p.GENERO;
            document.getElementById('alqDirector').innerText =
                p.DIRECTOR;
            document.getElementById('alqPrecio').innerText =
                p.PRECIO;
            document.getElementById('alquilerLoader')
                .classList.add('d-none');
            document.getElementById('alquilerContenido')
                .classList.remove('d-none');
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
   CONFIRMAR ALQUILER
=============================== */
const btnConfirmar = document.getElementById('btnConfirmarAlquiler');
if (btnConfirmar) {
    btnConfirmar.addEventListener('click', () => {
        const btn = document.getElementById('btnConfirmarAlquiler');
        btn.disabled = true;
        btn.innerHTML = "Procesando...";
        const metodo =
            document.getElementById('alqMetodo').value;
        fetch(window.BASE_PATH + '/alquilar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    pelicula_id: peliculaAlquilerId,
                    metodo_pago: metodo
                })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = "Confirmar alquiler";
                const modal =
                    bootstrap.Modal.getInstance(
                        document.getElementById('modalAlquiler')
                    );
                modal.hide();
                if (data.ok) {
                    const exito = new bootstrap.Modal(
                        document.getElementById(
                            'modalAlquilerExito'
                        )
                    );
                    exito.show();
                } else {
                    document.getElementById(
                        'errorAlquilerTexto'
                    ).innerText = data.msg;
                    const error = new bootstrap.Modal(
                        document.getElementById(
                            'modalAlquilerError'
                        )
                    );
                    error.show();
                }
            });
    });
}